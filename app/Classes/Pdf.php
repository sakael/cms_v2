<?php

namespace App\Classes;

use DB;
use Carbon\Carbon;
use App\Classes\Order;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Slim\Exception\NotFoundException;

class Pdf
{
    public $mpdf;

    public function __construct()
    {
        $this->mpdf= new Mpdf([
            'tempDir' => ROOT_DIR . '/pdf',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 48,
            'margin_bottom' => 25,
            'margin_header' => 10,
            'margin_footer' => 3
            ]);
            
        }
    public function generateOrderPdfString($orderId)
    {
        $file=$this->mpdf->Output('factuur_'.$orderId.'.pdf',Destination::STRING_RETURN);
        return chunk_split(base64_encode($file));
    }
    public function pdfGeneratorOrderLink($orderId)
    {
        return $this->mpdf->Output('factuur_'.$orderId.'.pdf',Destination::INLINE);
    }
}