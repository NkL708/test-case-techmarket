<?php
require 'categories.php';

$categories = new Categories();

if (isset($_POST['add_one'])) {
    $categories->add_random_category($categories);
    Database::update_categories($categories->categories);
    header("Location: index.php");
}

if (isset($_POST['add_many'])) {
    for ($i = 0; $i < 5000; $i++) {
        $categories->add_random_category($categories);
    }
    Database::update_categories($categories->categories);
    header("Location: index.php");
}

if (isset($_POST['delete_many'])) {
    $categories->delete_all_categories($categories);
    Database::update_categories($categories->categories);
    header("Location: index.php");
}

$categories->print_tree();