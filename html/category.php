<?php
require 'categories.php';

$id = $_GET['id'];
$categories = new Categories();
$path = $categories->get_category_full_path($id);

foreach ($path as $key => $category) {
    echo "$category->name";
    if (!($key === count($path) - 1))
        echo ' > ';
}