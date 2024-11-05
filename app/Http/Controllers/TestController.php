<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Elibyy\TCPDF\Facades\TCPDF;
use setasign\Fpdi\Fpdi;

class TestController extends Controller
{

    public function index(){
        $filename = 'demo.pdf';
        $data = [
            'title' => 'Generate PDF using Laravel TCPDF - ItSolutionStuff.com!'
        ];

        $html = view()->make('pdfSample', $data)->render();

        $pdf = new TCPDF();

        $pdf::SetTitle('Hello World');
        $pdf::AddPage();
        $pdf::writeHTML($html, true, false, true, false, '');

        $pdf::Output(public_path($filename), 'I');

        return response()->download(public_path($filename));

    }
}
