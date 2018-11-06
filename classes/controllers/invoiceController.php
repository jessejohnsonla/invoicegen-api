<?php 
require 'db.php';
require 'utils.php';

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

    public function handle_non_get_request($request, $request_method) {
        $json = file_get_contents("php://input");
        $invoice = json_decode($json);

        if($request_method === 'post') {
            return $this->createInvoice($invoice);
        }
        else{
            return $this->updateInvoice($invoice);
        }
    }

    public function createInvoice($invoice) {
        $result = 0;
        $query = "CALL CreateInvoice( ".
                    "'" . get_property_safe($invoice, 'BillToName')  . "',".
                    "'" . get_property_safe($invoice, 'BillToAddress1') . "',".
                    "'" . get_property_safe($invoice, 'BillToAddress2') . "',".
                    "'" . get_property_safe($invoice, 'BillToCity') . "',".
                    "'" . get_property_safe($invoice, 'BillToState') . "',".
                    "'" . get_property_safe($invoice, 'BillToZipcode') . "',".
                    "'" . get_property_safe($invoice, 'ServiceDate') . "',".
                    "'" . get_property_safe($invoice, 'Terms') . "',".
                    "'" . get_property_safe($invoice, 'TaxRate') . "',".
                    "'" . get_property_safe($invoice, 'AmountPaid') . "',".
                    "'" . get_property_safe($invoice, 'DueDate') . "')";
        $result = query_close($query); 
        $row = $result->fetch_assoc();
        $id = $row['ID'];
        return $this->readInvoice($id);
    }
    
    public function updateInvoice($invoice) {
        $query = "CALL UpdateInvoice( ".
                    "$invoice->ID,".
                    "'" . get_property_safe($invoice, 'BillToName')  . "',".
                    "'" . get_property_safe($invoice, 'BillToAddress1') . "',".
                    "'" . get_property_safe($invoice, 'BillToAddress2') . "',".
                    "'" . get_property_safe($invoice, 'BillToCity') . "',".
                    "'" . get_property_safe($invoice, 'BillToState') . "',".
                    "'" . get_property_safe($invoice, 'BillToZipcode') . "',".
                    "'" . get_property_safe($invoice, 'ServiceDate') . "',".
                    "'" . get_property_safe($invoice, 'Terms') . "',".
                    "'" . get_property_safe($invoice, 'TaxRate') . "',".
                    "'" . get_property_safe($invoice, 'AmountPaid') . "',".
                    "'" . get_property_safe($invoice, 'DueDate') . "')";
        $result = query_close($query);
        return $result;
    }
    public function deleteInvoice($id) {
        $result = query_close( "CALL DeleteInvoice($id)"); 
        $deletecount = $result->fetch_row()[0];
        return $deletecount;
    }

    public function readInvoices()
    {
        $result = query_close( "CALL GetInvoices()"); 
        $rows = array();
        $invoices = [];
        while($row = $result->fetch_assoc()) {
            $invoices[] = $row;
        }
        return $invoices;
    }

    public function readInvoice($id)
    {
        $result = query_close( "CALL GetInvoice($id)"); 
        $invoice = $result->fetch_assoc();
        return $invoice;
    }
}
?>