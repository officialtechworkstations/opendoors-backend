<?php 
require 'reconfig.php';
$GLOBALS['rstate'] = $rstate;
class Estate {
 

	function restatelogin($username,$password,$tblname) {
		if($tblname == 'admin')
		{
		$q = "select * from ".$tblname." where username='".$username."' and password='".$password."'";
	return $GLOBALS['rstate']->query($q)->num_rows;
		}
		else if($tblname == 'restate_details')
		{
			$q = "select * from ".$tblname." where email='".$username."' and password='".$password."'";
	return $GLOBALS['rstate']->query($q)->num_rows;
		}
		else 
		{
			$q = "select * from ".$tblname." where email='".$username."' and password='".$password."' and status=1";
	return $GLOBALS['rstate']->query($q)->num_rows;
		}
	}
	
	function restateinsertdata($field,$data,$table){

   return 0;
  }
  
  

  
  function insmulti($field,$data,$table){

    $field_values= implode(',',$field);
    $data_values=implode("','",$data);

    $sql = "INSERT INTO $table($field_values)VALUES('$data_values')";
    $result=$GLOBALS['rstate']->multi_query($sql);
  return $result;
  }
  
  function restateinsertdata_id($field,$data,$table){

    return 0;
  }
  
  function restateinsertdata_Api($field,$data,$table){

    $field_values= implode(',',$field);
    $data_values=implode("','",$data);

    $sql = "INSERT INTO $table($field_values)VALUES('$data_values')";
    $result=$GLOBALS['rstate']->query($sql);
  return $result;
  }
  
  function restateinsertdata_Api_Id($field,$data,$table){

    $field_values= implode(',',$field);
    $data_values=implode("','",$data);

    $sql = "INSERT INTO $table($field_values)VALUES('$data_values')";
    $result=$GLOBALS['rstate']->query($sql);
  return $GLOBALS['rstate']->insert_id;
  }
  
  function restateupdateData($field,$table,$where){
return 0;
  }
  
  
  
  
   function restateupdateData_Api($field,$table,$where){
$cols = array();

    foreach($field as $key=>$val) {
        if($val != NULL) // check if value is not null then only add that colunm to array
        {
           $cols[] = "$key = '$val'"; 
        }
    }
    $sql = "UPDATE $table SET " . implode(', ', $cols) . " $where";
$result=$GLOBALS['rstate']->query($sql);
    return $result;
  }
  
  function restateupdateDatanull_Api($field,$table,$where){
$cols = array();

    foreach($field as $key=>$val) {
        if($val != NULL) // check if value is not null then only add that colunm to array
        {
           $cols[] = "$key = '$val'"; 
        }
		else 
		{
			$cols[] = "$key = NULL"; 
		}
    }
	
 $sql = "UPDATE $table SET " . implode(', ', $cols) . " $where";
$result=$GLOBALS['rstate']->query($sql);
    return $result;
  }
  
  
  
  
  function restateupdateData_single($field,$table,$where){
return 0;
  }
  
  function restaterestateDeleteData($where,$table){

   return 0;
  }
  
  function restateDeleteData_Api($where,$table){

    return 0;
  }
  function restateDeleteData_Api_fav($where,$table){

     $sql = "Delete From $table $where";
    $result=$GLOBALS['rstate']->query($sql);
  return $result;
  }
  
 
}
?>