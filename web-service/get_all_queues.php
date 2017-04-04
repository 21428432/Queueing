
<?php

/*
 * Following code will list all the user
 */

// array for JSON response
$response = array();


// include db connect class
require_once __DIR__ . '/db_connect.php';


// Enabling error reporting
  
      error_reporting(-1);
        ini_set('display_errors', 'On');

        require_once __DIR__ . '/firebase.php';
        require_once __DIR__ . '/push.php';

        $firebase = new Firebase();
        $push = new Push();

        // notification title
        $title = "Checkout";
        
        // notification message
        $message = "Emerg Mobile";

        $push->setTitle($title);
        $push->setMessage($message);

        $json = '';
        $response_fire = '';

        $json = $push->getPush();
            
 

// connecting to db
$db = new DB_CONNECT();

 //current time 
    date_default_timezone_set("Africa/Johannesburg");
	        $current_time = new DateTime("now");
	        $current_time = $current_time->format('H:i');
			$to_time = strtotime($current_time);

 
// get all users from user table
$result = mysql_query("SELECT Token FROM Users 
                       WHERE (($to_time - Time ) /60) > 3
					   AND Flag = 'queued' 
					   ORDER BY User_id ASC 
					   LIMIT 1") or die(mysql_error());

// check for empty result
if (mysql_num_rows($result) > 0) {

    // looping through all results
    // users node
    $response["users"] = array();

    while ($row = mysql_fetch_array($result)) {

        // temp user array
        $user = array();
        $user["Token"] = $row["Token"];

        // push single product into final response array
        array_push($response["users"], $user);
		
        //$response_fire = $firebase->send($row["Token"], $json);

    }

    // success
    $response["success"] = 1;

 

    // echoing JSON response
    echo json_encode($response);

} else {

    // no users found
    $response["success"] = 0;
    $response["message"] = "No users found";

 

    // echo no users JSON
    echo json_encode($response);

}

?>