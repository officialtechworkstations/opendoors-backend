<?php
require "reconfig.php";
require "estate.php";

if (isset($_POST["type"])) {
    
			if($_POST['type'] == 'login')
	{
		$username = $_POST['username'];
		$password = $_POST['password'];
		$stype = $_POST["stype"];
		 
		 if($stype == 'Admin')
		{
		$h = new Estate();
	
	 $count = $h->restatelogin($username,$password,'admin');
 if($count != 0)
 {
	 $_SESSION['restatename'] = $username;
	 $_SESSION["stype"] = $stype;
	 $returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Login Successfully!","message"=>"welcome admin!!","action"=>"dashboard.php");
	 
	}
	else 
	{
		$returnArr = array("ResponseCode"=>"200","Result"=>"false","title"=>"Please Use Valid Data!!","message"=>"welcome admin!!","action"=>"index.php");
	}
		}
		else 
		{
		$h = new Estate();
	
	 $count = $h->restatelogin($username,$password,'tbl_staff');
 if($count != 0)
 {
	 $_SESSION['restatename'] = $username;
	 $_SESSION["stype"] = $stype;
	 $returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Login Successfully!","message"=>"welcome admin!!","action"=>"dashboard.php");
	 
	}
	else 
	{
		$returnArr = array("ResponseCode"=>"200","Result"=>"false","title"=>"Please Use Valid Data!!","message"=>"welcome admin!!","action"=>"index.php");
	}	
		}
	 
	
}
else if ($_POST["type"] == "add_code") {
        $okey = $_POST["status"];
        $title = $rstate->real_escape_string($_POST["title"]);

        $table = "tbl_code";
        $field_values = ["ccode", "status"];
        $data_values = ["$title", "$okey"];

        $h = new Estate();
        $check = $h->restateinsertdata($field_values, $data_values, $table);
        if ($check == 1) {
            $returnArr = [
                "ResponseCode" => "200",
                "Result" => "true",
                "title" => "Country Code Add Successfully!!",
                "message" => "Country Code section!",
                "action" => "list_code.php",
            ];
        } 
    }
	else if($_POST['type'] == 'edit_code')
{
	$okey = $_POST['status'];
	$title = $rstate->real_escape_string($_POST['title']);
	$id = $_POST['id'];
	$table="tbl_code";
  $field = array('status'=>$okey,'ccode'=>$title);
  $where = "where id=".$id."";
$h = new Estate();
	  $check = $h->restateupdateData($field,$table,$where);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Country Code Update Successfully!!","message"=>"Country Code section!","action"=>"list_code.php");
}

}
else if($_POST['type'] == 'add_page')
{
	$ctitle = $rstate->real_escape_string($_POST['ctitle']);
							$cstatus = $_POST['cstatus'];
							$cdesc = $rstate->real_escape_string($_POST['cdesc']);
  $table="tbl_page";
  
  $field_values=array("description","status","title");
  $data_values=array("$cdesc","$cstatus","$ctitle");
  
$h = new Estate();
	  $check = $h->restateinsertdata($field_values,$data_values,$table);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Page Add Successfully!!","message"=>"Page section!","action"=>"list_page.php");
}

}
else if($_POST['type'] == 'edit_page')
{
	$id = $_POST['id'];
	$ctitle = $rstate->real_escape_string($_POST['ctitle']);
							$cstatus = $_POST['cstatus'];
							$cdesc = $rstate->real_escape_string($_POST['cdesc']);
	
		$table="tbl_page";
  $field=array('description'=>$cdesc,'status'=>$cstatus,'title'=>$ctitle);
  $where = "where id=".$id."";
$h = new Estate();
	  $check = $h->restateupdateData($field,$table,$where);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Page Update Successfully!!","message"=>"Page section!","action"=>"list_page.php");
}

}
else if($_POST['type'] == 'edit_payment')
{
	
			$attributes = mysqli_real_escape_string($rstate,$_POST['p_attr']);
			$ptitle = mysqli_real_escape_string($rstate,$_POST['ptitle']);
			$okey = $_POST['status'];
			$id = $_POST['id'];
			$p_show = $_POST['p_show'];
			$s_show = $_POST['s_show'];
			$target_dir = dirname( dirname(__FILE__) )."/images/payment/";
			$url = "images/payment/";
			$temp = explode(".", $_FILES["cat_img"]["name"]);
$newfilename = round(microtime(true)) . '.' . end($temp);
$target_file = $target_dir . basename($newfilename);
$url = $url . basename($newfilename);
if($_FILES["cat_img"]["name"] != '')
{

	move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
	$table="tbl_payment_list";
  $field = array('status'=>$okey,'img'=>$url,'attributes'=>$attributes,'subtitle'=>$ptitle,'p_show'=>$p_show,'s_show'=>$s_show);
  $where = "where id=".$id."";
$h = new Estate();
	  $check = $h->restateupdateData($field,$table,$where);
  
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Payment Gateway Update Successfully!!","message"=>"Payment Gateway section!","action"=>"paymentlist.php");
}


}
else 
{
	$table="tbl_payment_list";
  $field = array('status'=>$okey,'attributes'=>$attributes,'subtitle'=>$ptitle,'p_show'=>$p_show,'s_show'=>$s_show);
  $where = "where id=".$id."";
$h = new Estate();
	  $check = $h->restateupdateData($field,$table,$where);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Payment Gateway Update Successfully!!","message"=>"Payment Gateway section!","action"=>"paymentlist.php");
}

}
}
else if($_POST['type'] == 'add_coupon')
{
	$ccode = $rstate->real_escape_string($_POST['coupon_code']);

							$cdate = $_POST['expire_date'];
							$minamt = $_POST['min_amt'];
							$ctitle = $rstate->real_escape_string($_POST['title']);
							$subtitle = $rstate->real_escape_string($_POST['subtitle']);
							$cstatus = $_POST['status'];
							$cvalue = $_POST['coupon_val'];
							$cdesc = $rstate->real_escape_string($_POST['description']);
							
			$target_dir = dirname( dirname(__FILE__) )."/images/offer/";
			$url = "images/offer/";
			$temp = explode(".", $_FILES["coupon_img"]["name"]);
$newfilename = round(microtime(true)) . '.' . end($temp);
$target_file = $target_dir . basename($newfilename);
$url = $url . basename($newfilename);

	move_uploaded_file($_FILES["coupon_img"]["tmp_name"], $target_file);
	$table="tbl_coupon";
  $store_id = $sdata['id'];
  $field_values=array("c_img","c_desc","c_value","c_title","status","cdate","ctitle","min_amt","subtitle");
  $data_values=array("$url","$cdesc","$cvalue","$ccode","$cstatus","$cdate","$ctitle","$minamt","$subtitle");
  
$h = new Estate();
	  $check = $h->restateinsertdata($field_values,$data_values,$table);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Coupon Add Successfully!!","message"=>"Coupon section!","action"=>"list_coupon.php");
}


}
else if($_POST['type'] == 'edit_coupon')
{
$ccode = $rstate->real_escape_string($_POST['coupon_code']);
$id = $_POST['id'];
							$cdate = $_POST['expire_date'];
							$minamt = $_POST['min_amt'];
							$ctitle = $rstate->real_escape_string($_POST['title']);
							$subtitle = $rstate->real_escape_string($_POST['subtitle']);
							$cstatus = $_POST['status'];
							$cvalue = $_POST['coupon_val'];
							$cdesc = $rstate->real_escape_string($_POST['description']);
							
			$target_dir = dirname( dirname(__FILE__) )."/images/offer/";
			$url = "images/offer/";
			$temp = explode(".", $_FILES["coupon_img"]["name"]);
$newfilename = round(microtime(true)) . '.' . end($temp);
$target_file = $target_dir . basename($newfilename);
$url = $url . basename($newfilename);
if($_FILES["coupon_img"]["name"] != '')
{

	move_uploaded_file($_FILES["coupon_img"]["tmp_name"], $target_file);
	$table="tbl_coupon";
  $field=array('c_img'=>$url,'c_desc'=>$cdesc,'c_value'=>$cvalue,'c_title'=>$ccode,'status'=>$cstatus,'cdate'=>$cdate,'ctitle'=>$ctitle,'min_amt'=>$minamt,'subtitle'=>$subtitle);
  $where = "where id=".$id."";
$h = new Estate();
	  $check = $h->restateupdateData($field,$table,$where);
  
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Offer Update Successfully!!","message"=>"Offer section!","action"=>"list_coupon.php");
}


}
else 
{
	$table="tbl_coupon";
  $field=array('c_desc'=>$cdesc,'c_value'=>$cvalue,'c_title'=>$ccode,'status'=>$cstatus,'cdate'=>$cdate,'ctitle'=>$ctitle,'min_amt'=>$minamt,'subtitle'=>$subtitle);
  $where = "where id=".$id."";
$h = new Estate();
	  $check = $h->restateupdateData($field,$table,$where);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Offer Update Successfully!!","message"=>"Offer section!","action"=>"list_coupon.php");
}

}	
}
else if($_POST['type'] == 'add_gal_category')
{
	$property = mysqli_real_escape_string($rstate,$_POST['property']);
			$title = mysqli_real_escape_string($rstate,$_POST['title']);
			$status = $_POST['status'];
			
			
				


  $table="tbl_gal_cat";
  $field_values=array("pid","title","status");
  $data_values=array("$property","$title","$status");
  
$h = new Estate();
	  $check = $h->restateinsertdata($field_values,$data_values,$table);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Gallery Category Add Successfully!!","message"=>"Gallery Category section!","action"=>"list_gal_cat.php");
}

}

