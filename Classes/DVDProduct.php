<?php

require_once 'Product.php';

class DVDProduct extends Product
{
    protected $type = 'dvd';

    protected $size_mb;

    public function setSize($size_mb)
    {
        $this->size_mb = $size_mb;
    }

    public function getSize()
    {
        return $this->size_mb;
    }

    public function construct($data)
    {
        $this->setSKU($data['sku']);
        $this->setName($data['name']);
        $this->setPrice($data['price']);
        $this->setSize($data['dvd_size_mb']);
    }

    public function validate($data)
    {
        $validation_result = $this->validateMain($data);

        if ($validation_result['success'] === false) {
            return $validation_result;
        }

        if (empty($data['dvd_size_mb'])) {
            return [
                'success' => false,
                'message' => 'SKU is Required!',
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
            'dvd_size_mb' => $this->size_mb,
        ]);
    }
}
