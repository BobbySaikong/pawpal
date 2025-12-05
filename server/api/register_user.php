<?php
//http header data
header(header: "Access-Control-Allow-Origin: *");
include 'dbconnect.php';

//if request method (from server content) is not POST, send 405, and error (method not allowed)
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    //405 response code:
    //The server cannot or will not process the request due to something that is perceived to be a client error (e.g., malformed request syntax, invalid request message framing, or deceptive request routing).
    http_response_code(response_code: 405);
    //encode value to JSON and output
    //array in map structure (key-value)(also can be in list,array,trees,2d,3d)
    echo json_encode(array('error' => 'Method Not Allowed'));
    //terminate script after execute
    exit;
}

//if any of content in POST is null/undeclared, 400 is sent + error (bad request)
if (!isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['name']) || !isset($_POST['phone'])) {
    //The server cannot or will not process the request due to something that is perceived to be a client error (e.g., malformed request syntax, invalid request message framing, or deceptive request routing).
    http_response_code(response_code: 400);
    echo json_encode(array('error' => 'Bad Request'));
    exit();
}

//pass variable from HTTP POST method (executed from dart files)
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$phone = $_POST['phone'];

//run sha1 and passed to variable
$hashedpassword = sha1(string: $password);


//checking existing email

//sql query
$sqlemailcheck = " SELECT * FROM tbl_users WHERE user_email = '$email' ";
//after successfully connecting to server
$sqlresult = $connect->query($sqlemailcheck);
if ($sqlresult->num_rows > 0) {
    $response = array('status' => 'failed', 'message' => 'Email existed');
    sendJsonResponse($response);
    exit();

}

// Insert new user into DB
$sqlregister = " INSERT INTO tbl_users ( user_name, user_email, user_password , user_phone ) VALUES ('$name','$email' , '$hashedpassword', '$phone');";

//exeption handling(try-catch)
try {
    //if result of query is == true(success), response sent in JSON
    if ($connect->query($sqlregister) === TRUE) {
        $response = array('success' => true, 'message' => 'registration successful!');
        sendJsonResponse($response);
    } else {
        //if query failed, error message sent
        $response = array('success' => false, 'message' => "registration failed.");
        sendJsonResponse($response);
    }
    //error message sent + stacktrace is displayed
} catch (Exception $ex) {
    $response = array("status" => "error", "message" => $ex->getMessage() );
    $ex->getTraceAsString();

}

//input array(string) data (response from query), then encode to JSON before returning it
function sendJsonResponse($response){
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>