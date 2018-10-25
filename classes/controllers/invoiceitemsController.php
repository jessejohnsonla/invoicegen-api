<?php

class invoiceitemsController extends AbstractController
{
    public function get($request)
    {
        switch (count($request->url_elements)) {
            case 2:
                $invoice_id = $request->url_elements[1];
                return $this->readInvoiceItems($invoice_id);
            break;
        }
    }
    
    

    protected function readInvoiceItems($invoiceid)
    {
        $db = new mysqli(  "localhost:3306", "root", "!mysql!", "invoicegen" ); 

        $res = $db->query( "CALL GetInvoiceItemsByInvoiceID($invoiceid)"); 
        $rows = array();
        while($row = $res->fetch_assoc()) {
            $invoiceitems[] = $row;
        }
        return $invoiceitems;
    }
}