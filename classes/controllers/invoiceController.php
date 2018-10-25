<?php 

class invoiceController extends AbstractController
{
    public function get($request)
    {
        switch (count($request->url_elements)) {
            case 1:
                return $this->readInvoices();
            break;
            case 2:
                $invoice_id = $request->url_elements[1];
                return $this->readInvoice($invoice_id);
            break;
            // case 4:
            //     $invoice_id = $request->url_elements[1];
            //     $contenttype = $request->url_elements[2]; //pdf
            //     $emails = $request->url_elements[3];
            //     return $this->generateInvoice($invoice_id, $contenttype, $emails);
            // break;
        }
    }
    
    public function post($request) //create
    {
        switch (count($request->url_elements)) {
            case 1:
                return null;
            break;
        }
    }
    
    public function put($request) //update
    {
        switch (count($request->url_elements)) {
            case 1:
            break;
        }
    }
    public function delete($request) {

    }
    
    

    protected function readInvoices()
    {
        $db = new mysqli(  "localhost:3306", "root", "!mysql!", "invoicegen" ); 

        $res = $db->query( "CALL GetInvoices()"); 
        $rows = array();
        while($row = $res->fetch_assoc()) {
            $invoices[] = $row;
        }
        return $invoices;
    }

    protected function readInvoice($id)
    {
        $db = new mysqli(  "localhost:3306", "root", "!mysql!", "invoicegen" ); 

        $res = $db->query( "CALL GetInvoice($id)"); 
        $invoices = array();
        while($row = $res->fetch_assoc()) {
            $invoices[] = $row;
        }
        $invoice = (count($invoices) > 0) ? $invoices[0] : null;
        return $invoice;
    }

    protected function writeInvoices($invoices)
    {
        //file_put_contents($this->articles_file, serialize($articles));
        return true;
    }
}