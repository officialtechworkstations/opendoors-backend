<?php
require 'include/reconfig.php';
$kb = $rstate->query("SELECT * FROM `tbl_payment_list` where id=10")->fetch_assoc();
$kk = explode(',',$kb['attributes']);
/**
 * This is a sample code for manual integration with senangPay
 * It is so simple that you can do it in a single file
 * Make sure that in senangPay Dashboard you have key in the return URL referring to this file for example http://myserver.com/senangpay_sample.php
 */

# please fill in the required info as below

// live creditinals
$merchant_id = $kk[0];
$secretkey = $kk[1];




# this part is to process data from the form that user key in, make sure that all of the info is passed so that we can process the payment
if(isset($_GET['detail']) && isset($_GET['amount']) && isset($_GET['order_id']) && isset($_GET['name']) && isset($_GET['email']) && isset($_GET['phone']))
{
    # assuming all of the data passed is correct and no validation required. Preferably you will need to validate the data passed
    $hashed_string = hash_hmac('sha256', $secretkey.urldecode($_GET['detail']).urldecode($_GET['amount']).urldecode($_GET['order_id']), $secretkey);
    
    # now we send the data to senangPay by using post method
	# https://app.senangpay.my/payment/ --> live url 
	# https://sandbox.senangpay.my/payment/ --> test url
    ?>
    <html>
    <head>
    <title>senangPay Sample Code</title>
    </head>
    <body onload="document.order.submit()">
	
	<?php 
	if($kk[2] == 'TEST')
	{
	?>
        <form name="order" method="post" action="https://sandbox.senangpay.my/payment/<?php echo $merchant_id; ?>">
	<?php } else { ?>
	<form name="order" method="post" action="https://app.senangpay.my/payment/<?php echo $merchant_id; ?>">
	<?php } ?>
            <input type="hidden" name="detail" value="<?php echo $_GET['detail']; ?>">
            <input type="hidden" name="amount" value="<?php echo $_GET['amount']; ?>">
            <input type="hidden" name="order_id" value="<?php echo $_GET['order_id']; ?>">
            <input type="hidden" name="name" value="<?php echo $_GET['name']; ?>">
            <input type="hidden" name="email" value="<?php echo $_GET['email']; ?>">
            <input type="hidden" name="phone" value="<?php echo $_GET['phone']; ?>">
            <input type="hidden" name="hash" value="<?php echo $hashed_string; ?>">
        </form>
    </body>
    </html>
    <?php
}
# this part is to process the response received from senangPay, make sure we receive all required info
else if(isset($_GET['status_id']) && isset($_GET['order_id']) && isset($_GET['msg']) && isset($_GET['transaction_id']) && isset($_GET['hash']))
{
    # verify that the data was not tempered, verify the hash
    $hashed_string = hash_hmac('sha256', $secretkey.urldecode($_GET['status_id']).urldecode($_GET['order_id']).urldecode($_GET['transaction_id']).urldecode($_GET['msg']), $secretkey);
    
    # if hash is the same then we know the data is valid
    if($hashed_string == urldecode($_GET['hash']))
    {
        # this is a simple result page showing either the payment was successful or failed. In real life you will need to process the order made by the customer
        if(urldecode($_GET['status_id']) == '1')
		{
            
		$returnArr = array("Transaction_id"=>$_GET['transaction_id'],"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>urldecode($_GET['msg']));
		echo json_encode($returnArr);
		}
        else
		{
           $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>urldecode($_GET['msg']));
		echo json_encode($returnArr);
		}
    }
    else
        echo 'Hashed value is not correct';
}
# this part is to show the form where customer can key in their information
else
{
    # by right the detail, amount and order ID must be populated by the system, in this example you can key in the value yourself
?>
    
<?php
}
?>