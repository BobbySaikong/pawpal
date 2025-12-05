<?php
header("Access-Control-Allow-Origin: *"); // running as chrome app

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    include "dbconnect.php";

    $results_per_page = 5;
    if (isset($_GET["current_page"])) {
        $currentpage = (int) $_GET["current_page"];
    } else {
        $currentpage = 1;
    }
    $page_first_result = ($currentpage - 1) * $results_per_page;

    // Base JOIN query
    $baseQuery = "
        SELECT
            p.pet_id,
            p.pet_name,
            p.pet_type,
            p.category,
            p.description,
            p.created_at
            users.user_name
            users.user_id
            users.user_email
            users.user_phone
            users.user_regdate
            FROM tbl_users users
            JOIN tbl_pets p ON users.users_id = p.users_id
    ";

    // Search logic
    if (isset($_GET["search"]) && !empty($_GET["search"])) {
        $search = $connect->real_escape_string($_GET["search"]);
        $sqlloadpets =
            $baseQuery .
            "
            WHERE p.pet_name LIKE '%$search%'
               OR p.pet_type LIKE '%$search%'
               OR p.description '%$search%'
            ORDER BY p.pet_id DESC";
    } else {
        $sqlloadpets = $baseQuery . " ORDER BY p.pet_id DESC";
    }

    // Execute query
    $result = $connect->query($sqlloadpets);
    $number_of_result = $result->num_rows;
    $number_of_page = ceil($number_of_result / $results_per_page);

    $sqlloadpets .= " LIMIT $page_first_result, $results_per_page";
    $result = $connect->query($sqlloadpets);

    if ($result && $result->num_rows > 0) {
        $petdata = [];
        while ($row = $result->fetch_assoc()) {
            $petdata[] = $row;
        }
        $response = [
            "success" => true,
            "data" => $petdata,
            "amount_of_page" => $number_of_page,
            "amount_of_result" => $number_of_result,
        ];
        sendJsonResponse($response);
    } else {
        $response = [
            "success" => false,
            "data" => null,
            "amount_of_page" => $number_of_page,
            "amount_of_result" => $number_of_result,
        ];
        sendJsonResponse($response);
    }
} else {
    $response = ["success" => false];
    sendJsonResponse($response);
    exit();
}

function sendJsonResponse($sentArray)
{
    header("Content-Type: application/json");
    echo json_encode($sentArray);
}
?>
