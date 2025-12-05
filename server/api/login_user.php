<?php

//http header config(allow all origin access domain )
header("Access-Control-Allow-Origin: *");


//if request method from server is POST,else error sent
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    //if email/password from POST array is null, bad request error is sent
    if(!isset($_POST['email'])|| !isset($_POST['password'])) {
        $response = array('success' => false , 'message' => 'Bad request');
        sendJsonResponse($response);
        exit();
    }
    //declare variable from POST array
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashedpassword = sha1($password);
    //check if user data existed already
    include 'dbconnect.php';
    $sqlusercheck = " SELECT * FROM tbl_users WHERE user_email = '$email' AND  user_password = '$hashedpassword' ";
    // query result is passed to a variable
    $result = $connect->query($sqlusercheck);
    // if there are rows frome query result, userdata is passed in array 
    if($result-> num_rows > 0) {
        $userdata = array();
        while($row = $result -> fetch_assoc()) {
            $userdata[] = $row;}
        //if yes, login success,data send (JSON)
        $response = array('success' => true, 'message' => 'login successful', 'data' => $userdata);
        sendJsonResponse($response);}
        //else, send error
        else{
        $response = array('success'=> false , 'message'=> 'login failed');
        sendJsonResponse($response);
        }

    
// else form POST req. method
    }else{
    $response = array('status' => 'failed' , 'message' => 'Method Not Allowed') ;
    sendJsonResponse($response);
    exit();
    
}
//	function to send json response	

function sendJsonResponse($sentArray){
    header('Content-Type: application/json');
    echo json_encode($sentArray);
}



?>