<?php
class Database {
    public static mysqli $connection;

    public static function connect() {
        self::$connection = new mysqli('mysql', 'root', 'root', 'mydatabase');
        if (self::$connection->connect_error) {
            die('Connection failed: ' . self::$connection->connect_error);
        }
    }

    public static function get_categories() {
        self::connect();
        $query = 'SELECT * FROM categories';

        $result = self::$connection->query($query);
        $categories = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $category = new Category($row['id'], $row['name'], $row['parent_id']);
                $categories[] = $category;
            }
        }
        self::$connection->close();
        return $categories;
    }
    
    public static function update_categories(array $categories) {
        $old_categories = self::get_categories();
        $categories_to_add = array_udiff($categories, $old_categories, function($a, $b) {
            return $a->id - $b->id;
        });
        $categories_to_delete = array_udiff($old_categories, $categories, function($a, $b) {
            return $a->id - $b->id;
        });
        $categories_to_update = array_udiff($categories, $categories_to_add, function($a, $b) {
            return $a->id - $b->id;
        });
    
        foreach ($categories_to_delete as $category) {
            self::delete_category($category->id);
        }
    
        foreach ($categories_to_add as $category) {
            self::add_category($category->id, $category->name, $category->parent_id);
        }
    
        foreach ($categories_to_update as $category) {
            self::update_category($category->id, $category->name, $category->parent_id);
        }
    }

    public static function delete_category($id) {
        self::connect();
        $query = "DELETE FROM categories WHERE id=$id";
        self::$connection->query($query);
        self::$connection->close();
    }

    public static function add_category($id, $name, $parent_id) {
        self::connect();
        $parent_id = self::convert_to_sql_null($parent_id);
        $query = "INSERT INTO categories (id, name, parent_id) VALUES ($id, '$name', $parent_id)";
        self::$connection->query($query);
        self::$connection->close();
    }

    public static function update_category($id, $name, $parent_id) {
        self::connect();
        $parent_id = self::convert_to_sql_null($parent_id);
        $query = "UPDATE categories SET name='$name', parent_id=$parent_id WHERE id=$id";
        self::$connection->query($query);
        self::$connection->close();
    }

    public static function convert_to_sql_null($parent_id) {
        return $parent_id == null ? 'NULL' : $parent_id;
    }
}
