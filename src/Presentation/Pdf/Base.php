<?php


namespace App\Presentation\Pdf;

use Dompdf\Dompdf;
use Dompdf\Options;


use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;

use App\Presentation\Pdf\Configuracion;


class Base {

    private static $instance = null;

    public function __construct(){
        $this->pdf = new Dompdf();
        $this->configuracion = new Configuracion();
    }


    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Base();
        }
        return self::$instance;
    }

    public function generateWord__comite($content) {

        $phpWord = new PhpWord();

        $section = $phpWord->addSection();

        $html=$content;


        $phpWord->addFontStyle('customFontStyle', [
            'name' => 'Arial',
            'size' => 8
        ]);

        Html::addHtml($section, $html, false, false);

        $fileName = 'documento_ejemplo.docx';

        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($fileName);

        $fileContent = file_get_contents($fileName);
        $base64Content = base64_encode($fileContent);

        return "data:application/vnd.openxmlformats-officedocument.wordprocessingml.document;base64," . $base64Content;

    }

    public function generatePdf__comite($content) {

        $html = self::headPdf();

        $html.='<div class="mt-6">';

        $html .= $content;

        $html.='</div>';


        $html .= self::footerPdf();

        $this->pdf->loadHtml($html);

        $this->pdf->setPaper('A4', 'portrait');

        $this->pdf->render();

        $canvas = $this->pdf->get_canvas(); 
        $canvas->page_text(500, 820, "Página {PAGE_NUM} de {PAGE_COUNT}","helvetica", 8, array(0,0,0)); 
   
        $output = $this->pdf->output();


        return base64_encode($output);
        
    }

    public function generatePdf__sn($content,$codigo) {

        $html = self::headPdf();

        $html.='<div class="mt-6">';

        $html .= $content;

        $html.='</div>';


        $html .= self::footerPdf();

        $this->pdf->loadHtml($html);

        $this->pdf->setPaper('A4', 'portrait');

        $this->pdf->render();

        $canvas = $this->pdf->get_canvas(); 
        $canvas->page_text(500, 820, "Página {PAGE_NUM} de {PAGE_COUNT}","helvetica", 8, array(0,0,0)); 
        $canvas->page_text(45, 820,$codigo, "helvetica", 8, array(0,0,0)); 

   
        $output = $this->pdf->output();


        return base64_encode($output);
        
    }


    public function generatePdf($content,$filePath,$codigo) {

        $html = self::headPdf();

        $html.='<div class="mt-6">';

        $html .= $content;

        $html.='</div>';


        $html .= self::footerPdf();

        $this->pdf->loadHtml($html);

        $this->pdf->setPaper('A4', 'portrait');

        $this->pdf->render();

        $canvas = $this->pdf->get_canvas(); 
        $canvas->page_text(500, 820, "Página {PAGE_NUM} de {PAGE_COUNT}","helvetica", 8, array(0,0,0)); 
        $canvas->page_text(45, 820,$codigo, "helvetica", 8, array(0,0,0)); 

   
        $output = $this->pdf->output();

        file_put_contents($filePath, $output);



        return base64_encode($output);
    }


    public function headPdf() {

        return $htm= '

        <!DOCTYPE html>

        <html lang="es">

            <head>

                <meta charset="utf-8" />
                <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                <title>Ministerio del deporte</title>
                <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" /> 

                '.$this->configuracion->Estilos().'
  
            </head>

            <body class="text-11">

                <div class="header" id="html_my_header"></div>

            ';

    }

    public function footerPdf() {

        return $thm='

            </body>

        </html>

        ';

    }



}