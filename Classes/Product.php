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
    }

    public function getAll()
    {
        return $this->db->select('products');
    }

    public function delete($ids)
    {
        return $this->db->delete('products', $ids);
    }
}
