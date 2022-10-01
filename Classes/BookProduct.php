<?php

require_once 'Product.php';

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
        $this->setSKU($data['sku']);
        $this->setName($data['name']);
        $this->setPrice($data['price']);
        $this->setWeight($data['book_weight_kg']);
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
        $this->db->insert('products', [
            'sku' => $this->sku,
            'name' => $this->name,
            'price' => $this->price,
            'type' => $this->type,
            'book_weight_kg' => $this->weight_kg,
        ]);
    }
}
