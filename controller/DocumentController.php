<?php

include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/documents.php';

class DocumentController {
    private $document;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->document = new Document($db);
    }

    public function handleRequest() {
        $action = isset($_GET['action']) ? $_GET['action'] : '';
        switch ($action) {
            case 'create':
                $this->create();
                break;
            case 'delete':
                $this->delete();
                break;
            case 'delete':
                $this->update();
                break;
            default:
                // Handle other actions or show a default view
                break;
        }
    }

    private function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->document->title = $_POST['title'];
            $this->document->author = $_POST['author'];
            $this->document->year = $_POST['year'];
            $this->document->category = $_POST['category'];

            if ($this->document->create()) {
                echo "Document added successfully.";
            } else {
                echo "Failed to add document.";
            }
        }
        header('Location: ../views/templates/niceadmin/tables-general.php');
    }

    private function delete() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->document->id = $_POST['id'];

            if ($this->document->delete()) {
                echo "Document deleted successfully.";
            } else {
                echo "Failed to delete document.";
            }
        }
        require '../views/delete.php';
    }

    private function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->document->id = $_POST['id'];
            $this->document->title = $_POST['title'];
            $this->document->author = $_POST['author'];
            $this->document->year = $_POST['year'];
            $this->document->category = $_POST['category'];

            if ($this->document->update()) {
                echo "Document updated successfully.";
            } else {
                echo "Failed to update document.";
            }
        }
        require '../views/update.php';
    }
}

$controller = new DocumentController();
$controller->handleRequest();
?>