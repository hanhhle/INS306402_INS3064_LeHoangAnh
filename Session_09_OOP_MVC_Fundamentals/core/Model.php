<?php

abstract class Model 
{
    protected $db;

    public function __construct() 
    {
        // Placeholder for real database connection (e.g., new PDO)
        // $this->db = ... 
    }

    // Shared method for all child classes
    public function all() 
    {
        return "Fetching all records from the database...";
    }

    // Forces child classes to implement their own validation logic
    abstract public function validate($data): bool;
}