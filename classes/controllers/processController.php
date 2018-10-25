<?php 
    
    class processController extends AbstractController
    {
        public function get($request)
        {
            switch (count($request->url_elements)) {
                case 2:
                    $invoice_id = $request->url_elements[1];
                    return $this->generateInvoice($invoice_id);
                break;
            }
        }
        protected function generateInvoice($id)
        {
            $baseurl = 'http://localhost/';
            $file = "out/pdf/invoice_from_jlion_consulting_" . $id . ".pdf";
            $pdfurl = $baseurl . $file;
            exec("/usr/local/bin/wkhtmltopdf " . $baseurl . "invoice-view.php?id=$id $file");
            return $pdfurl; 
        }
    }
?>