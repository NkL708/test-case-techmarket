<?php
class Category {
    public $id;
    public $name;
    public $parent_id;

    public function __construct($id, $name, $parent_id) {
        $this->id = $id;
        $this->name = $name;
        $this->parent_id = $parent_id;
    }
    public function print($level = 0) {
        echo "<p>" . str_repeat("&nbsp;", $level * 4) . "– " . $this->name . "</p>";
    }
}

function print_category_recur($categories, $parent_id = null, $level = 0) {
    foreach ($categories as $index => $category) {
        if ($category->parent_id == $parent_id) {
            $category->print($level);
            unset($categories[$index]);
            if (has_child_category($categories, $category->id)) 
                print_category_recur($categories, $category->id, $level + 1);
        }
    }
}

function has_child_category($categories, $category_id) {
    foreach ($categories as $category) {
        if ($category->parent_id == $category_id)
            return true;
    }
    return false;
}

function get_categories() {
    $connection = new mysqli('mysql', 'root', 'root', 'tree');
    $query = "SELECT * FROM categories";
    $result = mysqli_query($connection, $query);

    $categories = array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $category = new Category($row["id"], $row["name"], $row["parent_id"]);
            $categories[] = $category;
        }
    }
    mysqli_free_result($result);
    mysqli_close($connection);
    return $categories;
}

$categories = get_categories();
print_category_recur($categories);
