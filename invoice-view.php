<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function get_json_result($url){
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_FAILONERROR => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => false,
    ));
    return curl_exec($ch);
}

$curl = "http://localhost/invoiceview/" . $_GET['id'];
$result = get_json_result($curl);
$json = json_decode($result);
$invoice = $json[0];
$invoice_items = $json[1];

?>
<!DOCTYPE html>
<html>
    <head>
        <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>
<div id="invoicetemplate" 
      style="width: 8.5in; height: 11in; padding:0px; margin:0px;">
    <div style="height:50pt;">&nbsp;</div>
      <table class="table borderless">
        <tr>
            <td style="width:.5in;" rowspan="2"></td>
            <td style="text-align: right; font-size: 14pt;">Invoice</td>
            <td style="width:.5in;" rowspan="2"></td></tr>
        <tr><td><span  style="background-color: lightgrey; padding: 10px; font-size: 18pt;">J Monkey Coonsulting INC</span></td></tr>
        <tr><td colspan="3"><div style="height:2pt;width:100%;background-color:dimgrey;"></div></td></tr>
      </table>
      
      <table class="table borderless">
        <tr>
          <td style="width:.5in;" rowspan="4"></td>
          <td>Bill To:</td>
          <td><?= $invoice->BillToName; ?></td>
          <td style="text-align: right;">Invoice No:</td>
          <td><?= $invoice->ID ?></td>
          <td style="width:.5in;" rowspan="4"></td>
        </tr>
        <tr>
          <td colspan="3" style="width: 75%; text-align: right;">Date:</td>
          <td><?= $invoice->ServiceDate ?></td>
        </tr>
        <tr>
          <td colspan="3" style="text-align: right;">Terms:</td>
          <td><?= $invoice->Terms ?></td>
        </tr>
        <tr>
          <td colspan="3" style="text-align: right;">Due Date:</td>
          <td><?= $invoice->DueDate ?></td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
      </table>
      
      <table class="table borderless" style="">
          <tr> 
              <td style="width:.5in;" rowspan="1000"></td>
            <td style="width:50%; background-color: lightblue;">Description</td>
            <td style="background-color: lightblue; text-align: right;">Quantity</td>
            <td style="background-color: lightblue; text-align: right;">Rate</td>
            <td style="background-color: lightblue; text-align: right;">Amount</td>
            <td style="width:.5in;" rowspan="1000"></td>
          </tr> 

    <?php foreach($invoice_items as $key=>$value): ?>
          <tr>
            <td><?= $value->Description; ?></td>
            <td style="text-align: right;"><?=$value->Qty?></td>
            <td style="text-align: right;"><?=$value->Rate?></td>
            <td style="text-align: right;"><?=$value->Amount?></td>
          </tr>
    <?php endforeach; ?>          
          <tr><td colspan="4">&nbsp;</td></tr>
          <tr>
            <td colspan="3" style="text-align: right;">Subtotal</td>
            <td style="text-align: right;"><?= $invoice->Subtotal ?></td>
          </tr>
          <tr>
            <td colspan="3" style="text-align: right;">TAX%</td>
            <td style="text-align: right;"><?= $invoice->TaxRate ?></td>
          </tr>
          <tr>
            <td colspan="3" style="text-align: right;">Total</td>
            <td style="text-align: right;"><?= $invoice->Total ?></td>
          </tr>
          <tr>
            <td colspan="3" style="text-align: right;">PAID</td>
            <td style="text-align: right;"><?= $invoice->AmountPaid ?></td>
          </tr>
          <tr>
              <td>&nbsp;</td>
              <td colspan="2" style="background-color: black; color: white; text-align: right;">Balance Due</td>
              <td style="background-color: black; color: white; text-align: right;"><?= $invoice->Balance ?></td>
          </tr>

        </table>
</div>
</body>
</html>