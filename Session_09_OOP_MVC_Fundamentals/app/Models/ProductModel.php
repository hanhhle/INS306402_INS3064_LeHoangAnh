<?php

class ProductModel extends Model 
{
    public function validate($data): bool 
    {
        // Validation rule: Name cannot be empty and price must be greater than 0
        if (empty($data['name']) || $data['price'] <= 0) {
            return false;
        }
        return true;
    }
}S