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
    
    public function post($request) //create
    {
        switch (count($request->url_elements)) {
            case 2:
                $invoice_id = $request->url_elements[1];
                return $this->createInvoiceItem($invoice_id);
            break;
        }
        $json = file_get_contents("php://input");
        $item = json_decode($json);

        if($request_method === 'post') {
            return $this->createInvoiceItem($item);
        }
        else{
            return $this->updateInvoiceItem($item);
        }
    }
    
    protected function createInvoiceItem($item) {
        $result = 0;
        $db = $this->get_db_connection();
        $query = "CALL CreateInvoiceItem( ".
                    "'$item->InvoiceID',".
                    "'$item->Description',".
                    "'$item->Qty',".
                    "'$item->Rate',)";
        $result = $db->query($query);  
        
        return $result;
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