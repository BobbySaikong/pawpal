<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "pawpal_db";
    //Create connection
    $connect = new mysqli($servername, $username, $password, $dbname);
    //Check connection(if unsuccessful, eror displayed)
    if( $connect->connect_error ) {
        die("Connection failed". $connect->connect_error);}
    ?>