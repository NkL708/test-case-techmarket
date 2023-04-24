<?php
include "database.php";
class Category {
    public int $id;
    public string $name;
    public ?int $parent_id;

    public function __construct(int $id, string $name, ?int $parent_id) {
        $this->id = $id;
        $this->name = $name;
        $this->parent_id = $parent_id;
    }
    public function print($level = 0) {
        echo "<p>" . str_repeat("&nbsp;", $level * 4) . "– " . $this->name . "</p>";
    }
}

class Categories {
    public array $categories;

    public function __construct() {
        $this->categories = Database::get_categories();
    }

    public function print_tree($parent_id = 0, $level = 0) {
        foreach ($this->categories as $index => $category) {
            if ($category->parent_id == $parent_id) {
                $category->print($level);
                unset($this->categories[$index]);
                if ($this->has_child_category($category->id)) 
                    $this->print_tree($category->id, $level + 1);
            }
        }
    }
    
    public function has_child_category($category_id) {
        foreach ($this->categories as $category) {
            if ($category->parent_id == $category_id)
                return true;
        }
        return false;
    }
    private function get_max_parent_id() {
        $max = null;
        foreach ($this->categories as $category) {
            if ($category->parent_id > $max)
                $max = $category->parent_id;
        }
        return $max;
    }

    public function add_random_category() {
        $id = count($this->categories) + 1;
        $name = "Категория " . $id;
        $parent_id = $this->get_max_parent_id() == null 
        ? null : rand(1, $this->get_max_parent_id());
        array_push($this->categories, new Category($id, $name, $parent_id));
    }

    public function delete_all_categories() {
        $this->categories = array();
    }
}

$categories = new Categories();

if (isset($_POST["add_one"])) {
    $categories->add_random_category($categories);
    Database::update_categories($categories->categories);
}

if (isset($_POST["add_many"])) {
    for ($i = 0; $i < 5000; $i++) {
        $categories->add_random_category($categories);
    }
    Database::update_categories($categories->categories);
}

if (isset($_POST["delete_many"])) {
    $categories->delete_all_categories($categories);
    Database::update_categories($categories->categories);
}

$categories->print_tree();