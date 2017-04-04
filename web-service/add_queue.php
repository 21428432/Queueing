<?php

/*
 * Following code will create a new user row
 * All user details are read from HTTP Post Request
 */

// array for JSON response
$response = array();

//current time for user joing the queue
 date_default_timezone_set("Africa/Johannesburg");
 $date = new DateTime("now");
 $date = $date->format('h:i');
 
 
 $timestamp = date("g.i",strtotime($date));
			  echo"Time   $timestamp";

// check for required fields
if (isset($_POST['Token']) && isset($_POST['Gender'])) {
    
    $Token = $_POST['Token'];
    $Gender = $_POST['Gender'];
    $Time = $timestamp;
    $Flag = "queued";

    // include db connect class
    require_once __DIR__ . '/db_connect.php';

    // connecting to db
    $db = new DB_CONNECT();

    // mysql inserting a new row
    $result = mysql_query("INSERT INTO Users(Token, Gender, Time, Flag) VALUES('$Token', '$Gender', '$Time','$Flag')");

    // check if row inserted or not
    if ($result) {
        // successfully inserted into database
        $response["success"] = 1;
        $response["message"] = "user successfully created.";

        // echoing JSON response
        echo json_encode($response);
    } else {
        // failed to insert row
        $response["success"] = 0;
        $response["message"] = "Oops! An error occurred.";
        
        // echoing JSON response
        echo json_encode($response);
    }
} else {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";

    // echoing JSON response
    echo json_encode($response);
}
?>