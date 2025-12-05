<?php
header("Access-Control-Allow-Origin: *");
include "dbconnect.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Method Not Allowed"]);
    exit();
}
$userid = $_POST["user_id"];
$petname = addslashes($_POST["pet_name"]);
$petType = $_POST["pet_type"];
$latitude = $_POST["latitude"];
$longitude = $_POST["longitude"];
$description = addslashes($_POST["description"]);
$encodedimage = base64_decode($_POST["image"]);

// Insert new service into database
$sqladdpet = "INSERT INTO `tbl_pets`(`user_id`, `pet_name`, `pet_type`,`description`,`lat`, `lng`)
	VALUES ('$userid','$petname','$petType','$description','$latitude','$longitude')";
try {
    if ($conn->query($sqladdpet) === true) {
        $last_id = $connect->insert_id;
        $filename = ". $petname . ".png";
        file_put_contents("../uploads/" . $filename, $decodedImage);

        $response = [
            "success" => true,
            "message" => "Pet submitted successfully",
        ];
        sendJsonResponse($response);
    } else {
        $response = ["success" => false, "message" => "Pet not submitted"];
        sendJsonResponse($response);
    }
} catch (Exception $e) {
    $response = ["success" => false, "message" => $e->getMessage()];
    sendJsonResponse($response);
}

//	function to send json response
function sendJsonResponse($sentArray)
{
    header("Content-Type: application/json");
    echo json_encode($sentArray);
}

?>
