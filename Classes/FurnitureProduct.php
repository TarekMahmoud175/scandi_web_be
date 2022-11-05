<?php

class FurnitureProduct extends Product
{
    protected $type = 'furniture';

    public function __construct($data)
    {
        parent::__construct();

        if (!empty($data->id)) {
            $this->id = $data->id;
        }

        $this->sku = $data->sku;
        $this->name = $data->name;
        $this->price = $data->price;
        $this->furniture_width_cm = $data->furniture_width_cm;
        $this->furniture_height_cm = $data->furniture_height_cm;
        $this->furniture_length_cm = $data->furniture_length_cm;
    }

    public function toArray()
    {
        $data = [
            'type' => (string) $this->type,
            'sku' => (string) $this->sku,
            'name' => (string) $this->name,
            'price' => (string) $this->price,
            'furniture_width_cm' => (string) $this->furniture_width_cm,
            'furniture_height_cm' => (string) $this->furniture_height_cm,
            'furniture_length_cm' => (string) $this->furniture_length_cm,
        ];

        if (!empty((string) $this->id)) {
            $data['id'] = (string) $this->id;
        }

        return $data;
    }

    public function validate()
    {
        $validation_result = $this->validateMain();

        if ($validation_result['success'] === false) {
            die(json_encode($validation_result));
        }

        if (empty((string) $this->furniture_width_cm)) {
            die(json_encode([
                'success' => false,
                'message' => 'Width is Required!',
            ]));
        }

        if (empty((string) $this->furniture_height_cm)) {
            die(json_encode([
                'success' => false,
                'message' => 'Height is Required!',
            ]));
        }

        if (empty((string) $this->furniture_length_cm)) {
            die(json_encode([
                'success' => false,
                'message' => 'Length is Required!',
            ]));
        }

        return [
            'success' => true,
        ];
    }
}
