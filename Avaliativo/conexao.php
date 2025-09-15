<?php
$servername = "localhost"; 
$username = "root";
$password = "";
$dbname = "AvaliAtivo";


$conn = new mysqli($servername, $username, $password, $dbname);
if (!$conn) {
    die( " Conexão falhou: " . $conn->connect_error);
}


?>