else if($_POST['type'] == 'edit_gal_category')
{
	$property = mysqli_real_escape_string($rstate,$_POST['property']);
			$title = mysqli_real_escape_string($rstate,$_POST['title']);
			$status = $_POST['status'];
			$id = $_POST['id'];
			
				


  $id = $_POST['id'];
		
		$table="tbl_gal_cat";
  $field = array('pid'=>$property,'status'=>$status,'title'=>$title);
  $where = "where id=".$id."";
$h = new Estate();
	  $check = $h->restateupdateData($field,$table,$where);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Gallery Category Update Successfully!!","message"=>"Gallery Category section!","action"=>"list_gal_cat.php");
}

}

else if($_POST['type'] == 'add_faq')
{
	$question = mysqli_real_escape_string($rstate,$_POST['question']);
			$answer = mysqli_real_escape_string($rstate,$_POST['answer']);
			$okey = $_POST['status'];
			
			
				


  $table="tbl_faq";
  $field_values=array("question","answer","status");
  $data_values=array("$question","$answer","$okey");
  
$h = new Estate();
	  $check = $h->restateinsertdata($field_values,$data_values,$table);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Faq Add Successfully!!","message"=>"Faq section!","action"=>"list_faq.php");
}

}
else if($_POST['type'] == 'edit_faq')
{
	$question = mysqli_real_escape_string($rstate,$_POST['question']);
			$answer = mysqli_real_escape_string($rstate,$_POST['answer']);
			$okey = $_POST['status'];
	$id = $_POST['id'];
		
		$table="tbl_faq";
  $field = array('question'=>$question,'status'=>$okey,'answer'=>$answer);
  $where = "where id=".$id."";
$h = new Estate();
	  $check = $h->restateupdateData($field,$table,$where);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Faq Update Successfully!!","message"=>"Faq section!","action"=>"list_faq.php");
}

}

