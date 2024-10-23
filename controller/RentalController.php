<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/rental.php';

class RentalController {
    private $db;
    private $rental;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->rental = new Rental($this->db);
    }

    public function addRental($user_id, $book_id, $duration) {
        return $this->rental->addRental($user_id, $book_id, $duration);
    }

    public function getRentals($user_id, $duration = 'all', $status = '', $sort_by = 'rental_date', $sort_order = 'DESC') {
        $query = "SELECT id, book_id, rental_date, return_date, status FROM rentals WHERE user_id = :user_id";
        
        if ($duration != 'all') {
            $query .= " AND DATEDIFF(return_date, rental_date) = :duration";
        }
        
        if ($status != '') {
            $query .= " AND status = :status";
        }
        
        $query .= " ORDER BY $sort_by $sort_order";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        
        if ($duration != 'all') {
            $stmt->bindParam(':duration', $duration);
        }
        
        if ($status != '') {
            $stmt->bindParam(':status', $status);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAllRentals() {
        $query = "SELECT id, book_id, rental_date, return_date, status FROM rentals ORDER BY rental_date DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateExpiredRentals() {
        $currentDate = date('Y-m-d');
        $query = "UPDATE rentals SET status = 'Returned' WHERE return_date < :currentDate AND status = 'Active'";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':currentDate', $currentDate);
        $stmt->execute();
    }
    public function returnRental($rental_id) {
        return $this->rental->returnRental($rental_id);
    }
}
?>