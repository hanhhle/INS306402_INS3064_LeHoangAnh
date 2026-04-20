<?php

class ProductController 
{
    public function index() 
    {
        $model = new ProductModel();
        $data = $model->all();
        
        echo "<h1>Product List</h1>";
        echo "<p>System Status: " . $data . "</p>";
    }

    // Shows form to create a new product
    public function create() 
    {
        echo "<h1>Create New Product</h1>";
        echo "<p>Displaying the creation form here...</p>";
    }

    // Shows form to edit an existing product
    public function edit($id) 
    {
        echo "<h1>Edit Product</h1>";
        echo "<p>Displaying edit form for Product ID: " . $id . "</p>";
    }

    // Deletes an existing product
    public function delete($id) 
    {
        echo "<h1>Delete Product</h1>";
        echo "<p>Successfully deleted Product ID: " . $id . "</p>";
    }
}