else if($_POST['type'] == 'update_reason')
{
	$reason = mysqli_real_escape_string($rstate,$_POST['reason']);
			
	$id = $_POST['id'];
		
		$table="tbl_book";
  $field = array('cancle_reason'=>$reason,'book_status'=>'Cancelled');
  $where = "where id=".$id."";
$h = new Estate();
	  $check = $h->restateupdateData($field,$table,$where);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Booking Cancelled Successfully!!","message"=>"Booking section!","action"=>"pending.php");
}

}
else if($_POST['type'] == 'edit_commission')
{
	$commission = $_POST['commission'];
	$id = $_POST['id'];
		$table="tbl_rider";
   $field = "commission=" . $commission . "";
  $where = "where id=".$id."";
$h = new Estate();
	  $check = $h->restateupdateData_single($field,$table,$where);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Commission Update Successfully!!","message"=>"Commission section!","action"=>"riderlist.php");
}

}
else if($_POST['type'] == 'edit_profile')
{
	$dname = $_POST['username'];
			$dsname = $_POST['password'];
			$mobile = $_POST['mobile'];
	$id = $_POST['id'];
	$table="admin";
  $field = array('username'=>$dname,'password'=>$dsname,'mobile'=>$mobile);
  $where = "where id=".$id."";
$h = new Estate();
	  $check = $h->restateupdateData($field,$table,$where);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Profile Update Successfully!!","message"=>"Profile  section!","action"=>"profile.php");
}

}
else if($_POST['type'] == 'edit_setting')
{
$webname = mysqli_real_escape_string($rstate,$_POST['webname']);
			$timezone = $_POST['timezone'];
			$currency = $_POST['currency'];
			$id = $_POST['id'];
			$sms_type = $_POST['sms_type'];
			$auth_key = $_POST['auth_key'];
			$otp_id = $_POST['otp_id'];
			$acc_id = $_POST['acc_id'];
			$auth_token = $_POST['auth_token'];
			$twilio_number = $_POST['twilio_number'];
			$wlimit = $_POST['wlimit'];
			$one_key = $_POST['one_key'];
			$one_hash = $_POST['one_hash'];
			$otp_auth = $_POST['otp_auth'];
			$scredit = $_POST['scredit'];
			$rcredit =$_POST['rcredit'];
			
			
			$target_dir = dirname( dirname(__FILE__) )."/images/website/";
			$url = "images/website/";
			$temp = explode(".", $_FILES["weblogo"]["name"]);
$newfilename = round(microtime(true)) . '.' . end($temp);
$target_file = $target_dir . basename($newfilename);
$url = $url . basename($newfilename);
if($_FILES["weblogo"]["name"] != '')
{

	move_uploaded_file($_FILES["weblogo"]["tmp_name"], $target_file);
	$table="tbl_setting";
  $field = array('timezone'=>$timezone,'otp_auth'=>$otp_auth,'twilio_number'=>$twilio_number,'auth_token'=>$auth_token,'acc_id'=>$acc_id,'otp_id'=>$otp_id,'auth_key'=>$auth_key,'sms_type'=>$sms_type,'weblogo'=>$url,'webname'=>$webname,'currency'=>$currency,'one_key'=>$one_key,'one_hash'=>$one_hash,'scredit'=>$scredit,'rcredit'=>$rcredit,'wlimit'=>$wlimit);
  $where = "where id=".$id."";
$h = new Estate();
	  $check = $h->restateupdateData($field,$table,$where);
  
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Setting Update Successfully!!","message"=>"Setting section!","action"=>"setting.php");
}
}
else 
{
	$table="tbl_setting";
  $field = array('timezone'=>$timezone,'otp_auth'=>$otp_auth,'twilio_number'=>$twilio_number,'auth_token'=>$auth_token,'acc_id'=>$acc_id,'otp_id'=>$otp_id,'auth_key'=>$auth_key,'sms_type'=>$sms_type,'webname'=>$webname,'currency'=>$currency,'one_key'=>$one_key,'one_hash'=>$one_hash,'scredit'=>$scredit,'rcredit'=>$rcredit,'wlimit'=>$wlimit);
  $where = "where id=".$id."";
$h = new Estate();
	  $check = $h->restateupdateData($field,$table,$where);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Setting Update Successfully!!","message"=>"Offer section!","action"=>"setting.php");
}

}	
}

	elseif ($_POST["type"] == "add_category") {
        $okey = $_POST["status"];
        $title = $rstate->real_escape_string($_POST["title"]);
        $target_dir = dirname(dirname(__FILE__)) . "/images/category/";
        $url = "images/category/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        
            move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
            $table = "tbl_category";
            $field_values = ["img", "status", "title"];
            $data_values = ["$url", "$okey", "$title"];

            $h = new Estate();
            $check = $h->restateinsertdata($field_values, $data_values, $table);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Category Add Successfully!!",
                    "message" => "Category section!",
                    "action" => "list_category.php",
                ];
            } 
        
    }
	
	elseif ($_POST["type"] == "add_country") {
        $okey = $_POST["status"];
        $title = $rstate->real_escape_string($_POST["title"]);
        $target_dir = dirname(dirname(__FILE__)) . "/images/country/";
        $url = "images/country/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        
            move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
            $table = "tbl_country";
            $field_values = ["img", "status", "title"];
            $data_values = ["$url", "$okey", "$title"];

            $h = new Estate();
            $check = $h->restateinsertdata($field_values, $data_values, $table);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Country Add Successfully!!",
                    "message" => "Country section!",
                    "action" => "list_country.php",
                ];
            } 
        
    }
	
	elseif ($_POST["type"] == "add_package") {
        $status = $_POST["status"];
		$day = $_POST['day'];
		$price = $_POST['price'];
        $title = $rstate->real_escape_string($_POST["title"]);
		$description = $rstate->real_escape_string($_POST["description"]);
        $target_dir = dirname(dirname(__FILE__)) . "/images/package/";
        $url = "images/package/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        
            move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
            $table = "tbl_package";
            $field_values = ["image", "status", "title","day","description","price"];
            $data_values = ["$url", "$status", "$title","$day","$description","$price"];

            $h = new Estate();
            $check = $h->restateinsertdata($field_values, $data_values, $table);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Package Add Successfully!!",
                    "message" => "Package section!",
                    "action" => "list_package.php",
                ];
            } 
        
    }
	
	 elseif ($_POST["type"] == "edit_package") {
         $status = $_POST["status"];
		$day = $_POST['day'];
		$price = $_POST['price'];
		$id = $_POST['id'];
        $title = $rstate->real_escape_string($_POST["title"]);
		$description = $rstate->real_escape_string($_POST["description"]);
        $target_dir = dirname(dirname(__FILE__)) . "/images/package/";
        $url = "images/package/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        if ($_FILES["cat_img"]["name"] != "") {
            
                move_uploaded_file(
                    $_FILES["cat_img"]["tmp_name"],
                    $target_file
                );
                $table = "tbl_package";
                $field = ["status" => $status, "image" => $url, "title" => $title,"description"=>$description,"day"=>$day,"price"=>$price];
                $where = "where id=" . $id . "";
                $h = new Estate();
                $check = $h->restateupdateData($field, $table, $where);

                if ($check == 1) {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "true",
                        "title" => "Package Update Successfully!!",
                        "message" => "Package section!",
                        "action" => "list_package.php",
                    ];
                } 
            
        } else {
            $table = "tbl_package";
            $field = ["status" => $status, "title" => $title,"description"=>$description,"day"=>$day,"price"=>$price];
            $where = "where id=" . $id . "";
            $h = new Estate();
            $check = $h->restateupdateData($field, $table, $where);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Package Update Successfully!!",
                    "message" => "Package section!",
                    "action" => "list_package.php",
                ];
            } 
        
    }
	 }
	
	elseif ($_POST["type"] == "add_extra") {
        $status = $_POST["status"];
		$pid = $_POST['property'];
		$pano = $_POST['pano'];
        $target_dir = dirname(dirname(__FILE__)) . "/images/property/";
        $url = "images/property/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = uniqid().round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        
            move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
             $table = "tbl_extra";
            $field_values = ["img", "status","pid","pano"];
            $data_values = ["$url", "$status","$pid","$pano"];

            $h = new Estate();
            $check = $h->restateinsertdata($field_values, $data_values, $table);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Extra Image Add Successfully!!",
                    "message" => "Extra Image section!",
                    "action" => "list_extra.php",
                ];
            } 
             
        
    }
	
	elseif ($_POST["type"] == "edit_extra") {
        $status = $_POST["status"];
		$pid = $_POST['property'];
		$id = $_POST['id'];
		$pano = $_POST['pano'];
        $target_dir = dirname(dirname(__FILE__)) . "/images/property/";
        $url = "images/property/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = uniqid().round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        if ($_FILES["cat_img"]["name"] != "") {
            
                move_uploaded_file(
                    $_FILES["cat_img"]["tmp_name"],
                    $target_file
                );
                $table = "tbl_extra";
                $field = ["status" => $status, "img" => $url,"pid"=>$pid,"pano"=>$pano];
                $where = "where id=" . $id . "";
                $h = new Estate();
                $check = $h->restateupdateData($field, $table, $where);

                if ($check == 1) {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "true",
                        "title" => "Extra Image Update Successfully!!",
                        "message" => "Extra Image section!",
                        "action" => "list_extra.php",
                    ];
                } 
            
        } else {
            $table = "tbl_extra";
            $field = ["status" => $status,"pid"=>$pid,"pano"=>$pano];
            $where = "where id=" . $id . "";
            $h = new Estate();
            $check = $h->restateupdateData($field, $table, $where);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Extra Image Update Successfully!!",
                    "message" => "Extra Image section!",
                    "action" => "list_extra.php",
                ];
            } 
        }
    }
	
	elseif ($_POST["type"] == "add_gal") {
        $status = $_POST["status"];
		$pid = $_POST['property'];
		$cid = $_POST['galcat'];
        $title = $rstate->real_escape_string($_POST["title"]);
        $target_dir = dirname(dirname(__FILE__)) . "/images/gallery/";
        $url = "images/gallery/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        
            move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
            $table = "tbl_gallery";
            $field_values = ["img", "status", "cat_id","pid"];
            $data_values = ["$url", "$status", "$cid","$pid"];

            $h = new Estate();
            $check = $h->restateinsertdata($field_values, $data_values, $table);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Gallery Add Successfully!!",
                    "message" => "Gallery section!",
                    "action" => "list_gal.php",
                ];
            } 
        
    }
	
	 elseif ($_POST["type"] == "edit_gal") {
        $status = $_POST["status"];
		$pid = $_POST['property'];
		$cid = $_POST['galcat'];
		$id = $_POST['id'];
        $target_dir = dirname(dirname(__FILE__)) . "/images/gallery/";
        $url = "images/gallery/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        if ($_FILES["cat_img"]["name"] != "") {
            
                move_uploaded_file(
                    $_FILES["cat_img"]["tmp_name"],
                    $target_file
                );
                $table = "tbl_gallery";
                $field = ["status" => $status, "img" => $url,"cat_id"=>$cid,"pid"=>$pid];
                $where = "where id=" . $id . "";
                $h = new Estate();
                $check = $h->restateupdateData($field, $table, $where);

                if ($check == 1) {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "true",
                        "title" => "Gallery Update Successfully!!",
                        "message" => "Gallery section!",
                        "action" => "list_gal.php",
                    ];
                } 
            
        } else {
            $table = "tbl_gallery";
            $field = ["status" => $status,"cat_id"=>$cid,"pid"=>$pid];
            $where = "where id=" . $id . "";
            $h = new Estate();
            $check = $h->restateupdateData($field, $table, $where);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Gallery Update Successfully!!",
                    "message" => "Gallery section!",
                    "action" => "list_gal.php",
                ];
            } 
        }
    }
	
	elseif ($_POST["type"] == "add_property") {
        $status = $_POST["status"];
        $title = $rstate->real_escape_string($_POST["title"]);
		$address = $rstate->real_escape_string($_POST["address"]);
		$description = $rstate->real_escape_string($_POST["description"]);
		$ccount = $rstate->real_escape_string($_POST["ccount"]);
		$plimit = $_POST['plimit'];
		$country_id = $_POST['country_id'];
		$pbuysell = $_POST['pbuysell'];
		$facility = implode(',',$_POST['facility']);
		$ptype = $_POST['ptype'];
		$beds = $_POST['beds'];
		$bathroom = $_POST['bathroom'];
		$sqft = $_POST['sqft'];
		$rate = $_POST['rate'];
		$latitude = $_POST['latitude'];
		$longtitude = $_POST['longtitude'];
		$mobile = $_POST['mobile'];
        $target_dir = dirname(dirname(__FILE__)) . "/images/property/";
        $url = "images/property/";
		$user_id='0';
		
		$listing_date = date("Y-m-d H:i:s");
		$price = $_POST['price'];
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        
            move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
            $table = "tbl_property";
            $field_values = ["image", "status", "title","price","address","facility","description","beds","bathroom","sqrft","rate","ptype","latitude","longtitude","mobile","city","listing_date","add_user_id","pbuysell","country_id","plimit"];
            $data_values = ["$url", "$status", "$title","$price","$address","$facility","$description","$beds","$bathroom","$sqft","$rate","$ptype","$latitude","$longtitude","$mobile","$ccount","$listing_date","$user_id","$pbuysell","$country_id","$plimit"];

            $h = new Estate();
            $check = $h->restateinsertdata($field_values, $data_values, $table);
			
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Property Add Successfully!!",
                    "message" => "Property section!",
                    "action" => "list_properties.php",
                ];
            } 
        
    }
	elseif($_POST["type"] == "edit_property")
	{
		$status = $_POST["status"];
		$plimit = $_POST['plimit'];
		$country_id = $_POST['country_id'];
		$pbuysell = $_POST['pbuysell'];
        $title = $rstate->real_escape_string($_POST["title"]);
		$address = $rstate->real_escape_string($_POST["address"]);
		$description = $rstate->real_escape_string($_POST["description"]);
		$ccount = $rstate->real_escape_string($_POST["ccount"]);
		$facility = implode(',',$_POST['facility']);
		$ptype = $_POST['ptype'];
		$beds = $_POST['beds'];
		$bathroom = $_POST['bathroom'];
		$sqft = $_POST['sqft'];
		$rate = $_POST['rate'];
		$latitude = $_POST['latitude'];
		$longtitude = $_POST['longtitude'];
		$mobile = $_POST['mobile'];
        $target_dir = dirname(dirname(__FILE__)) . "/images/property/";
        $url = "images/property/";
		$user_id='0';
		$id = $_POST['id'];
		$listing_date = date("Y-m-d H:i:s");
		$price = $_POST['price'];
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
		 if ($_FILES["cat_img"]["name"] != "") {
           
                move_uploaded_file(
                    $_FILES["cat_img"]["tmp_name"],
                    $target_file
                );
                $table = "tbl_property";
                $field = ["plimit"=>$plimit,"country_id"=>$country_id,"pbuysell"=>$pbuysell,"status" => $status, "image" => $url, "title" => $title,"price"=>$price,"address"=>$address,"facility"=>$facility,"description"=>$description,"beds"=>$beds,"bathroom"=>$bathroom,"sqrft"=>$sqft,"rate"=>$rate,"ptype"=>$ptype,"latitude"=>$latitude,"longtitude"=>$longtitude,"mobile"=>$mobile,"city"=>$ccount,"listing_date"=>$listing_date];
                $where = "where id=" . $id . " and add_user_id=".$user_id."";
                $h = new Estate();
                $check = $h->restateupdateData($field, $table, $where);

                if ($check == 1) {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "true",
                        "title" => "Property Update Successfully!!",
                        "message" => "Property section!",
                        "action" => "list_properties.php",
                    ];
                } 
            
        } else {
            $table = "tbl_property";
                $field = ["plimit"=>$plimit,"country_id"=>$country_id,"pbuysell"=>$pbuysell,"status" => $status, "title" => $title,"price"=>$price,"address"=>$address,"facility"=>$facility,"description"=>$description,"beds"=>$beds,"bathroom"=>$bathroom,"sqrft"=>$sqft,"rate"=>$rate,"ptype"=>$ptype,"latitude"=>$latitude,"longtitude"=>$longtitude,"mobile"=>$mobile,"city"=>$ccount,"listing_date"=>$listing_date];
                $where = "where id=" . $id . " and add_user_id=".$user_id."";
                $h = new Estate();
                $check = $h->restateupdateData($field, $table, $where);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Property Update Successfully!!",
                    "message" => "Property section!",
                    "action" => "list_properties.php",
                ];
            } 
        }
		
	}
	elseif ($_POST["type"] == "add_facility") {
        $okey = $_POST["status"];
        $title = $rstate->real_escape_string($_POST["title"]);
        $target_dir = dirname(dirname(__FILE__)) . "/images/facility/";
        $url = "images/facility/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
      
            move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
            $table = "tbl_facility";
            $field_values = ["img", "status", "title"];
            $data_values = ["$url", "$okey", "$title"];

            $h = new Estate();
            $check = $h->restateinsertdata($field_values, $data_values, $table);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Facility Add Successfully!!",
                    "message" => "Facility section!",
                    "action" => "list_facility.php",
                ];
            } 
        
    }
	
	 elseif ($_POST["type"] == "edit_category") {
        $okey = $_POST["status"];
        $id = $_POST["id"];
        $title = $rstate->real_escape_string($_POST["title"]);
        $target_dir = dirname(dirname(__FILE__)) . "/images/category/";
        $url = "images/category/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        if ($_FILES["cat_img"]["name"] != "") {
           
                move_uploaded_file(
                    $_FILES["cat_img"]["tmp_name"],
                    $target_file
                );
                $table = "tbl_category";
                $field = ["status" => $okey, "img" => $url, "title" => $title];
                $where = "where id=" . $id . "";
                $h = new Estate();
                $check = $h->restateupdateData($field, $table, $where);

                if ($check == 1) {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "true",
                        "title" => "Category Update Successfully!!",
                        "message" => "Category section!",
                        "action" => "list_category.php",
                    ];
                } 
            
        } else {
            $table = "tbl_category";
            $field = ["status" => $okey, "title" => $title];
            $where = "where id=" . $id . "";
            $h = new Estate();
            $check = $h->restateupdateData($field, $table, $where);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Category Update Successfully!!",
                    "message" => "Category section!",
                    "action" => "list_category.php",
                ];
            } 
        }
    }
	
	elseif ($_POST["type"] == "edit_country") {
        $okey = $_POST["status"];
        $id = $_POST["id"];
        $title = $rstate->real_escape_string($_POST["title"]);
        $target_dir = dirname(dirname(__FILE__)) . "/images/country/";
        $url = "images/country/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        if ($_FILES["cat_img"]["name"] != "") {
            
                move_uploaded_file(
                    $_FILES["cat_img"]["tmp_name"],
                    $target_file
                );
                $table = "tbl_country";
                $field = ["status" => $okey, "img" => $url, "title" => $title];
                $where = "where id=" . $id . "";
                $h = new Estate();
                $check = $h->restateupdateData($field, $table, $where);

                if ($check == 1) {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "true",
                        "title" => "Country Update Successfully!!",
                        "message" => "Country section!",
                        "action" => "list_country.php",
                    ];
                }
            
        } else {
            $table = "tbl_country";
            $field = ["status" => $okey, "title" => $title];
            $where = "where id=" . $id . "";
            $h = new Estate();
            $check = $h->restateupdateData($field, $table, $where);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Country Update Successfully!!",
                    "message" => "Country section!",
                    "action" => "list_country.php",
                ];
            } 
        }
    }
	
	 elseif ($_POST["type"] == "edit_facility") {
        $okey = $_POST["status"];
        $id = $_POST["id"];
        $title = $rstate->real_escape_string($_POST["title"]);
        $target_dir = dirname(dirname(__FILE__)) . "/images/facility/";
        $url = "images/facility/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        if ($_FILES["cat_img"]["name"] != "") {
            
                move_uploaded_file(
                    $_FILES["cat_img"]["tmp_name"],
                    $target_file
                );
                $table = "tbl_facility";
                $field = ["status" => $okey, "img" => $url, "title" => $title];
                $where = "where id=" . $id . "";
                $h = new Estate();
                $check = $h->restateupdateData($field, $table, $where);

                if ($check == 1) {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "true",
                        "title" => "Facility Update Successfully!!",
                        "message" => "Facility section!",
                        "action" => "list_facility.php",
                    ];
                } 
            
        } else {
            $table = "tbl_facility";
            $field = ["status" => $okey, "title" => $title];
            $where = "where id=" . $id . "";
            $h = new Estate();
            $check = $h->restateupdateData($field, $table, $where);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Facility Update Successfully!!",
                    "message" => "Facility section!",
                    "action" => "list_category.php",
                ];
            } 
        }
    }
		elseif ($_POST["type"] == "coupon_delete") {
        $id = $_POST["id"];
        $table = "tbl_coupon";
        $where = "where id=" . $id . "";
        $h = new Estate();
        $check = $h->restateDeleteData($where, $table);
        if ($check == 1) {
            $returnArr = [
                "ResponseCode" => "200",
                "Result" => "true",
                "title" => "Coupon Code Delete Successfully!!",
                "message" => "Coupon Code section!",
                "action" => "list_coupon.php",
            ];
        } 
    }
	elseif ($_POST["type"] == "com_payout") {
        $payout_id = $_POST["payout_id"];
        $target_dir = dirname(dirname(__FILE__)) . "/images/proof/";
        $url = "images/proof/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        
            move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
            $table = "payout_setting";
            $field = ["proof" => $url, "status" => "completed"];
            $where = "where id=" . $payout_id . "";
            $h = new Estate();
            $check = $h->restateupdateData($field, $table, $where);

            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Payout Update Successfully!!",
                    "message" => "Payout section!",
                    "action" => "list_payout.php",
                ];
            } 
        
    }
	elseif ($_POST["type"] == "update_status") {
        $id = $_POST["id"];
        $status = $_POST["status"];
        $coll_type = $_POST["coll_type"];
        $page_name = $_POST["page_name"];
         if ($coll_type == "userstatus") {
            $table = "tbl_user";
            $field = "status=" . $status . "";
            $where = "where id=" . $id . "";
            $h = new Estate();
            $check = $h->restateupdateData_single($field, $table, $where);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "User Status Change Successfully!!",
                    "message" => "User section!",
                    "action" => "userlist.php",
                ];
            }
        }elseif ($coll_type == "proper_sell") {
            $table = "tbl_property";
            $field = "is_sell=" . $status . "";
            $where = "where id=" . $id . "";
            $h = new Estate();
            $check = $h->restateupdateData_single($field, $table, $where);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Property Selled Successfully!!",
                    "message" => "User section!",
                    "action" => "list_properties.php",
                ];
            } 
        } elseif ($coll_type == "confirmed_book") {
            $table = "tbl_book";
            $field = "book_status='" . $status . "'";
            $where = "where id=" . $id . "";
            $h = new Estate();
            $check = $h->restateupdateData_single($field, $table, $where);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Booking Confirmed  Successfully!!",
                    "message" => "Booking section!",
                    "action" => "approved.php",
                ];
            } 
			
			$bdata = $rstate->query("select * from tbl_book where id=".$id."")->fetch_assoc();
			$udata = $rstate->query("select * from tbl_user where id=".$bdata['uid']."")->fetch_assoc();
