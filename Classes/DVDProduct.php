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
        if (isset($data->id)) {
            $this->setId($data->id);
        }

        $this->setSKU($data->sku);
        $this->setName($data->name);
        $this->setPrice($data->price);
        $this->setSize($data->dvd_size_mb);
    }

    public function toArray()
    {
        $data = [
            'type' => $this->type,
            'sku' => $this->getSKU(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'dvd_size_mb' => $this->getSize(),
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
        $this->db->insert('products', $this->toArray());
    }
}
