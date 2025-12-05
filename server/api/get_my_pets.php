<?php
header("Access-Control-Allow-Origin: *"); // running as crome app

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (!isset($_GET["user_id"])) {
        $response = ["success" => false, "message" => "Bad Request"];
        sendJsonResponse($response);
        exit();
    }
    $userid = $_GET["user_id"];
    include "dbconnect.php";
    $sqlgetpet = "SELECT * FROM `tbl_pets` WHERE `user_id` = '$userid'";
    $result = $connect->query($sqlgetpet);
    if ($result->num_rows > 0) {
        $userdata = [];
        while ($row = $result->fetch_assoc()) {
            $userdata[] = $row;
        }
        $response = [
            "success" => true,
            "message" => "Successful",
            "data" => $userdata,
        ];
        sendJsonResponse($response);
    } else {
        $response = [
            "success" => false,
            "message" => "Invalid request",
            "data" => null,
        ];
        sendJsonResponse($response);
    }
} else {
    $response = ["success" => false, "message" => "Method Not Allowed"];
    sendJsonResponse($response);
    exit();
}

function sendJsonResponse($sentArray)
{
    header("Content-Type: application/json");
    echo json_encode($sentArray);
}
?>
