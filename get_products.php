<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

require_once 'Classes/Product.php';

$product = new Product;

$products = $product->getAll();

die(json_encode([
    'products' => $products
]));
