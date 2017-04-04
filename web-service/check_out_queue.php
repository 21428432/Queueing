<?php

/*
 * Following code will checkout the user of the queue 
 * A user is identified by Token 
 */

// array for JSON response
$response = array();

// check for required fields
if (isset($_POST['Token'])) {
    
    $Token = $_POST['Token'];
	$Flag = "Out";
   
    // include db connect class
    require_once __DIR__ . '/db_connect.php';

    // connecting to db
    $db = new DB_CONNECT();
	
	//current time 
     date_default_timezone_set("Africa/Johannesburg");
     $Now = new DateTime("now");
     $Now = $Now->format('h:i');
 
     $timestamp = date("g.i",strtotime($Now));
			      echo"Time:   $timestamp";

    // mysql update row with matched pid
    $result = mysql_query("UPDATE Users SET Flag = '$Flag' WHERE Token = $Token
	                       AND Flag = 'queued' 
	                       AND ($timestamp - Time) > 3");

    // check if row inserted or not
    if ($result) {
        // successfully updated
        $response["success"] = 1;
        $response["message"] = "User successfully checked out.";
        
        // echoing JSON response
        echo json_encode($response);
    } else {
        
    }
} else {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";

    // echoing JSON response
    echo json_encode($response);
}
?>
