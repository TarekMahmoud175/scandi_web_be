<?php

class Product extends DataModel
{
    protected $tableName = 'products';

    public function __construct()
    {
        parent::__construct();

        $this->data['type'] = $this->type;
    }

    public function validateMain()
    {
        if (empty((string) $this->sku)) {
            return [
                'success' => false,
                'message' => 'SKU is Required!',
            ];
        }

        $result = $this->retrieveByField('sku', (string) $this->sku);
        if (count($result) > 0) {
            return [
                'success' => false,
                'message' => 'This Sku exist in our database before',
            ];
        }

        if (empty((string) $this->name)) {
            return [
                'success' => false,
                'message' => 'Name is Required!',
            ];
        }

        if (empty((string) $this->price)) {
            return [
                'success' => false,
                'message' => 'Price is Required!',
            ];
        }

        if (empty((string) $this->type)) {
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
}
