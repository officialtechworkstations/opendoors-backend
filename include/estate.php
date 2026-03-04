<?php
require 'reconfig.php';
$GLOBALS['rstate'] = $rstate;
class Estate
{
    function restatelogin($username, $password, $tblname)
    {
        $db = $GLOBALS['rstate'];

        if ($tblname == 'admin') {
            // Fetch by username only — verify password hash separately
            $q    = "SELECT * FROM `admin` WHERE `username` = '" . $db->real_escape_string($username) . "' LIMIT 1";
            $row  = $db->query($q)->fetch_assoc();
            return ($row && password_verify($password, $row['password'])) ? $row : false;

        } elseif ($tblname == 'restate_details') {
            // Fetch by email only — verify password hash separately
            $q    = "SELECT * FROM `restate_details` WHERE `email` = '" . $db->real_escape_string($username) . "' LIMIT 1";
            $row  = $db->query($q)->fetch_assoc();
            return ($row && password_verify($password, $row['password'])) ? $row : false;

        } else {
            // All other tables (e.g. tbl_user) — fetch by email and active status
            $q    = "SELECT * FROM `{$tblname}` WHERE `email` = '" . $db->real_escape_string($username) . "' AND `status` = 1 LIMIT 1";
            $row  = $db->query($q)->fetch_assoc();
            return ($row && password_verify($password, $row['password'])) ? $row : false;
        }
    }

    function restateinsertdata($field, $data, $table)
    {
        $field_values = implode(',', $field);
        $data_values = implode("','", $data);

        $sql = "INSERT INTO $table($field_values)VALUES('$data_values')";
        $result = $GLOBALS['rstate']->query($sql);
        return $result;
    }

    function insmulti($field, $data, $table)
    {
        $field_values = implode(',', $field);
        $data_values = implode("','", $data);

        $sql = "INSERT INTO $table($field_values)VALUES('$data_values')";
        $result = $GLOBALS['rstate']->multi_query($sql);
        return $result;
    }

    function restateinsertdata_id($field, $data, $table)
    {

        $field_values = implode(',', $field);
        $data_values = implode("','", $data);

        $sql = "INSERT INTO $table($field_values)VALUES('$data_values')";
        $result = $GLOBALS['rstate']->query($sql);
        return $GLOBALS['rstate']->insert_id;
    }

    function restateinsertdata_Api($field, $data, $table)
    {

        $field_values = implode(',', $field);
        $data_values = implode("','", $data);

        $sql = "INSERT INTO $table($field_values)VALUES('$data_values')";
        $result = $GLOBALS['rstate']->query($sql);
        return $result;
    }

    function restateinsertdata_Api_Id($field, $data, $table)
    {

        $field_values = implode(',', $field);
        $data_values = implode("','", $data);

        $sql = "INSERT INTO $table($field_values)VALUES('$data_values')";
        $result = $GLOBALS['rstate']->query($sql);
        return $GLOBALS['rstate']->insert_id;
    }

    function restateupdateData($field, $table, $where)
    {
        $cols = array();

        foreach ($field as $key => $val)
        {
            if ($val != NULL) // check if value is not null then only add that colunm to array
            
            {

                $cols[] = "$key = '$val'";

            }
        }
        $sql = "UPDATE $table SET " . implode(', ', $cols) . " $where";
        $result = $GLOBALS['rstate']->query($sql);
        return $result;
    }

    function restateupdateData_Api($field, $table, $where)
    {
        $cols = array();

        foreach ($field as $key => $val)
        {
            if ($val != NULL) // check if value is not null then only add that colunm to array
            
            {
                $cols[] = "$key = '$val'";
            }
        }
        $sql = "UPDATE $table SET " . implode(', ', $cols) . " $where";
        $result = $GLOBALS['rstate']->query($sql);
        return $sql;
    }

    function restateupdateDatanull_Api($field, $table, $where)
    {
        $cols = array();

        foreach ($field as $key => $val)
        {
			// check if value is not null then only add that colunm to array
            if ($val != NULL) {
                $cols[] = "$key = '$val'";
            } else {
                $cols[] = "$key = NULL";
            }
        }

        $sql = "UPDATE $table SET " . implode(', ', $cols) . " $where";
        $result = $GLOBALS['rstate']->query($sql);
        return $result;
    }

    function restateupdateData_single($field, $table, $where)
    {
        $query = "UPDATE $table SET $field";

        $sql = $query . ' ' . $where;
        $result = $GLOBALS['rstate']->query($sql);
        return $result;
    }

    function restaterestateDeleteData($where, $table)
    {

        $sql = "Delete From $table $where";
        $result = $GLOBALS['rstate']->query($sql);
        return $result;
    }

    function restateDeleteData_Api($where, $table)
    {

        $sql = "Delete From $table $where";
        $result = $GLOBALS['rstate']->query($sql);
        return $result;
    }

}
