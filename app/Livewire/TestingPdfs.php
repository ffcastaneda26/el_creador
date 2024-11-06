<?php

namespace App\Livewire;

use TCPDF;
use App\Models\Client;
use Livewire\Component;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Str;
use App\Helpers\GeneralHelp;

class TestingPdfs extends Component
{
    public function mount(){
        $this->aviso_privacidad();
        // $this->ejemplo();
    }

    public function ejemplo(){
        // $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // $pdf->SetMargins(0, 0, 0, true);
        // $pdf->SetAutoPageBreak(false, 0);
        // $pdf->AddPage();
        // $pageWidth = $pdf->getPageWidth();
        // $pageHeight = $pdf->getPageHeight();
        // $imagen = imagecreatefrompng(public_path('pdfs/Aviso de Privacidad.png'));

        // $template = $fpdi->importPage($i);
        // dd('Ya terminé');


        $imagen = imagecreatefrompng(public_path('pdfs/Aviso de Privacidad.png'));
        $colorTexto = imagecolorallocate($imagen, 0, 0, 0);
        $fuente = public_path('fonts/arial.ttf');
        $fuenteNegrita = public_path('fonts/Arial-Bold.ttf');
        $fechaObj = new \DateTime();
        $meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
        $diaActual = $fechaObj->format('d');
        $mesActual = $meses[$fechaObj->format('m') - 1];
        $añoActual = $fechaObj->format('Y');
        imagettftext($imagen, 18, 0, 134, 22, $colorTexto, $fuente, $diaActual . ' de ' . $mesActual . ' del ' . $añoActual);

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetAutoPageBreak(false, 0);
        $pageWidth = $pdf->getPageWidth();
        $pageHeight = $pdf->getPageHeight();
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $filePath = public_path("aviso de privacidad.pdf");
        $outputFilePath = public_path("sample_output.pdf");
        $data = Client::first();


        $pdf->AddPage();
        $pdf->Write(0, 'Hello World');
        $pdf->Output('historial_servicio.pdf', 'I');
        $tempImagePath2 = tempnam(sys_get_temp_dir(), 'pdf') . '.jpg';
        imagejpeg($imagen, $tempImagePath2);
        $pdf->Image($tempImagePath2, 0, 0, $pageWidth, $pageHeight, '', '', '', false, 300, '', false, false, 0, false, false, false);
        unlink($tempImagePath2);
        for ($i = 0; $i < 5; $i++) {
            $pdf->SetTitle('Hello World'.$i);
            $pdf->AddPage();
            $pdf->Write(0, 'Hello World'.$i);
            $pdf->Output(public_path('hello_world' . $i . '.pdf'), 'F');
          }
        // $pdf->Output('historial_servicio.pdf', 'I');

    }
    public function render()
    {
        return view('livewire.testing-pdfs');
    }

    public function aviso_privacidad()
    {
        // $number =4444444.44;
        // $texto = GeneralHelp:: number_to_letters($number);
        // dd('Número=' . number_format($number,2,'.',',') . '-->' . $texto);

        $filePath = public_path("aviso de privacidad.pdf");
        $outputFilePath = public_path("sample_output.pdf");
        $data = Client::first();
        $this->fillPDFFile($filePath, $outputFilePath,$data);
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
