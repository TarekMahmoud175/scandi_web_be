<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

require_once 'Classes/autoload.php';

$products = (new Product)->all();

die(json_encode([
    'products' => $products
]));
