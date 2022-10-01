<?php

require_once 'Product.php';

class FurnitureProduct extends Product
{
    protected $type = 'furniture';

    protected $dimensions_width_cm;

    protected $dimensions_height_cm;

    protected $dimensions_length_cm;

    public function setWidth($dimensions_width_cm)
    {
        $this->dimensions_width_cm = $dimensions_width_cm;
    }

    public function getWidth()
    {
        return $this->dimensions_width_cm;
    }

    public function setHeight($dimensions_height_cm)
    {
        $this->dimensions_height_cm = $dimensions_height_cm;
    }

    public function getHeight()
    {
        return $this->dimensions_height_cm;
    }

    public function setLength($dimensions_length_cm)
    {
        $this->dimensions_length_cm = $dimensions_length_cm;
    }

    public function getLength()
    {
        return $this->dimensions_length_cm;
    }

    public function construct($data)
    {
        $this->setSKU($data['sku']);
        $this->setName($data['name']);
        $this->setPrice($data['price']);
        $this->setWidth($data['furniture_width_cm']);
        $this->setHeight($data['furniture_height_cm']);
        $this->setLength($data['furniture_length_cm']);
    }

    public function validate($data)
    {
        $validation_result = $this->validateMain($data);

        if ($validation_result['success'] === false) {
            return $validation_result;
        }

        if (empty($data['furniture_width_cm'])) {
            return [
                'success' => false,
                'message' => 'Width is Required!',
            ];
        }

        if (empty($data['furniture_height_cm'])) {
            return [
                'success' => false,
                'message' => 'Height is Required!',
            ];
        }

        if (empty($data['furniture_length_cm'])) {
            return [
                'success' => false,
                'message' => 'Length is Required!',
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
            'furniture_width_cm' => $this->dimensions_width_cm,
            'furniture_height_cm' => $this->dimensions_height_cm,
            'furniture_length_cm' => $this->dimensions_length_cm,
        ]);
    }
}
