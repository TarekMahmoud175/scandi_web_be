<?php

class DVDProduct extends Product
{
    protected $type = 'dvd';

    public function __construct($data)
    {
        parent::__construct();

        if (!empty($data->id)) {
            $this->id = $data->id;
        }

        $this->sku = $data->sku;
        $this->name = $data->name;
        $this->price = $data->price;
        $this->dvd_size_mb = $data->dvd_size_mb;
    }

    public function toArray()
    {
        $data = [
            'type' => (string) $this->type,
            'sku' => (string) $this->sku,
            'name' => (string) $this->name,
            'price' => (string) $this->price,
            'dvd_size_mb' => (string) $this->dvd_size_mb,
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
            return $validation_result;
        }

        if (empty((string) $this->dvd_size_mb)) {
            return [
                'success' => false,
                'message' => 'DVD Size is Required!',
            ];
        }

        return [
            'success' => true,
        ];
    }
}
