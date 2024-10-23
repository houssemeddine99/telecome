<?php
class Rental {
    private $conn;
    private $table_name = "rentals";

    public $id;
    public $user_id;
    public $book_id;
    public $rental_date;
    public $return_date;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addRental($user_id, $book_id, $duration) {
        $this->user_id = $user_id;
        $this->book_id = $book_id;
        $this->rental_date = date('Y-m-d');
        $this->return_date = date('Y-m-d', strtotime("+$duration days"));
        $this->status = 'Active';

        $query = "INSERT INTO " . $this->table_name . " (user_id, book_id, rental_date, return_date, status) 
                  VALUES (:user_id, :book_id, :rental_date, :return_date, :status)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':book_id', $this->book_id);
        $stmt->bindParam(':rental_date', $this->rental_date);
        $stmt->bindParam(':return_date', $this->return_date);
        $stmt->bindParam(':status', $this->status);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function getRentals($user_id, $duration = 'all') {
        $query = "SELECT *, DATEDIFF(return_date, rental_date) AS rent_duration 
                  FROM " . $this->table_name . " 
                  WHERE user_id = :user_id";

        if ($duration !== 'all') {
            $query .= " AND DATEDIFF(return_date, rental_date) = :duration";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        if ($duration !== 'all') {
            $stmt->bindParam(':duration', $duration, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function returnRental($rental_id) {
        $query = "UPDATE " . $this->table_name . " 
                  SET status = 'Returned', return_date = NOW() 
                  WHERE id = :rental_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':rental_id', $rental_id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
?>