<?php

namespace App\Http\Controllers;

use App\Helpers\GeneralHelp;
use App\Models\Client;
use App\Models\Cotization;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;

class PdfController extends Controller
{

    public function index($record, string $document = "aviso")
    {
        switch ($document) {
            case 'aviso':
                $this->aviso_pricacidad($record);
                break;
            case 'cotizacion':
                $this->cotizacion($record);
                break;
            case 'contrato':
                $this->contrato($record);
                break;
            default:
                dd('Otro Documento:' . $document);
                break;
        }
        return true;
    }

    /**
     * Crea avisod e privacidad para un cliente
     *
     * @param [modelo] $data: Cliente
     * @return void
     */
    public function aviso_pricacidad($record)
    {

        $data = Client::findOrFail($record);

        $filePath = public_path('pdfs/aviso de privacidad.pdf');
        $outputFilePath = public_path("sample_output.pdf");
        $fpdi = new FPDI;
        $fpdi->SetFont("arial");
        $fpdi->SetFontSize(11);
        $fpdi->SetTextColor(0, 0, 185);
        $count = $fpdi->setSourceFile($filePath);


        for ($i = 1; $i <= $count; $i++) {
            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
            $fpdi->useTemplate($template);
            $fpdi->SetXY(134, 22);

            $fpdi->Write(0, now()->format('d'));
            $fpdi->Text(152, 23, GeneralHelp::spanish_month(now(), 's'));

            if ($data) {
                $standard_name = GeneralHelp::normalize_text($data->name);
                $standard_name = ucwords($standard_name);

                $fpdi->Text(42, 133, $standard_name);    // Nombre
                $fpdi->Text(44, 145, $data->phone);   // Teléfono
                $fpdi->Text(61, 157, strtolower($data->email)); // Correo
                $address = $data->address . ' Col: ' . $data->colony . ' en ' . $data->city->name; // Calle y número
                // $address = strtr($address, array_combine($buscar, $reemplazar));
                $address = GeneralHelp::normalize_text($address);
                $fpdi->Text(44, 170, $address);
                $municipality_state = $data->municipality->name . ',' . $data->state->abbreviated . '   C.P. ' . $data->zipcode;
                $fpdi->Text(44, 175, $municipality_state);
                $fpdi->Text(90, 183, $data->rfc); // RFC
                $fpdi->Text(64, 195, $data->ine); // INE
                $fpdi->Text(40, 265, $standard_name); // Nombre para firmar
            }
        }
        return $fpdi->Output($outputFilePath, 'I');
    }

