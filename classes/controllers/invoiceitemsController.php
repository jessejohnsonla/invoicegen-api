<?php
require 'db.php';

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
    
    public function post($request) //create
    {
        switch (count($request->url_elements)) {
            case 2:
                $invoice_id = $request->url_elements[1];
                $json = file_get_contents("php://input");
                $item = json_decode($json);
                return $this->createInvoiceItem($item);
            break;
        }
    }
    
    protected function createInvoiceItem($item) {
        $result = 0;
        $query = "CALL CreateInvoiceItem( ".
                    "'$item->InvoiceID',".
                    "'$item->Description',".
                    "'$item->Qty',".
                    "'$item->Rate',".
                    "'$item->Date')";
        $result = query_close($query);
        
        return ($result) ? $result->fetch_assoc() : "false";
    }

    protected function readInvoiceItems($invoiceid)
    {
        $res = query_close( "CALL GetInvoiceItemsByInvoiceID($invoiceid)"); 
        $rows = array();
        while($row = $res->fetch_assoc()) {
            $invoiceitems[] = $row;
        }
        return $invoiceitems;
    }
}