$name = $udata['name'];
$uid = $bdata['uid'];
	   


$content = array(
       "en" => $name.', Your Booking #'.$id.' Has Been Confirmed Successfully.'
   );
$heading = array(
   "en" => "Confirmed Successfully!!"
);

$fields = array(
'app_id' => $set['one_key'],
'included_segments' =>  array("Active Users"),
'data' => array("order_id" =>$id,"type"=>'normal'),
'filters' => array(array('field' => 'tag', 'key' => 'user_id', 'relation' => '=', 'value' => $bdata['uid'])),
'contents' => $content,
'headings' => $heading
);
$fields = json_encode($fields);

 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
curl_setopt($ch, CURLOPT_HTTPHEADER, 
array('Content-Type: application/json; charset=utf-8',
'Authorization: Basic '.$set['one_hash']));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
 
$response = curl_exec($ch);
curl_close($ch);

$timestamp = date("Y-m-d H:i:s");

$title_mains = "Confirmed Successfully!!";
$descriptions = 'Booking #'.$id.' Has Been Confirmed Successfully.';

	   $table="tbl_notification";
  $field_values=array("uid","datetime","title","description");
  $data_values=array("$uid","$timestamp","$title_mains","$descriptions");
  
    $h = new Estate();
	   $h->restateinsertdata_Api($field_values,$data_values,$table);
	   
        } elseif ($coll_type == "Check_in") {
            $table = "tbl_book";
            $field = "book_status='" . $status . "'";
            $where = "where id=" . $id . "";
            $h = new Estate();
            $check = $h->restateupdateData_single($field, $table, $where);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Booking Check In  Successfully!!",
                    "message" => "Booking section!",
                    "action" => "check_in.php",
                ];
            } 
			$bdata = $rstate->query("select * from tbl_book where id=".$id."")->fetch_assoc();
			$udata = $rstate->query("select * from tbl_user where id=".$bdata['uid']."")->fetch_assoc();
