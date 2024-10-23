<?php
class Document {
    private $conn;
    private $table_name = "documents";

    public $id;
    public $title;
    public $author;
    public $year;
    public $category;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Méthodes CRUD
    // Create
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET title=:title, author=:author, year=:year, category=:category";
        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->year = htmlspecialchars(strip_tags($this->year));
        $this->category = htmlspecialchars(strip_tags($this->category));

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":author", $this->author);
        $stmt->bindParam(":year", $this->year);
        $stmt->bindParam(":category", $this->category);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Read
    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Update
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET title=:title, author=:author, year=:year, category=:category WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->year = htmlspecialchars(strip_tags($this->year));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":author", $this->author);
        $stmt->bindParam(":year", $this->year);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
