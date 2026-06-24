<?php 
require dirname( dirname(__FILE__) ).'/include/reconfig.php';
$data = json_decode(file_get_contents('php://input'), true);
header('Content-type: text/json');
$kb = $rstate->query("SELECT * FROM `tbl_payment_list` where id=6")->fetch_assoc();
$kk = explode(',',$kb['attributes']);

if($data['email'] == '' or $data['amount'] == '')
{
    $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else
{
 

$email = $data['email'];
$amount = $data['amount'] * 100;  //the amount in kobo. This value is actually NGN 300

// Base host for the redirect URLs. Both the success callback and the cancel
// page must live on THIS backend's domain so the app's webview can detect them.
$base_url = 'https://admin.opendoorsapp.com';

// url to go to after a successful/completed payment
$callback_url = $base_url . '/paystack/callback.php';

// url Paystack redirects to when the user taps "Cancel Payment" on checkout.
// The app's webview watches for this URL to know the transaction was cancelled.
$cancel_url = $base_url . '/paystack/cancel.php';

$url = "https://api.paystack.co/transaction/initialize";
  $fields = [
    'email' => $email,
    'amount' => $amount,
	'callback_url'=>$callback_url,
	'metadata'=> json_encode(['cancel_action' => $cancel_url])
  ];
  $fields_string = http_build_query($fields);
  //open connection
  $ch = curl_init();
  
  //set the url, number of POST vars, POST data
  curl_setopt($ch,CURLOPT_URL, $url);
  curl_setopt($ch,CURLOPT_POST, true);
  curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer $kk[1]",
    "Cache-Control: no-cache",
  ));
  
  //So that curl_exec returns the contents of the cURL; rather than echoing it
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
  
  //execute post
  $result = curl_exec($ch);
}
echo  $result;
exit;