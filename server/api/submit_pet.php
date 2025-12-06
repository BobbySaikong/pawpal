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
$pettype = $_POST["pet_type"];
$petcategory = $_POST["pet_category"];
$description = addslashes($_POST["pet_description"]);
$latitude = $_POST["latitude"];
$longitude = $_POST["longitude"];
$decodedimage = base64_decode($_POST["image"]);

// Insert new pet into database
//TODO! : rectify sql error
$sqladdpet = "INSERT INTO tbl_pets (user_id, pet_name , pet_type , pet_category , description , image_paths , lat , lng)
	VALUES ('$userid','$petname','$pettype', '$petcategory','$description', '$decodedimage','$latitude','$longitude');";
try {
    //to be checked
    if ($connect->query($sqladdpet) === TRUE) {
        $filename = "../assets/uploads/" . $petname . ".png";
        file_put_contents($filename, $decodedimage);

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
    echo $e->getTraceAsString();
}

//	function to send json response
function sendJsonResponse($sentArray)
{
    header("Content-Type: application/json");
    echo json_encode($sentArray);
}
?>
