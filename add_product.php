<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

require_once 'Classes/DVDProduct.php';
require_once 'Classes/BookProduct.php';
require_once 'Classes/FurnitureProduct.php';

$json_string = file_get_contents('php://input');
$Reqdata = json_decode($json_string, true);


// 1. Fetch Request Data
$data = [
    'sku' => $Reqdata['sku'],
    'name' => $Reqdata['name'],
    'price' => $Reqdata['price'],
    'type' => $Reqdata['type'],
    'dvd_size_mb' => $Reqdata['dvd_size_mb'],
    'book_weight_kg' => $Reqdata['book_weight_kg'],
    'furniture_width_cm' => $Reqdata['furniture_width_cm'],
    'furniture_height_cm' => $Reqdata['furniture_height_cm'],
    'furniture_length_cm' => $Reqdata['furniture_length_cm'],
];


// 2. Validate Request Data
if (!in_array($data['type'], ['dvd', 'book', 'furniture'])) {
    // Return Error
    die(json_encode([
        'success' => false,
        'message' => 'Type is invalid!',
    ]));
}

$products = [
    'dvd' => 'DVDProduct',
    'book' => 'BookProduct',
    'furniture' => 'FurnitureProduct',
];

$productClass = $products[$data['type']];
$product = new $productClass();
$validation_result = $product->validate($data);
if ($validation_result['success'] === false) {
    // Return Error
    die(json_encode($validation_result));
}

// 3. Insert Product
$product->construct($data);
$product->save();

die(json_encode([
    'success' => true,
    'message' => 'Product Added Successfully!',
]));