    /**
     * Crea cotización formal
     *
     * @param [model] $data: Cotización
     * @return void
     */
    public function cotizacion($record)
    {
        // TODO: ¿Se va agregar en algun lado la retención ISR?
        $data = Cotization::findOrFail($record);
        $filePath = public_path('pdfs/cotizacion formal.pdf');
        $outputFilePath = public_path("output.pdf");
        $fpdi = new FPDI;
        $fpdi->SetFont("arial");
        $fpdi->SetFontSize(8);
        $fpdi->SetTextColor(0, 0, 0);
        $count = $fpdi->setSourceFile($filePath);



        for ($i = 1; $i <= $count; $i++) {

            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
            $fpdi->useTemplate($template);

            if ($data && $i == 1) {
                $fpdi->SetXY(164, 27);

                $fecha_dia = $data->fecha->format('d');
                $fpdi->Text(166, 28, $fecha_dia);

                $fpdi->Text(175, 28, strtoupper(GeneralHelp::spanish_month($data->fecha, 's')));
                $standard_name = GeneralHelp::normalize_text($data->client->name);
                $standard_name = ucwords($standard_name);
                $fpdi->Text(43, 64, $standard_name);                          // Nombre

                $standar_description = GeneralHelp::normalize_text($data->description);
                $arrayDescripcion = explode("\n", $standar_description);
                $posx = 104;
                $posy = 90;

                // Descripción

                foreach ($arrayDescripcion as $linea) {
                    $palabras = wordwrap($linea, 40, "\n", true);
                    $lineasSeparadas = explode("\n", $palabras);
                    foreach ($lineasSeparadas as $linea_separada) {
                        $fpdi->text($posx, $posy, $linea_separada);
                        $posy = $posy + 5;
                    }
                }

                $totImagesCotization = $data->images->count();

                if ($totImagesCotization) {
                    // switch ($totImagesCotization) {
                    //     case 1:
                    //         $posx = 105;
                    //         break;
                    //     case 2:
                    //         $posx = 85;
                    //         break;
                    //     case 3:
                    //         $posx = 65;
                    //         break;
                    //     case 4:
                    //         $posx = 45;
                    //         break;
                    //     case 5:
                    //         $posx = 25;
                    //         break;
                    //     default:
                    //         $posx = 32;
                    //         break;
                    // }
                    $posx = 15;

                    $posy = 90;
                    foreach ($data->images->sortBy('id') as $image) {

                        $imageUrl = storage_path('app/public/' . $image->image);
                        $fpdi->Image($imageUrl, $posx, $posy, 15, 15);
                        $image_description = GeneralHelp::normalize_text($image->description);
                        $image_description_array = explode("\n", $image_description);

                        foreach ($image_description_array as $linea) {
                            $palabras = wordwrap($linea, 40, "\n", true);
                            $lineasSeparadas = explode("\n", $palabras);
                            foreach ($lineasSeparadas as $linea_separada) {
                                $fpdi->text($posx + 18, $posy, $linea_separada);
                                $posy = $posy + 5;
                            }
                        }

                        $posy = $posy + 10;
                    }

                }


                // Fecha de entrega

                if ($data->fecha_entrega) {
                    $fpdi->SetFontSize(14);
                    $fecha_entrega_espanol = GeneralHelp::normalize_text(GeneralHelp::spanish_date($data->fecha_entrega, 'n', 'n', 'dmy'));

                    $fpdi->text(95, 185, 'Fecha de Entrega: ' . $fecha_entrega_espanol);
                }

                // Totales
                $fpdi->SetFontSize(11);

                if ($data->envio > 0) {
                    $fpdi->text(194 - strlen(number_format($data->envio, 2, '.', ',')), 195, number_format($data->envio, 2, '.', ','));
                }

                $fpdi->text(194 - strlen(number_format($data->subtotal, 2, '.', ',')), 200, number_format($data->subtotal, 2, '.', ','));

                if ($data->iva > 0) {
                    $fpdi->text(195 - strlen(number_format($data->iva, 2)), 205, number_format($data->iva, 2));
                } else {
                    $fpdi->Text(105, 212, 'NO INCLUYE IVA');
                }



                if ($data->retencion_isr > 0) {
                    $texto_retencion_isr = 'RETENCION ISR $' . number_format($data->retencion_isr, 2);
                    $fpdi->text(104, 218, $texto_retencion_isr);

                }

                if ($data->descuento > 0) {
                    $fpdi->text(192 - strlen(number_format($data->descuento)), 212, number_format($data->descuento, 2));
                }


                $fpdi->text(191 - strlen(number_format($data->total)), 218, number_format($data->total, 2));
            }
        }
        return $fpdi->Output($outputFilePath, 'I');
    }

    /**
     * Contrato en base a una orden de compra
     * @param mixed $record
     * @return void
     */
    public function contrato($record)
    {
        $data = Order::find($record)->with('client')->first();
        $filePath = public_path('pdfs/contrato.pdf');
        $outputFilePath = public_path("output.pdf");

        $fpdi = new FPDI;

        $fpdi->SetFont("arial");
        $fpdi->SetFontSize(8);
        $fpdi->SetTextColor(0, 0, 0);

        $count = $fpdi->setSourceFile($filePath);

        for ($i = 1; $i <= $count; $i++) {
            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
            $fpdi->useTemplate($template);

            if ($data && $i == 1) {
                $this->contrato_pagina_1($fpdi, $data);
            }
            if ($data && $i == 2) {
                $this->contrato_pagina_2($fpdi, $data);
            }

            if ($data && $i == 3) {
                $this->contrato_pagina_3($fpdi, $data);
            }

        }
        return $fpdi->Output($outputFilePath, 'I');
    }

