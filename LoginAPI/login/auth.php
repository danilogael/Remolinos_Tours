<?php
header('Content-Type: application/json');
session_start();

if(!isset($_SESSION['user_id'])) { 
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}
?>