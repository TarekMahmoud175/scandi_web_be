<?php

require_once 'Product.php';

require_once 'Classes/DVDProduct.php';
require_once 'Classes/BookProduct.php';
require_once 'Classes/FurnitureProduct.php';


class BookProduct extends Product
{
    protected $type = 'book';

    protected $weight_kg;

    public function setWeight($weight_kg)
    {
        $this->weight_kg = $weight_kg;
    }

    public function getWeight()
    {
        return $this->weight_kg;
    }

    public function construct($data)
    {
        if (isset($data->id)) {
            $this->setId($data->id);
        }

        $this->setSKU($data->sku);
        $this->setName($data->name);
        $this->setPrice($data->price);
        $this->setWeight($data->book_weight_kg);
    }

    public function toArray()
    {
        $data = [
            'type' => $this->type,
            'sku' => $this->getSKU(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'book_weight_kg' => $this->getWeight(),
        ];

        if (!empty($id = $this->getId())) {
            $data['id'] = $id;
        }

        return $data;
    }

    public function validate($data)
    {
        $validation_result = $this->validateMain($data);

        if ($validation_result['success'] === false) {
            return $validation_result;
        }

        if (empty($data['book_weight_kg'])) {
            return [
                'success' => false,
                'message' => 'Weight is Required!',
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function save()
    {
        $this->db->insert('products', $this->toArray());
    }
}
