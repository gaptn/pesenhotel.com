<?php
// File: admin/hotel_delete.php
include 'session_check.php';

$hotel_id = isset($_GET['id']) ? $_GET['id'] : 0;

if ($hotel_id > 0) {
    $query = "DELETE FROM db_hotel WHERE id = $hotel_id";
    mysqli_query($conn, $query);
}

header('Location: hotel_list.php');
exit;
?>