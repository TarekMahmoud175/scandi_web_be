<?php

require_once 'Classes/DB.php';

class Product
{
    protected $id;

    protected $sku;

    protected $name;

    protected $price;

    protected $db;

    public function __construct()
    {
        $this->db = new DB;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setSKU($sku)
    {
        $this->sku = $sku;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getSKU()
    {
        return $this->sku;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function validateMain($data)
    {
        if (empty($data['sku'])) {
            return [
                'success' => false,
                'message' => 'SKU is Required!',
            ];
        }

        $result = $this->db->checkExisitance("products", "sku", $data['sku']);
        if (count($result) > 0) {
            return [
                'success' => false,
                'message' => 'This Sku exist in our database before',
            ];
        }

        if (empty($data['name'])) {
            return [
                'success' => false,
                'message' => 'Name is Required!',
            ];
        }

        if (empty($data['price'])) {
            return [
                'success' => false,
                'message' => 'Price is Required!',
            ];
        }

        if (empty($data['type'])) {
            return [
                'success' => false,
                'message' => 'Type is Required!',
            ];
        }

        return [
            'success' => true,
            'message' => 'Type is Required!',
        ];
    }

    public function getAll()
    {
        $result = $this->db->select('products');

        if (count($result) > 0) {
            $products = [];
            $product_classes = [
                'dvd' => 'DVDProduct',
                'book' => 'BookProduct',
                'furniture' => 'FurnitureProduct',
            ];
            foreach ($result as $_product) {
                $product = new $product_classes[$_product->type];

                $product->construct($_product);

                $products[] = $product->toArray();
            }
            return $products;
        }

        return [];
    }

    public function delete($ids)
    {
        return $this->db->delete('products', $ids);
    }
}
