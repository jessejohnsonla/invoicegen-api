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
        }
    }
    
    public function post($request) //create
    {
        $d = file_get_contents("php://input");
        $json = json_decode($d);
        updateInvoice($json);
    }
    
    public function put($request) //update
    {
        $d = file_get_contents("php://input");
        $json = json_decode($d);
        updateInvoice($json);
    }

    public function delete($request) {

    }
    
    public function createInvoice($invoice) {
        $db = new mysqli(  "localhost:3306", "root", "!mysql!", "invoicegen" ); 
        $res = $db->query( "CALL CreateInvoice($id)"); 
        $res->exec();
        return readInvoice($invoice.ID);
    }
    protected function updateInvoice($invoice) {

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
}