$name = $udata['name'];
$uid = $bdata['uid'];
	   


$content = array(
       "en" => $name.', Your Booking #'.$id.' Has Been Check In Successfully.'
   );
$heading = array(
   "en" => "Check In Successfully!!"
);

$fields = array(
'app_id' => $set['one_key'],
'included_segments' =>  array("Active Users"),
'data' => array("order_id" =>$id,"type"=>'normal'),
'filters' => array(array('field' => 'tag', 'key' => 'user_id', 'relation' => '=', 'value' => $uid)),
'contents' => $content,
'headings' => $heading
);
$fields = json_encode($fields);

 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
curl_setopt($ch, CURLOPT_HTTPHEADER, 
array('Content-Type: application/json; charset=utf-8',
'Authorization: Basic '.$set['one_hash']));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
 
$response = curl_exec($ch);
curl_close($ch);

$timestamp = date("Y-m-d H:i:s");

$title_mains = "Check In Successfully!!";
$descriptions = 'Booking #'.$id.' Has Been Check In Successfully.';

	   $table="tbl_notification";
  $field_values=array("uid","datetime","title","description");
  $data_values=array("$uid","$timestamp","$title_mains","$descriptions");
  
    $h = new Estate();
	   $h->restateinsertdata_Api($field_values,$data_values,$table);
	   
        }elseif ($coll_type == "Check_out") {
            $table = "tbl_book";
            $field = "book_status='" . $status . "'";
            $where = "where id=" . $id . "";
            $h = new Estate();
            $check = $h->restateupdateData_single($field, $table, $where);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Booking Check Out  Successfully!!",
                    "message" => "Booking section!",
                    "action" => "completed.php",
                ];
            } 
			$bdata = $rstate->query("select * from tbl_book where id=".$id."")->fetch_assoc();
			$udata = $rstate->query("select * from tbl_user where id=".$bdata['uid']."")->fetch_assoc();