    private function contrato_pagina_1($fpdi, $data)
    {
        $fpdi->SetFont("arial", "B", 12);
        $fpdi->text(80, 26, $data->client->full_name);
        $fpdi->SetFont("arial", "", size: 9);
        $fpdi->text(144, 96, $data->client->rfc);

        // Dirección del cliente
        if ($data->client->interior_number) {
            $direccion = "Calle " . $data->client->street . ' No. ' . $data->client->number . ' Int:  ' . $data->client->interior_number;
            $direccion .= ' Col: ' . $data->client->colony . ' en ' . $data->client->city->name . ',' . $data->client->state->abbreviated;
        } else {
            $direccion = "Calle " . $data->client->street . ' No. ' . $data->client->number . ' Col: ' . $data->client->colony . ' en ' . $data->client->city->name . ',' . $data->client->state->abbreviated;
        }


        $fpdi->text(50, 100.5, $direccion);
        $fpdi->text(45, 107, $data->client->phone);
        $fpdi->text(105, 107, $data->client->email);

        $fpdi->text(30, 129, "UNA BOTARGA (30,129)");
        // Fecha de la orden de compra
        $orden_dia = $data->date->format('d');
        $orden_mes = GeneralHelp::spanish_month($data->date, 's');
        $order_axo = $data->date->format('Y');
        $fecha_orden = $orden_dia . '-' . $orden_mes . '-' . $order_axo;
        $fpdi->SetFont("arial", "", size: 10);
        $fpdi->Text(104, 133.5, $fecha_orden);
        $fpdi->Text(144, 133.5, $data->id);

        $fontSize = 11; // Tamaño por defecto
        $fpdi->SetFont("arial", "B", $fontSize); // Negritas

        $fontSizes = [
            45 => 7,  // Largo > 45:Tamaño 7
            35 => 9,  // Largo > 35:Tamaño 9
            0 => 11  // Defecto (Largo <= 35):Tamaño 11
        ];

        $total_letras = GeneralHelp::normalize_text(GeneralHelp::to_letters_rounded((int) $data->total));
        $anticipo_letras = GeneralHelp::normalize_text(GeneralHelp::to_letters_rounded($data->advance));
        $pendiente_letras = GeneralHelp::normalize_text(GeneralHelp::to_letters_rounded($data->pending_balance));


        // Total
        foreach ($fontSizes as $lengthThreshold => $size) {
            if (strlen($total_letras) > $lengthThreshold) {
                $fontSize = $size;
                break;
            }
        }
        $fpdi->SetFont("arial", "B", $fontSize); // Negritas
        $fpdi->Text(27, 142, $total_letras);

        // Anticipo
        foreach ($fontSizes as $lengthThreshold => $size) {
            if (strlen($anticipo_letras) > $lengthThreshold) {
                $fontSize = $size;
                break;
            }
        }

        $fpdi->SetFont("arial", "B", $fontSize);
        $fpdi->Text(105, 150, $anticipo_letras);


        $fontSizes = [
            40 => 7,  // Largo > 40:Tamaño 7
            35 => 9,  // Largo > 35:Tamaño 9
            0 => 10  // Defecto (Largo <= 35):Tamaño 10
        ];
        // Pendiente
        foreach ($fontSizes as $lengthThreshold => $size) {
            if (strlen($pendiente_letras) > $lengthThreshold) {
                $fontSize = $size;
                break;
            }
        }
        $fpdi->SetFont("arial", "B", $fontSize);
        $fpdi->Text(118, 161, $pendiente_letras);


        $fpdi->SetFont("arial", "", 10);
        // Fecha de promeso ee pago
        // // Se revisa si tiene fecha de promesa
        if ($data->payment_promise_date) {
            $payment_promise_date = Carbon::parse($data->payment_promise_date);
            $fpdi->Text(50, 168, $payment_promise_date->format('d'));
            $nombre_mes = GeneralHelp::spanish_month($payment_promise_date, 'l');
            if (strlen($nombre_mes) > 7) {
                $fpdi->SetFont("arial", "B", 7);
            }
            $fpdi->Text(82, 167.5, $nombre_mes);
            $fpdi->SetFont("arial", "", 10);

            $fpdi->Text(107, 168, $payment_promise_date->format('Y'));
        }


        // ¿Requiere Factura?
        $fpdi->SetFont("arial", "", 10);
        if ($data->require_invoice) {
            $fpdi->Text(87, 202, 'X');
        } else {
            $fpdi->Text(110, 202, 'X');
        }
    }

