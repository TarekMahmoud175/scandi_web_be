<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

require_once 'Classes/autoload.php';

$json_string = file_get_contents('php://input');
$data = json_decode($json_string);
$ids = $data->ids;

(new Product)->bulkDeleteByIDs($ids);

die(json_encode([
    'success' => true,
    'message' => 'Deleted successfully!'
]));
