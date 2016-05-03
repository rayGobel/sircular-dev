<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Dompdf\Dompdf;

class PrintController extends Controller {

    public function getIndex() 
    {
        return ('welcome');
    }

    public function printTest()
    {
        $dompdf = new Dompdf;
        $dompdf->loadHtml('Hello world');

        $dompdf->setPaper('A4', 'Landscape');

        $dompdf->render();

        $dompdf->stream();
    }


	

}
