<?php
session_start();
include "../../config/koneksi.php";

// Log to check if we receive POST request
error_log("Received a request to delete a book");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        $delete_id = mysqli_real_escape_string($koneksi, $_POST['delete_id']);
        
        // Log to check the delete_id value
        error_log("Delete ID: " . $delete_id);

        // Hapus buku dari database
        $query = "DELETE FROM books WHERE id = '$delete_id'";
        if (mysqli_query($koneksi, $query)) {
            echo json_encode(['status' => 'success', 'message' => 'eBook deleted successfully.']);
        } else {
            // Log the database error for debugging
            error_log("Database Error: " . mysqli_error($koneksi));
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete eBook.']);
        }
    } else {
        error_log("Invalid request: delete_id not set");
        echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
    }
} else {
    error_log("Invalid request method");
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
