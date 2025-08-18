<?php
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
function generate_random()
{
    require dirname(dirname(__FILE__)) . '/include/reconfig.php';
    $six_digit_random_number = mt_rand(100000, 999999);
    $c_refer                 = $rstate->query("select * from tbl_user where refercode=" . $six_digit_random_number . "")->num_rows;
    if ($c_refer != 0) {
        generate_random();
    } else {
        return $six_digit_random_number;
    }
}

if ($data['name'] == '' or $data['mobile'] == '' or $data['password'] == '' or $data['ccode'] == '' or $data['email'] == '') {
    $returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "Something Went Wrong!"
    );
} else {
    
    $name     = strip_tags(mysqli_real_escape_string($rstate, $data['name']));
    $email     = strip_tags(mysqli_real_escape_string($rstate, $data['email']));
    $mobile    = strip_tags(mysqli_real_escape_string($rstate, $data['mobile']));
    $ccode     = strip_tags(mysqli_real_escape_string($rstate, $data['ccode']));
    $password  = strip_tags(mysqli_real_escape_string($rstate, $data['password']));
    $refercode = strip_tags(mysqli_real_escape_string($rstate, $data['refercode']));
    
    
    $checkmob   = $rstate->query("select * from tbl_user where mobile=" . $mobile . "");
    $checkemail = $rstate->query("select * from tbl_user where email='" . $email . "'");
    
    if ($checkmob->num_rows != 0) {
        $returnArr = array(
            "ResponseCode" => "401",
            "Result" => "false",
            "ResponseMsg" => "Mobile Number Already Used!"
        );
    } else if ($checkemail->num_rows != 0) {
        $returnArr = array(
            "ResponseCode" => "401",
            "Result" => "false",
            "ResponseMsg" => "Email Address Already Used!"
        );
    } else {
        
        if ($refercode != '') {
            $c_refer = $rstate->query("select * from tbl_user where refercode=" . $refercode . "")->num_rows;
            if ($c_refer != 0) {
                
                $timestamp    = date("Y-m-d H:i:s");
                $prentcode    = generate_random();
                $wallet       = $rstate->query("select * from tbl_setting")->fetch_assoc();
                $fin          = $wallet['scredit'];
                $table        = "tbl_user";
                $field_values = array(
                    "name",
                    "email",
                    "mobile",
                    "reg_date",
                    "password",
                    "ccode",
                    "refercode",
                    "wallet",
                    "parentcode"
                );
                $data_values  = array(
                    "$name",
                    "$email",
                    "$mobile",
                    "$timestamp",
                    "$password",
                    "$ccode",
                    "$prentcode",
                    "$fin",
                    "$refercode"
                );
                
                $h     = new Estate();
                $check = $h->restateinsertdata_Api_Id($field_values, $data_values, $table);
                
                $table        = "wallet_report";
                $field_values = array(
                    "uid",
                    "message",
                    "status",
                    "amt",
                    "tdate"
                );
                $data_values  = array(
                    "$check",
                    'Sign up Credit Added!!',
                    'Credit',
                    "$fin",
                    "$timestamp"
                );
                
                $h      = new Estate();
                $checks = $h->restateinsertdata_Api($field_values, $data_values, $table);
                
                $c = $rstate->query("select * from tbl_user where id=" . $check . "")->fetch_assoc();
                
                $returnArr = array(
                    "UserLogin" => $c,
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "ResponseMsg" => "Sign Up Done Successfully!"
                );
            } else {
                $returnArr = array(
                    "ResponseCode" => "401",
                    "Result" => "false",
                    "ResponseMsg" => "Refer Code Not Found Please Try Again!!"
                );
            }
        } else {
            $timestamp    = date("Y-m-d H:i:s");
            $prentcode    = generate_random();
            $table        = "tbl_user";
            $field_values = array(
                "name",
                "email",
                "mobile",
                "reg_date",
                "password",
                "ccode",
                "refercode"
            );
            $data_values  = array(
                "$name",
                "$email",
                "$mobile",
                "$timestamp",
                "$password",
                "$ccode",
                "$prentcode"
            );
            $h            = new Estate();
            $check        = $h->restateinsertdata_Api_Id($field_values, $data_values, $table);
            $c            = $rstate->query("select * from tbl_user where id=" . $check . "")->fetch_assoc();
            $returnArr    = array(
                "UserLogin" => $c,
                "ResponseCode" => "200",
                "Result" => "true",
                "ResponseMsg" => "Sign Up Done Successfully!"
            );
            
        }
        
    }
}

echo json_encode($returnArr);