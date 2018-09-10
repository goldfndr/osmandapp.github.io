<?php
  	include '../reports/db_conn.php';
    $dbconn = db_conn();
    $visiblename = pg_escape_string($dbconn, $_POST["visibleName"]);
    $useremail = pg_escape_string($dbconn, $_POST["email"]);
    $country = pg_escape_string($dbconn, $_POST["preferredCountry"]);
    $pos = strpos($useremail, "@");
    $insert = $pos === false;
    if($pos === false) {
      $insert = true;
    } else {
      $result = pg_query($dbconn, "UPDATE supporters SET visiblename='{$visiblename}', preferred_region='{$country}' ".
        " where useremail='${useremail}' RETURNING userid, token;");
        $insert = !$result;
        if(!$insert) {
          $row = pg_fetch_row($result);
          if(!$row) {
            $insert = true;
          }
        }
    }

	  if($insert) {
      $rand = rand(100000,1000000);
  		$result = pg_query($dbconn, "INSERT INTO supporters(userid, token, visiblename, useremail, preferred_region)  ".
  			" VALUES (nextval('supporters_seq'), '{$rand}', '{$visiblename}', '{$useremail}', '{$country}') RETURNING userid, token;");
  		$row = pg_fetch_row($result);
  		if(!$result) {
  			$res = array();        
  			$res['error'] = "Error";
        $res['error'] = "can't insert - {$visiblename} {$useremail}";
  			echo json_encode($res);
  			die;
  		}
  		
  	}  	
    $res = array();        
    $res['visibleName'] = $_POST["visibleName"]; 
    $res['email'] = $_POST["email"];
    $res['preferredCountry'] = $_POST["preferredCountry"]; 
    $res['userid'] = $row[0]; 
    $res['token'] = $row[1]; 
	   echo json_encode($res);
  
?>