<?php

namespace App\Livewire;

use TCPDF;
use Livewire\Component;

class EjemploPdf extends Component
{
    public function mount(){

        $this->ejemplo();
    }

    public function ejemplo(){
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
        //  $pdf->Output('historial_servicio.pdf', 'I');
    }


}
