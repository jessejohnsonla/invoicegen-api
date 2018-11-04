<?php
require 'db.php';

class invoiceitemsController extends AbstractController
{
    public function get($request)
    {
        switch (count($request->url_elements)) {
            case 2:
                $invoiceid = $request->url_elements[1];
                return $this->readInvoiceItems($invoiceid);
            break;
            case 3:
                $invoiceid = $request->url_elements[1];
                $invoiceitemid = $request->url_elements[2];
                return $this->readInvoiceItem($invoiceid, $invoiceitemid);
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

    public function delete($request) //create
    {
        return $this->handle_non_get_request($request, 'delete');
    }
    

    protected function handle_non_get_request($request, $request_method) {
        $json = file_get_contents("php://input");
        $invoiceitem = json_decode($json);

        if($request_method === 'post') {
            switch (count($request->url_elements)) {
                case 2:
                    return $this->createInvoiceItem($invoiceitem);
                break;
            }
        }
        elseif($request_method === 'delete') {
            switch (count($request->url_elements)) {
                case 2:
                    $invoiceitemid = $request->url_elements[1];
                    return $this->deleteInvoiceItem($invoiceitemid);
                break;
            }
        }
        else{
            switch (count($request->url_elements)) {
                case 2:
                    return $this->updateInvoiceItem($invoiceitem);
                break;
            }
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
    protected function updateInvoiceItem($item) {
        $query = "CALL UpdateInvoiceItem( ".
                    "$item->ID,".
                    "'$item->InvoiceID',".
                    "'$item->Description',".
                    "'$item->Qty',".
                    "'$item->Rate',".
                    "'$item->Date')";
        $result = query_close($query);
        return $result;
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

    protected function readInvoiceItem($invoiceid, $invoiceitemid)
    {
        $res = query_close( "CALL GetInvoiceItemByID($invoiceid, $invoiceitemid)"); 
        $rows = array();
        $invoiceitem = $res->fetch_assoc();
        return $invoiceitem;
    }

    protected function deleteInvoiceItem($invoiceitemid)
    {
        $res = query_close( "CALL DeleteInvoiceItem($invoiceitemid)"); 
        $deletecount = $res->fetch_row()[0];
        return $deletecount;
    }
}