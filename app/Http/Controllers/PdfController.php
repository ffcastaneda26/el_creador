<?php
namespace App\Http\Controllers;

use App\Helpers\GeneralHelp;
use App\Mail\DocumentEmail;
use App\Models\Client;
use App\Models\Cotization;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use setasign\Fpdi\Fpdi;

class PdfController extends Controller
{

    public function index($record, string $document, string $output = "view")
    {
        switch ($document) {
            case 'aviso':
                $pdfContent = $this->aviso_pricacidad($record);
                $fileName   = 'aviso_de_privacidad.pdf';
                break;
            case 'cotizacion':
                $pdfContent = $this->cotizacion($record);
                $fileName   = 'cotizacion.pdf';
                break;
            case 'contrato':
                $pdfContent = $this->contrato($record);
                $fileName   = 'contrato.pdf';
                break;
            default:
                abort(404, 'Tipo de documento no válido.');
        }

        if ($output === 'view') {
            return response($pdfContent, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
        } elseif ($output === 'mail') {
            try {
                Mail::to(Auth::user()->email)->send(new DocumentEmail(ucfirst($document), $pdfContent));
                return response()->json(['message' => 'El documento ha sido enviado por correo electrónico.']);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Error al enviar el correo electrónico: ' . $e->getMessage()], 500);
            }
        }
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

        $filePath       = public_path('pdfs/aviso de privacidad.pdf');
        $outputFilePath = public_path("sample_output.pdf");
        $fpdi           = new FPDI;
        $fpdi->SetFont("arial");
        $fpdi->SetFontSize(11);
        $fpdi->SetTextColor(0, 0, 185);
        $count = $fpdi->setSourceFile($filePath);

        for ($i = 1; $i <= $count; $i++) {
            $template = $fpdi->importPage($i);
            $size     = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $fpdi->useTemplate($template);
            $fpdi->SetXY(128, 22.5);

            $fpdi->Write(0, now()->format('d'));
            $fpdi->Text(145, 23.2, GeneralHelp::spanish_month(now(), 'l'));
            $fpdi->Text(170, 23.2, now()->format('Y'));

            if ($data) {
                $standard_name = GeneralHelp::normalize_text($data->full_name);
                $standard_name = ucwords($standard_name);

                $fpdi->Text(50, 121, $standard_name);                                              // Nombre
                                                                                                   // Nombre
                $fpdi->Text(52, 132, $data->phone);                                                // Teléfono
                $fpdi->Text(66, 143.1, strtolower($data->email));                                  // Correo
                $address = $data->address . ' Col: ' . $data->colony . ' en ' . $data->city->name; // Calle y número
                                                                                                   // $address = strtr($address, array_combine($buscar, $reemplazar));
                $address = GeneralHelp::normalize_text($address);
                $fpdi->Text(50, 155, $address);
                $municipality_state = $data->municipality->name . ',' . $data->state->abbreviated . '   C.P. ' . $data->zipcode;
                $fpdi->Text(50, 160, $municipality_state);
                $fpdi->Text(92, 166.1, $data->rfc);   // RFC
                $fpdi->Text(68, 177.5, $data->ine);   // INE
                $fpdi->Text(30, 226, $standard_name); // Nombre para firmar
            }
        }
        return $fpdi->Output('S');
    }

    /**
 * Crea cotización formal
 *
 * @param [model] $record: Cotización (ID)
 * @return string
 */
    public function cotizacion($record)
    {
        $data     = Cotization::findOrFail($record);
        $filePath = public_path('pdfs/cotizacion formal.pdf');

        // ✅ Validación de existencia del PDF
        if (!file_exists($filePath)) {
            abort(404, 'No existe el PDF base en: ' . $filePath);
        }

        $fpdi = new FPDI;

        // ✅ Evita problemas de fuente (FPDF normalmente trae Arial por default)
        // Si tu versión no soporta SetFont("arial") sin estilo, usa "Arial"
        $fpdi->SetFont("Arial");
        $fpdi->SetFontSize(8);
        $fpdi->SetTextColor(0, 0, 0);

        /**
         * ✅ FIX CLAVE:
         * Muchos PDFs (Canva/Word/firmados/optimizados) a veces FPDI los cuenta mal.
         * Cargarlo por StreamReader suele hacer que detecte bien las páginas.
         */
        $count = null;
        try {
            // Si tienes instalado setasign/fpdi-fpdf y setasign/fpdi
            // StreamReader está en: setasign\Fpdi\PdfParser\StreamReader
            $pdfContent = file_get_contents($filePath);
            $count = $fpdi->setSourceFile(\setasign\Fpdi\PdfParser\StreamReader::createByString($pdfContent));
        } catch (\Throwable $e) {
            // Fallback: método clásico
            $count = $fpdi->setSourceFile($filePath);
        }

        // ✅ Si por alguna razón el conteo regresa 0
        if (!$count || $count < 1) {
            abort(500, 'FPDI no pudo leer páginas del PDF base. Revisa el archivo o conviértelo/imprímelo a PDF nuevamente.');
        }

        for ($i = 1; $i <= $count; $i++) {
            $template = $fpdi->importPage($i);
            $size     = $fpdi->getTemplateSize($template);

            $fpdi->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $fpdi->useTemplate($template);

            // ✅ (Opcional) DEBUG para confirmar que sí recorre la página 3
            // Descomenta para ver "PAGINA: X" impreso en cada página
            /*
            $fpdi->SetFont("Arial", "B", 16);
            $fpdi->Text(10, 15, "PAGINA: " . $i . " / " . $count);
            $fpdi->SetFont("Arial", "", 8);
            */

            if ($data && $i == 1) {

                // Fecha completa: México a 21 de Enero del 2026
                $fpdi->SetFont("Arial", "B", 10);
                $fechaCompleta = 'Mexico a ' . $data->fecha->format('d') . ' de ' . ucfirst(GeneralHelp::spanish_month($data->fecha, 'l')) . ' del ' . $data->fecha->format('Y');
                $fpdi->Text(150, 10, $fechaCompleta);

                // Nombre del cliente en negritas
                $standard_name = GeneralHelp::normalize_text($data->client->full_name);
                $standard_name = ucwords($standard_name);
                $fpdi->Text(39, 63, $standard_name); // Nombre completo del cliente

                // Partida
                $posx = 20;
                $posy = 90;
                $fpdi->SetFont("Arial", "B", 11);
                $fpdi->Text($posx, $posy, 1); // Partida
                $fpdi->SetFont("Arial", "", 8);

                $fpdi->SetFont("Arial", "B", 6);
                $fpdi->text(81 - strlen(number_format($data->total)), 203, number_format($data->total, 2));

                $standar_description = GeneralHelp::normalize_text($data->description);
                $arrayDescripcion    = explode("\n", $standar_description);
                $posx                = 104;
                $posy                = 90;

                // Descripción
                foreach ($arrayDescripcion as $linea) {
                    $palabras        = wordwrap($linea, 40, "\n", true);
                    $lineasSeparadas = explode("\n", $palabras);
                    foreach ($lineasSeparadas as $linea_separada) {
                        $fpdi->text($posx, $posy, $linea_separada);
                        $posy = $posy + 5;
                    }
                }
                // Detalles de la cotización
                $partidas = $data->details()->get();

                if ($partidas->count() > 0) {
                    $posx = 38;
                    $posy = 90;

                    foreach ($partidas as $partida) {
                        $fpdi->SetFont("Arial", "B", 11);
                        $fpdi->Text($posx, $posy, $partida->quantity);

                        if ($partida->image) {
                            $imageUrl = storage_path('app/public/' . $partida->image);
                            if (file_exists($imageUrl)) {
                                $fpdi->Image($imageUrl, $posx + 19, $posy, 40, 40);
                            }
                        }

                        $fpdi->SetFont("Arial", "B", 11);
                        $fpdi->text(160, $posy, number_format($partida->price, 2, '.', ','));

                        $monto = round($partida->quantity * $partida->price, 2);
                        $fpdi->text(182, $posy, number_format($monto, 2, '.', ','));

                        $fpdi->SetFont("Arial", "", 8);
                        $posy = $posy + 25;
                    }
                }

                // Fecha de entrega
                if ($data->fecha_entrega) {
                    $fpdi->SetFontSize(14);
                    $fecha_entrega_espanol = GeneralHelp::normalize_text(
                        GeneralHelp::spanish_date($data->fecha_entrega, 'n', 'n', 'dmy')
                    );
                    $fpdi->text(95, 185, 'Fecha de Entrega: ' . $fecha_entrega_espanol);
                }

                // Totales
                $fpdi->SetFontSize(11);

                if ($data->envio > 0) {
                    $fpdi->text(194 - strlen(number_format($data->envio, 2, '.', ',')), 195, number_format($data->envio, 2, '.', ','));
                }

                // Subtotal
                $fpdi->SetFont("Arial", "B", 11);
                $fpdi->text(195 - strlen(number_format($data->subtotal, 2, '.', ',')), 225, number_format($data->subtotal, 2, '.', ','));
                $fpdi->SetFont("Arial", "", 11);

                if ($data->iva > 0) {
                    $fpdi->SetFont("Arial", "B", 11);
                    $fpdi->text(193 - strlen(number_format($data->iva, 2)), 232.5, number_format($data->iva, 2));
                    $fpdi->SetFont("Arial", "", 11);
                } else {
                    $fpdi->Text(105, 226, 'NO INCLUYE IVA');
                }

                if ($data->retencion_isr > 0) {
                    // Texto
                    $fpdi->SetFont("Arial", "B", 11);
                    $fpdi->Text(125, 238, 'RETENCION ISR');

                    // Monto (alineado a la derecha, más separado)
                    $fpdi->SetFont("Arial", "B", 11);
                    $fpdi->Text(185.5, 238, number_format($data->retencion_isr, 2, '.', ','));

                    $fpdi->SetFont("Arial", "", 11);
                }


                if ($data->descuento > 0) {
                    $fpdi->text(192 - strlen(number_format($data->descuento)), 212, number_format($data->descuento, 2));
                }

                // Total en negritas y más abajo
                $fpdi->SetFont("Arial", "B", 12);
                $fpdi->text(191 - strlen(number_format($data->total)), 245, number_format($data->total, 2));
                $fpdi->SetFont("Arial", "", 11);
            }

            if ($data && $i == 2) {
                // Anticipo del 80% en hoja 2 (ajusta coordenadas si el template cambia)
                $fpdi->SetFont("Arial", "B", 11);
                $anticipo80 = round($data->total * 0.80, 2);
                $fpdi->Text(69.2, 130.2, '$ ' . number_format($anticipo80, 2, '.', ','));
                $fpdi->SetFont("Arial", "", 11);
            }

            // ✅ NUEVO: HOJA 3 (para que “sí la reconozca” y puedas imprimir algo)
            // Aunque tu PDF ya tenga la página 3, aquí puedes poner lo que necesites.
            if ($data && $i == 3) {
                // Ejemplo: imprimir total en la página 3 o cualquier texto legal.
                // Ajusta coordenadas según tu template.
            }
        }

        return $fpdi->Output('S');
    }


    /**
     * Contrato en base a una orden de compra
     * @param mixed $record
     * @return void
     */
    public function contrato($record)
    {
        $data = null;
        $data = Order::where('id', $record)->with('client')->first();

        $filePath       = public_path('pdfs/contrato.pdf');
        $outputFilePath = public_path("output.pdf");

        $fpdi = new FPDI;

        $fpdi->SetFont("arial");
        $fpdi->SetFontSize(8);
        $fpdi->SetTextColor(0, 0, 0);

        $count = $fpdi->setSourceFile($filePath);

        for ($i = 1; $i <= $count; $i++) {
            $template = $fpdi->importPage($i);
            $size     = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], [$size['width'], $size['height']]);
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
        return $fpdi->Output('S');
    }

