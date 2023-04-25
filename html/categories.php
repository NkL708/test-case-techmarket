<?php
require 'database.php';
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
        $indent = str_repeat("&nbsp;", $level * 4);
        echo "<p>$indent – <a href=\"category.php?id=$this->id\">$this->name</a></p>";
    }

    public function has_parent() {
        return $this->parent_id !== null ? true : false;
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
        $name = "Категория $id";
        $max_parent_id = convert_null_to_zero($this->get_max_parent_id());
        $parent_id = convert_zero_to_null(rand(0, $max_parent_id + 1));
        if (count($this->categories) === 0)
            $parent_id = null;
        array_push($this->categories, new Category($id, $name, $parent_id));
    }

    public function get_category_full_path($id) {
        $path = array();
        $category = $this->get_category_by_id($id);
        array_push($path, $category);
        if ($category->has_parent()) {
            $parent_path = $this->get_category_full_path($category->parent_id);
            $path = array_merge($parent_path, $path);
        }
        return $path;
    }

    public function get_category_by_id($id) {
        foreach ($this->categories as $category) {
            if ($category->id == $id) {
                return $category;
            }
        }
        return null;
    }

    public function delete_all_categories() {
        $this->categories = array();
    }
}

function convert_null_to_zero($number) {
    return $number === null ? 0 : $number;
}

function convert_zero_to_null($number) {
    return $number === 0 ? null : $number;
}
