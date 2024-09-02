<?php
session_start();
include "../../config/koneksi.php";

// Check if the form is submitted
if (isset($_POST['namaKategori'])) {
    // Get the category name from the form and escape it to prevent SQL injection
    $namaKategori = mysqli_real_escape_string($koneksi, $_POST['namaKategori']);
    
    // Insert the new category into the database
    $query = "INSERT INTO categories (name) VALUES ('$namaKategori')";
    
    // Check if the query was successful
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['berhasil'] = "Kategori berhasil ditambahkan.";
    } else {
        $_SESSION['gagal'] = "Gagal menambahkan kategori.";
    }
    
    // Redirect to the list of eBooks page
    header("Location: ../list_ebooks.php");
    exit();
} else {
    $_SESSION['gagal'] = "Nama kategori tidak boleh kosong.";
    header("Location: ../list_ebooks.php");
    exit();
}
?>