    private function contrato_pagina_1($fpdi, $data)
    {
        $fpdi->SetFont("arial", "B", 12);
        $fpdi->text(20, 58, GeneralHelp::normalize_text($data->client->full_name));
        // Nombre del cliente en negritas
        $fpdi->SetFont("arial", "", size: 9);
        // Dirección del cliente
        if ($data->client->interior_number) {
            $direccion = "Calle " . $data->client->street . ' No. ' . $data->client->number . ' Int:  ' . $data->client->interior_number;
            $direccion .= ' Col: ' . $data->client->colony . ' en ' . $data->client->city->name . ',' . $data->client->state->abbreviated;
        } else {
            $direccion = "Calle " . $data->client->street . ' No. ' . $data->client->number . ' Col: ' . $data->client->colony . ' en ' . $data->client->city->name . ',' . $data->client->state->abbreviated;
        }

        $fpdi->text(42, 128, GeneralHelp::normalize_text($direccion));

        //
        if ($data->client->rfc) {
            $fpdi->text(125, 123.5, $data->client->rfc);
        }

        $fpdi->text(50, 133.3, $data->client->phone);
        $fpdi->text(140, 133.3, $data->client->email);

        // Nombre de la botarga
        $fpdi->text(25, 158, $data->motley_name);
        // Fecha de la orden de compra
        $orden_dia   = $data->date->format('d');
        $orden_mes   = GeneralHelp::spanish_month($data->date, 'l');
        $order_axo   = $data->date->format('Y');
        $fecha_orden = 'México a ' . $orden_dia . ' de ' . ucfirst($orden_mes) . ' del ' . $order_axo;

        $fpdi->Text(120, 163, $fecha_orden);

        if ($data->folio) {
            $fpdi->Text(18, 168, $data->folio);
        } else {
            $fpdi->Text(18, 168, $data->id);
        }
        $fontSize = 11;                          // Tamaño por defecto
        $fpdi->SetFont("arial", "B", $fontSize); // Negritas
        $fontSizes = [
            60 => 6,
            50 => 7,
            45 => 8,  // Largo > 45:Tamaño 7
            35 => 9,  // Largo > 35:Tamaño 9
            0  => 11, // Defecto (Largo <= 35):Tamaño 11
        ];

        $total_letras     = GeneralHelp::normalize_text(GeneralHelp::to_letters($data->total));
        $anticipo_letras  = GeneralHelp::normalize_text(GeneralHelp::to_letters($data->advance));
        $pendiente_letras = GeneralHelp::normalize_text(GeneralHelp::to_letters($data->pending_balance));

        // Total
        foreach ($fontSizes as $lengthThreshold => $size) {
            if (strlen($total_letras) > $lengthThreshold) {
                $fontSize = $size;
                break;
            }
        }
        $fpdi->SetFont("arial", "B", $fontSize); // Negritas
        $fpdi->Text(130, 178, ucfirst(strtolower($total_letras)));

        // Anticipo
        // foreach ($fontSizes as $lengthThreshold => $size) {
        //     if (strlen($anticipo_letras) > $lengthThreshold) {
        //         $fontSize = $size;
        //         break;
        //     }
        // }

        // $fpdi->SetFont("arial", "B", $fontSize);

        // $fpdi->Text(105, 150, $anticipo_letras);

        // $fontSizes = [
        //     60 => 5,
        //     50 => 6,
        //     45 => 7,  // Largo > 45:Tamaño 7
        //     35 => 8,  // Largo > 35:Tamaño 9
        //     0  => 10, // Defecto (Largo <= 35):Tamaño 10
        // ];
        // // Pendiente
        // foreach ($fontSizes as $lengthThreshold => $size) {
        //     if (strlen($pendiente_letras) > $lengthThreshold) {
        //         $fontSize = $size;
        //         break;
        //     }
        // }
        $fpdi->SetFont("arial", "B", $fontSize);
        // $fpdi->Text(118, 161, $pendiente_letras);

        $fpdi->SetFont("arial", "", 10);
        // Fecha de promeso ee pago
        // // Se revisa si tiene fecha de promesa

        // Fecha promesa de pago se bloquea
        // if ($data->payment_promise_date) {
        //     $payment_promise_date = Carbon::parse($data->payment_promise_date);
        //     $fpdi->Text(50, 168, $payment_promise_date->format('d'));
        //     $nombre_mes = GeneralHelp::spanish_month($payment_promise_date, 'l');
        //     if (strlen($nombre_mes) > 7) {
        //         $fpdi->SetFont("arial", "B", 7);
        //     }
        //     $fpdi->Text(82, 167.5, $nombre_mes);
        //     $fpdi->SetFont("arial", "", 10);

        //     $fpdi->Text(107, 168, $payment_promise_date->format('Y'));
        // }

        // Whats App
        if ($data->phone_whatsApp) {
            $fpdi->text(105, 220, $data->phone_whatsApp);

        }

        // ¿Requiere Factura?
        $fpdi->SetFont("arial", "", 13);
        if ($data->require_invoice) {
            $fpdi->Text(74, 229, 'X');
        } else {
            $fpdi->Text(100, 229, 'X');
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
        $deliveryDate   = Carbon::parse($data->delivery_date);
        $date           = Carbon::parse($data->date);
        $daysDifference = $date->diffInDays($deliveryDate);

        if ($data->days_term) {
            $fpdi->Text(115, 22.5, $data->days_term);

        } else {
            $fpdi->Text(83, 50, $daysDifference);
        }

        // Empresa de envío
        if ($data->shipping_company) {
            if (strlen($data->shipping_company) > 35) {
                $fpdi->SetFont("arial", "", 9);
            } else {
                $fpdi->SetFont("arial", "", 9);
            }
            $fpdi->Text(16, 59, $data->shipping_company);
        }

        // Domicilio empresa envío
        if ($data->shipping_company_address) {
            $posicion_coma = strpos($data->shipping_company_address, ',');
            if ($posicion_coma !== false) {
                $primera_linea = trim(substr($data->shipping_company_address, 0, $posicion_coma));
                $segunda_linea = trim(substr($data->shipping_company_address, $posicion_coma + 1));
            } else {
                $primera_linea = $data->shipping_company_address;
                $segunda_linea = ""; // O lo que sea apropiado para tu lógica
            }

            if ($primera_linea && $posicion_coma !== false) {
                $fpdi->Text(135, 59, GeneralHelp::normalize_text($primera_linea));

                if ($segunda_linea) {
                    $fpdi->Text(17, 63.5, GeneralHelp::normalize_text($segunda_linea));
                }
            } else {
                $fpdi->Text(17, 63.5, GeneralHelp::normalize_text($primera_linea));

            }

        }

        // Costo de envío
        if ($data->shipping_cost) {
            $fontSizes = [
                60 => 6,
                50 => 7,
                45 => 8,  // Largo > 45:Tamaño 7
                35 => 9,  // Largo > 35:Tamaño 9
                0  => 11, // Defecto (Largo <= 35):Tamaño 11
            ];
            $costo_letras = GeneralHelp::normalize_text(GeneralHelp::to_letters($data->shipping_cost));

            foreach ($fontSizes as $lengthThreshold => $size) {
                if (strlen($costo_letras) > $lengthThreshold) {
                    $fontSize = $size;
                    break;
                }
            }
            $fpdi->SetFont("arial", "B", $fontSize);
            $fpdi->Text(120, 73, $costo_letras);
        }

        // Fecha de Firma
        $fpdi->Text(85, 225, $data->date_approved->format('d'));
        $nombre_mes = GeneralHelp::spanish_month($data->date_approved, 'l');
        $fpdi->Text(117, 225, $nombre_mes);
        $fpdi->SetFont("arial", "", 11);
        $fpdi->Text(156, 225, $data->date_approved->format('Y'));

        // Nombre del Comprador
        $fpdi->SetFont("arial", "B", 12);

        $fpdi->text(38,270, GeneralHelp::normalize_text($data->client->full_name));

        // Nombre del Vendedor

        $fpdi->text(125,270,  GeneralHelp::normalize_text($data->user->name));
        // Anticipo 80% del total
        if (! is_null($data->total)) {
            $fpdi->SetFont("arial", "B", 12);
            $fpdi->SetTextColor(0, 0, 0);
            $anticipo80 = round($data->total * 0.80, 2);
            // Posición cercana al texto “EL ANTICIPO DEL 80%...”
            $fpdi->Text(100, 191, '$ ' . number_format($anticipo80, 2, '.', ','));
            $fpdi->SetFont("arial", "", 11);
        }

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
        if (strlen($data->folio) > 6) {
            $fpdi->SetFont("arial", "B", 10);

        } else {
            $fpdi->SetFont("arial", "B", 14);
        }

        if ($data->folio) {
            $fpdi->Text(192, 15, $data->folio);
        } else {
            $fpdi->Text(192, 15, $data->id);
        }

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
        // Notas de la orden de compra
        if ($data->notes) {
            $fpdi->SetFont("arial", "", 10);
            $notes      = GeneralHelp::normalize_text($data->notes);
            $arrayNotes = explode("\n", $notes);
            $posx       = 27;
            $posy       = 110;
            foreach ($arrayNotes as $linea) {
                $palabras        = wordwrap($linea, 50, "\n", true);
                $lineasSeparadas = explode("\n", $palabras);
                foreach ($lineasSeparadas as $linea_separada) {
                    $fpdi->text($posx, $posy, $linea_separada);
                    $posy = $posy + 5;
                }
            }

        }

        // Fecha promesa de entrega
        if ($data->delivery_date) {
            $delivery_date = Carbon::parse($data->delivery_date);
            $fpdi->SetFont("arial", "B", 11);
            $fpdi->Text(45, 285, $delivery_date->format('d'));
            $nombre_mes = GeneralHelp::spanish_month($delivery_date, 'l');
            if (strlen($nombre_mes) > 7) {
                $fpdi->SetFont("arial", "B", 7);
            }
            $fpdi->Text(61, 285, $nombre_mes);
            $fpdi->SetFont("arial", "B", 10);
            $fpdi->Text(83.5, 285, $delivery_date->format('Y'));
        }

        // Totales

        $fpdi->Text(201 - strlen(number_format($data->total, 2, '.', ',')), 289, number_format($data->total, 2, '.', ','));
        $fpdi->Text(202 - strlen(number_format($data->advance, 2, '.', ',')), 294, number_format($data->advance, 2, '.', ','));
        $fpdi->Text(201 - strlen(number_format($data->pending_balance, 2, '.', ',')), 299, number_format($data->pending_balance, 2, '.', ','));

        // Fecha de Envío
        $fpdi->SetFont("arial", "B", 11);
        if ($data->delivery_date) {
            $delivery_date = Carbon::parse($data->delivery_date);
            $fpdi->Text(45, 303, $delivery_date->format('d'));
            $nombre_mes = GeneralHelp::spanish_month($delivery_date, 'l');
            if (strlen($nombre_mes) > 7) {
                $fpdi->SetFont("arial", "B", 7);
            }
            $fpdi->Text(61, 303, $nombre_mes);
            $fpdi->SetFont("arial", "B", 10);
            $fpdi->Text(83.5, 303, $delivery_date->format('Y'));
        }

    }

}
