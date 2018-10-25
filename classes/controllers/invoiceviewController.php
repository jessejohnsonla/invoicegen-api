<?php

class invoiceviewController extends AbstractController
{
    public function get($request)
    {
        switch (count($request->url_elements)) {
            case 2:
                $invoice_id = $request->url_elements[1];
                $invoice = $this->readInvoice($invoice_id);
                $invoice_items = $this->readInvoiceItems($invoice_id);
                return [$invoice, $invoice_items];
            break;
        }
    }
    
    
    protected function readInvoice($id)
    {
        $db = new mysqli(  "localhost:3306", "root", "!mysql!", "invoicegen" ); 
        $query = "CALL GetInvoice($id)";
        $res = $db->query($query); 
        return $res->fetch_assoc();
    }

    protected function readInvoiceItems($invoiceid)
    {
        $db = new mysqli(  "localhost:3306", "root", "!mysql!", "invoicegen" ); 
        $query = "CALL GetInvoiceItemsByInvoiceID($invoiceid)";
        
        $res = $db->query($query); 
        $invoice_items = array();
        while($row = $res->fetch_assoc()) {
            $invoice_items[] = array_map('utf8_encode', $row);
        }
        return $invoice_items;
    }
}