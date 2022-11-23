<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");


require_once 'Classes/autoload.php';

$json_string = file_get_contents('php://input');
$Reqdata = json_decode($json_string, true);

// 1. Fetch Request Data
// $data = [
//     'sku' => $Reqdata['sku'],
//     'name' => $Reqdata['name'],
//     'price' => $Reqdata['price'],
//     'type' => $Reqdata['type'],
//     'dvd_size_mb' => $Reqdata['dvd_size_mb'],
//     'book_weight_kg' => $Reqdata['book_weight_kg'],
//     'furniture_width_cm' => $Reqdata['furniture_width_cm'],
//     'furniture_height_cm' => $Reqdata['furniture_height_cm'],
//     'furniture_length_cm' => $Reqdata['furniture_length_cm'],
// ];
$data = [
    'sku' => 'sku01234',
    'name' => 'name',
    'price' => 'price',
    'type' => 'furniture',
    'dvd_size_mb' => 'dvd_size_mb',
    'book_weight_kg' => 'book_weight_kg',
    'furniture_width_cm' => '5',
    'furniture_height_cm' => '10',
    'furniture_length_cm' => '15',
];


(new Product)->checkTypeValidty($data["type"]);

$products = [
    'dvd' => 'DVDProduct',
    'book' => 'BookProduct',
    'furniture' => 'FurnitureProduct',
];

$productClass = $products[$data['type']];
$product = new $productClass((object) $data);

$validation_result = $product->validate();
// if ($validation_result['success'] === false) {
//     // Return Error
//     die(json_encode($validation_result));
// }

// 3. Insert Product
$product->insert();

die(json_encode([
    'success' => true,
    'message' => 'Product Added Successfully!',
]));
