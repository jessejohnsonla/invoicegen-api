<?php
require 'db.php';
require 'utils.php';

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
        $query = "CALL GetInvoice($id)";
        $result = query_close($query); 
        return $result->fetch_assoc();
    }

    protected function readInvoiceItems($invoiceid)
    {
        $query = "CALL GetInvoiceItemsByInvoiceID($invoiceid)";
        
        $result = query_close($query); 
        $invoice_items = array();
        while($row = $result->fetch_assoc()) {
            $invoice_items[] = array_map('utf8_encode', $row);
        }
        return $invoice_items;
    }
}