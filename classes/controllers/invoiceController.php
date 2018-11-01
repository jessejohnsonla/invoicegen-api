<?php 

class invoiceController extends AbstractController
{
    protected function get_db_connection() {
        return new mysqli("localhost:3306", "root", "!mysql!", "invoicegen"); 
    }
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
        return $this->handle_non_get_request($request, 'post');
    }
    
    public function put($request) //update
    {
        return $this->handle_non_get_request($request, 'put');
    }

    public function options($request) { //update due to cors
        return $this->handle_non_get_request($request, 'options');
    }


    public function delete($request) {
        switch (count($request->url_elements)) {
            case 2:
                $invoice_id = $request->url_elements[1];
                return $this->deleteInvoice($invoice_id);
            break;
        }
    }

    protected function handle_non_get_request($request, $request_method) {
        $json = file_get_contents("php://input");
        $invoice = json_decode($json);

        if($request_method === 'post') {
            return $this->createInvoice($invoice);
        }
        else{
            return $this->updateInvoice($invoice);
        }
    }
    
    protected function createInvoice($invoice) {
        $result = 0;
        $db = $this->get_db_connection();
        $query = "CALL CreateInvoice( ".
                    "'$invoice->BillToName',".
                    "'$invoice->BillToAddress1',".
                    "'$invoice->BillToAddress2',".
                    "'$invoice->BillToCity',".
                    "'$invoice->BillToState',".
                    "'$invoice->BillToZipcode',".
                    "'$invoice->ServiceDate',".
                    "'$invoice->Terms',".
                    "$invoice->TaxRate,".
                    "$invoice->AmountPaid,".
                    "'$invoice->DueDate',)";
        $result = $db->query($query);  
        
        return $this->readInvoice($result);
    }
    
    protected function updateInvoice($invoice) {
        $result = 0;
        $db = $this->get_db_connection();
        $query = "CALL UpdateInvoice( ".
                    "$invoice->ID,".
                    "'$invoice->BillToName',".
                    "'$invoice->BillToAddress1',".
                    "'$invoice->BillToAddress2',".
                    "'$invoice->BillToCity',".
                    "'$invoice->BillToState',".
                    "'$invoice->BillToZipcode',".
                    "'$invoice->ServiceDate',".
                    "'$invoice->Terms',".
                    "$invoice->TaxRate,".
                    "$invoice->AmountPaid,".
                    "'$invoice->DueDate')";
        $result = $db->query($query); 
        return $result;
    }
    protected function deleteInvoice($invoice) {
        $db = $this->get_db_connection();
        $result = $db->query( "CALL DeleteInvoice($id)"); 
        //$res->exec();
        return $result;
    }

    protected function readInvoices()
    {
        $db = $this->get_db_connection(); 
        $result = $db->query( "CALL GetInvoices()"); 
        $rows = array();
        while($row = $result->fetch_assoc()) {
            $invoices[] = $row;
        }
        return $invoices;
    }

    protected function readInvoice($id)
    {
        $db = $this->get_db_connection();
        $result = $db->query( "CALL GetInvoice($id)"); 
        $invoices = array();
        while($row = $result->fetch_assoc()) {
            $invoices[] = $row;
        }
        $invoice = (count($invoices) > 0) ? $invoices[0] : null;
        return $invoice;
    }
}
?>