<?php

namespace App\Http\Controllers;

use App\Helpers\GeneralHelp;
use App\Models\Client;
use App\Models\Cotization;
use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;

class PdfController extends Controller
{

    public function index($record,string $document="aviso")
    {
        switch ($document) {
            case 'aviso':
                $this->aviso_pricacidad($record);
                break;
            case 'cotizacion':
                $this->cotizacion($record);
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


        for ($i=1; $i<=$count; $i++) {
            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
            $fpdi->useTemplate($template);
            $fpdi->SetXY(134,22);

            $fpdi->Write(0,now()->format('d'));
            $fpdi->Text(152,23,GeneralHelp::spanish_month(now(),'s'));

            if($data){
                $standard_name = GeneralHelp::normalize_text($data->name);
                $standard_name = ucwords($standard_name);

                $fpdi->Text(42,133,$standard_name);    // Nombre
                $fpdi->Text(44,145,$data->phone);   // Teléfono
                $fpdi->Text(61,157,strtolower($data->email)); // Correo
                $address = $data->address . ' Col: ' . $data->colony . ' en ' . $data->city->name; // Calle y número
                // $address = strtr($address, array_combine($buscar, $reemplazar));
                $address = GeneralHelp::normalize_text($address);
                $fpdi->Text(44,170,$address);
                $municipality_state = $data->municipality->name . ',' . $data->state->abbreviated . '   C.P. ' .$data->zipcode;
                $fpdi->Text(44,175,$municipality_state);
                $fpdi->Text(90,183,$data->rfc); // RFC
                $fpdi->Text(64,195,$data->ine); // INE
                $fpdi->Text(40,265,$standard_name); // Nombre para firmar
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
        $data = Cotization::findOrFail($record);
        $filePath = public_path('pdfs/cotizacion formal.pdf');
        $outputFilePath = public_path("output.pdf");
        $fpdi = new FPDI;
        $fpdi->SetFont("arial");
        $fpdi->SetFontSize(8);
        $fpdi->SetTextColor(0, 0, 0);
        $count = $fpdi->setSourceFile($filePath);


        for ($i=1; $i<=$count; $i++) {
            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
            $fpdi->useTemplate($template);

            if($data && $i==1){
                $fpdi->SetXY(164,27);
                $fpdi->Write(0,$data->fecha->format('d'));
                $fpdi->Text(176,28,strtoupper(GeneralHelp::spanish_month($data->fecha,'s')));
                $standard_name = GeneralHelp::normalize_text($data->client->name);
                $standard_name = ucwords($standard_name);
                $fpdi->Text(43,64,$standard_name);                          // Nombre

                $standar_description = GeneralHelp::normalize_text($data->description);
                $arrayDescripcion = explode("\n", $standar_description);
                $posx= 52;
                $posy= 85;

                // Descripción
                foreach ($arrayDescripcion as $linea) {
                    $palabras= wordwrap($linea, 70, "\n", true);
                    $lineasSeparadas = explode("\n", $palabras);
                    foreach($lineasSeparadas as $linea_separada){
                        $fpdi->text($posx,$posy,$linea_separada);
                        $posy=$posy+5;
                    }
                }
                $fpdi->SetFontSize(14);
                if($data->fecha_entrega){
                    $fpdi->text(100,$posy +10 ,'Fecha de Entrega: ' . GeneralHelp::spanish_date($data->fecha_entrega,'n','n','dmy') );
                }

                // Totales
                $fpdi->SetFontSize(11);
                $fpdi->text(194-strlen(number_format($data->subtotal,2,'.',',')),200,number_format($data->subtotal,2,'.',','));
                if($data->iva> 0){
                    $fpdi->text(195-strlen(number_format($data->iva,2)),205,number_format($data->iva,2));
                }else{
                    $fpdi->Text(105,212,'NO INCLUYE IVA');
                }

                if($data->descuento > 0){
                    $fpdi->text(192-strlen(number_format($data->descuento)),212,number_format($data->descuento,2));
                }
                $fpdi->text(191-strlen(number_format($data->total)),218,number_format($data->total,2));
            }
        }
        return $fpdi->Output($outputFilePath, 'I');
    }

}
