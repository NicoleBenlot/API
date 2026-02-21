<?php
// Try 'localhost' first; it's faster and more reliable for internal scripts
$servername = "johnny.heliohost.org"; 
$username = "sphinx0945_practiceaccount";
$password = "practice12311";
$dbname = "sphinx0945_practice";
$port = 3306;

// Use this for better error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($servername, $username, $password, $dbname, $port);
} catch (mysqli_sql_exception $e) {
    header('Content-Type: application/json');
    echo json_encode(["error" => "Connection failed: " . $e->getMessage()]);
    exit;
}
?>