    /**
     * Página 2
     * @param mixed $fpdi
     * @param mixed $data
     * @return void
     */
    private function contrato_pagina_2($fpdi, $data)
    {
        $fpdi->SetFont("arial", "", 11);
        // Plazo de Entrega
        $deliveryDate = Carbon::parse($data->delivery_date);
        $date = Carbon::parse($data->date);
        $daysDifference = $date->diffInDays($deliveryDate);
        $fpdi->Text(83, 50, $daysDifference);

        // Fecha de Firma
        $fpdi->Text(113, 257.25, $data->date_approved->format('d'));
        $nombre_mes = GeneralHelp::spanish_month($data->date_approved, 'l');
        $fpdi->Text(152, 257.25, $nombre_mes);
        $fpdi->SetFont("arial", "", 11);
        $fpdi->Text(22, 261.5, $data->date_approved->format('Y'));

        // Nombre del Comprador
        $fpdi->SetFont("arial", "B", 12);

        $fpdi->text(45, 273.5, $data->client->full_name);

        // Nombre del Vendedor

        $fpdi->text(125, 273.5, $data->user->name);

    }

    /**
     * Página 3 Formulario
     * @param mixed $fpdi
     * @param mixed $data
     * @return void
     */
    private function contrato_pagina_3($fpdi, $data)
    {
        // Folio
        $fpdi->SetFont("arial", "B", 14);
        $fpdi->Text(192, 15, $data->id);

        // Vendedor
        $fpdi->SetFont("arial", "B", 12);
        $fpdi->Text(90, 55, $data->user->name);

        // Fecha del pedido
        $fpdi->SetFont("arial", "", 12);
        $fpdi->Text(15, 73.5, $data->date->format('d'));
        $nombre_mes = GeneralHelp::spanish_month($data->date, 's');
        $fpdi->Text(32, 73.5, $nombre_mes);
        $fpdi->SetFont("arial", "", 11);
        $fpdi->Text(54, 73.5, $data->date->format('Y'));

        // Nombre del cliente
        $fpdi->SetFont("arial", "B", 12);
        $fpdi->text(48, 80, $data->client->full_name);
        // Rfc
        $fpdi->text(162, 80.5, $data->client->rfc);
        // Direccion
        $address = $data->street . ' ' . $data->number;
        if ($data->interior_number) {
            $address .= ' Int ' . $data->interior_number;
        }

        $address .= ' Col: ' . $data->colony;
        $address = GeneralHelp::normalize_text($address);
        $fpdi->text(30, 87, $address);

        // Ciudad
        $fpdi->text(24, 93.5, $data->city->name);

        // Código postal - Teléfono y Celular
        $fpdi->text(78, 93.5, $data->zipcode);
        $fpdi->text(105, 93.5, $data->client->phone);
        $fpdi->text(165, 93.5, $data->client->mobile);

        // Correo Electrónico
        if ($data->client->email && strlen($data->client->email) > 23) {
            $fpdi->SetFont("arial", "B", 10);
        }

        if ($data->client->email && strlen($data->client->email) > 30) {
            $fpdi->SetFont("arial", "", 9);
        }
        $fpdi->text(153.5, 99.5, $data->client->email);

        // Fecha promesa de entrega
        if ($data->delivery_date) {
            $delivery_date = Carbon::parse($data->delivery_date);
            $fpdi->SetFont("arial", "B", 11);
            $fpdi->Text(45, 239, $delivery_date->format('d'));
            $nombre_mes = GeneralHelp::spanish_month($delivery_date, 'l');
            if (strlen($nombre_mes) > 7) {
                $fpdi->SetFont("arial", "B", 7);
            }
            $fpdi->Text(61, 239, $nombre_mes);
            $fpdi->SetFont("arial", "B", 10);
            $fpdi->Text(83.5, 239, $delivery_date->format('Y'));
        }

        // Totales

        $fpdi->Text(201 - strlen(number_format($data->total, 2, '.', ',')), 243, number_format($data->total, 2, '.', ','));
        $fpdi->Text(202 - strlen(number_format($data->advance, 2, '.', ',')), 248, number_format($data->advance, 2, '.', ','));
        $fpdi->Text(201 - strlen(number_format($data->pending_balance, 2, '.', ',')), 253, number_format($data->pending_balance, 2, '.', ','));

        // Fecha de Envío
        $fpdi->SetFont("arial", "B", 11);
        $fpdi->Text(45, 257, $data->delivery_date->format('d'));
        $nombre_mes = GeneralHelp::spanish_month($data->delivery_date, 'l');
        if (strlen($nombre_mes) > 7) {
            $fpdi->SetFont("arial", "B", 7);
        }
        $fpdi->Text(61, 257, $nombre_mes);
        $fpdi->SetFont("arial", "B", 10);
        $fpdi->Text(83.5, 257, $data->delivery_date->format('Y'));

    }
}
