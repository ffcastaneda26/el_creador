<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Helpers\GeneralHelp;
use TCPDF;
use Livewire\Component;
use setasign\Fpdi\Fpdi;

class BKPrivateNotice extends Controller
{
    public function download(Client $record)
    {
        $filePath = public_path("aviso de privacidad.pdf");
        $outputFilePath = public_path("sample_output.pdf");
        $this->fillPDFFile($filePath, $outputFilePath,$record);
        return response()->file($outputFilePath);
    }

    public function fillPDFFile($file, $outputFilePath,$data)
    {

        $fpdi = new FPDI;
        $fpdi->SetFont("arial");
        $fpdi->SetFontSize(11);
        $fpdi->SetTextColor(0, 0, 185);
        $count = $fpdi->setSourceFile($file);


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
}
