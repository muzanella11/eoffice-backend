<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Mpdf\Mpdf;

class PdfManagement {
    function __construct()
    {
        $this->Mpdf = new \Mpdf\Mpdf();
    }

    public function run ($config = [])
    {
        // var_dump($config);exit;
        // $this->Mpdf->WriteHTML($config['html'], 0);
        // $this->Mpdf->AddPage();
        // $this->Mpdf->WriteHTML($config['html'], 0);
        if (isset($config['setFooterPageNumber']))
        {
            // Set a simple Footer including the page number
            $this->Mpdf->setFooter('Halaman {PAGENO}');
        }

        $this->writeHtml($config);       
        
        if (isset($config['title']))
        {
            // var_dump('a');exit;
            $this->setOutputFile($config);
        }
        else
        {
            $this->Mpdf->Output();
        }
    }

    public function setOutputFile ($config)
    {
        $location = 'uploads/pdf/'.$config['title'].'.pdf';
        // $this->Mpdf->Output();
        $this->Mpdf->Output($location, \Mpdf\Output\Destination::FILE);

        // header('Content-type: application/pdf');
        // header('Content-Disposition: inline; filename="' . $location . '"');
        // header('Content-Transfer-Encoding: binary');
        // header('Accept-Ranges: bytes');
        // ob_clean();
        // flush();
        // if (readfile($location))
        // {
        //     unlink($location);
        // }
    }

    public function writeHtml ($config) {
        if (!isset($config['withBreak']))
        {
            $config['withBreak'] = '';
        }

        if ($config['withBreak'] === false && $config['html'])
        {
            foreach ($config['html'] as $key => $value) {
                $this->Mpdf->WriteHTML($value, 0);
            }
        } 
        else if ($config['withBreak'] === true && $config['html']) 
        {
            $arraySize = count($config['html']);
            $lastArray = $arraySize - 1;

            foreach ($config['html'] as $key => $value) {
                $this->Mpdf->WriteHTML($value, 0);
                if ($key === $lastArray)
                {
                    return true;
                }
                else
                {
                    $this->Mpdf->AddPage();
                }
            }
        } 
        else 
        {
            foreach ($config['html'] as $key => $value) {
                $this->Mpdf->WriteHTML($value, 0);
            }
        }
    }
}