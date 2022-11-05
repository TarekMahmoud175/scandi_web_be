<?php

class BookProduct extends Product
{
    protected $type = 'book';

    public function __construct($data)
    {
        parent::__construct();

        if (!empty($data->id)) {
            $this->id = $data->id;
        }

        $this->sku = $data->sku;
        $this->name = $data->name;
        $this->price = $data->price;
        $this->book_weight_kg = $data->book_weight_kg;
    }

    public function toArray()
    {
        $data = [
            'type' => (string) $this->type,
            'sku' => (string) $this->sku,
            'name' => (string) $this->name,
            'price' => (string) $this->price,
            'book_weight_kg' => (string) $this->book_weight_kg,
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

        if (empty((string) $this->book_weight_kg)) {
            die(json_encode([
                'success' => false,
                'message' => 'Weight is Required!',
            ]));
        }

        return [
            'success' => true,
        ];
    }
}
