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
    public function print(int $level = 0) {
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

    public function print_tree($parent_id = 0, int $level = 0) {
        foreach ($this->categories as $index => $category) {
            if ($category->parent_id == $parent_id) {
                $category->print($level);
                unset($this->categories[$index]);
                if ($this->has_child_category($category->id))
                    $this->print_tree($category->id, $level + 1);
            }
        }
    }
    
    public function has_child_category(int $id) {
        foreach ($this->categories as $category) {
            if ($category->parent_id == $id)
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
        $max_parent_id = convert_null_to_zero($this->get_max_parent_id());
        $parent_id = convert_zero_to_null(rand(0, $max_parent_id + 1));
        if (count($this->categories) === 0)
            $parent_id = null;
        $name = "Категория {$this->get_category_number($id, $parent_id)}";
        array_push($this->categories, new Category($id, $name, $parent_id));
    }

    public function get_category_full_path(int $id) {
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

    public function get_category_number($id, $parent_id) {
        $number = '';
        $categories = array_filter($this->categories, 
        function ($category) use ($parent_id) {
            return $category->parent_id === $parent_id;
        });
        $current_level_number = count($categories) + 1;
        $parent_category = $this->get_category_by_id($parent_id);
        if ($parent_category) {
            $parent_number = str_replace('Категория', '', $parent_category->name);
            $number = "{$parent_number}-{$current_level_number}";
        }
        else
            $number = $current_level_number;
        return $number;
    }
}

function convert_null_to_zero($number) {
    return $number === null ? 0 : $number;
}

function convert_zero_to_null($number) {
    return $number === 0 ? null : $number;
}