$name = $udata['name'];
$uid = $bdata['uid'];
	   


$content = array(
       "en" => $name.', Your Booking #'.$id.' Has Been Check Out Successfully.'
   );
$heading = array(
   "en" => "Check Out Successfully!!"
);

$fields = array(
'app_id' => $set['one_key'],
'included_segments' =>  array("Active Users"),
'data' => array("order_id" =>$id,"type"=>'normal'),
'filters' => array(array('field' => 'tag', 'key' => 'user_id', 'relation' => '=', 'value' => $uid)),
'contents' => $content,
'headings' => $heading
);
$fields = json_encode($fields);

 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
curl_setopt($ch, CURLOPT_HTTPHEADER, 
array('Content-Type: application/json; charset=utf-8',
'Authorization: Basic '.$set['one_hash']));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
 
$response = curl_exec($ch);
curl_close($ch);

$timestamp = date("Y-m-d H:i:s");

$title_mains = "Check Out Successfully!!";
$descriptions = 'Booking #'.$id.' Has Been Check Out Successfully.';

	   $table="tbl_notification";
  $field_values=array("uid","datetime","title","description");
  $data_values=array("$uid","$timestamp","$title_mains","$descriptions");
  
    $h = new Estate();
	   $h->restateinsertdata_Api($field_values,$data_values,$table);
	   
        }elseif ($coll_type == "dark_mode") {
            $table = "tbl_setting";
            $field = "show_dark=" . $status . "";
            $where = "where id=" . $id . "";
            $h = new Estate();
            $check = $h->restateupdateData_single($field, $table, $where);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Dark Mode Status Change Successfully!!",
                    "message" => "Dark Mode section!",
                    "action" => $page_name,
                ];
            } 
        }

		else {
            $returnArr = [
                "ResponseCode" => "200",
                "Result" => "false",
                "title" => "Option Not There!!",
                "message" => "Error!!",
                "action" => "dashboard.php",
            ];
        }
    }
else 
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"false","title"=>"Don't Try New Function!","message"=>"welcome admin!!","action"=>"dashboard.php");
}	
}
else 
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"false","title"=>"Don't Try New Function!","message"=>"welcome admin!!","action"=>"dashboard.php");
}
echo json_encode($returnArr);
?>