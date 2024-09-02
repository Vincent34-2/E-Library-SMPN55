<?php
session_start();
include "../../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($koneksi, $_POST['title']);
    $author = mysqli_real_escape_string($koneksi, $_POST['author']);
    $category_id = mysqli_real_escape_string($koneksi, $_POST['category_id']);
    $description = mysqli_real_escape_string($koneksi, $_POST['description']);
    $published_year = mysqli_real_escape_string($koneksi, $_POST['published_year']);
    $download_link = mysqli_real_escape_string($koneksi, $_POST['download_link']);

    // Handle cover image upload
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
        $cover_image = $_FILES['cover_image']['name'];
        $cover_image_tmp = $_FILES['cover_image']['tmp_name'];
        $cover_upload_dir = "../../assets/images/";

        // Upload cover image
        $cover_image_path = $cover_upload_dir . basename($cover_image);
        if (move_uploaded_file($cover_image_tmp, $cover_image_path)) {
            $cover_image_relative_path = basename($cover_image);
        } else {
            echo "Failed to upload cover image.";
            exit;
        }
    } else {
        $cover_image_relative_path = "default_cover.png"; // Use a default cover image if none is provided
    }

    // Handle eBook file upload
    if (isset($_FILES['ebook_file']) && $_FILES['ebook_file']['error'] === UPLOAD_ERR_OK) {
        $ebook_file = $_FILES['ebook_file']['name'];
        $ebook_file_tmp = $_FILES['ebook_file']['tmp_name'];
        $ebook_upload_dir = "../../assets/Buku/";

        // Upload eBook file
        $ebook_file_path = $ebook_upload_dir . basename($ebook_file);
        if (move_uploaded_file($ebook_file_tmp, $ebook_file_path)) {
            $ebook_relative_path = basename($ebook_file);
        } else {
            echo "Failed to upload eBook file.";
            exit;
        }
    } else {
        echo "No eBook file uploaded.";
        exit;
    }

    // Manual ID management
    // Check for the lowest available ID
    $query = "SELECT id FROM books ORDER BY id ASC";
    $result = mysqli_query($koneksi, $query);

    $available_id = 1; // Start with the first ID
    $found_id = false;
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['id'] != $available_id) {
            // We found a gap
            $found_id = true;
            break;
        }
        $available_id++;
    }
    if (!$found_id) {
        // No gaps found, use the next highest ID
        $available_id = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT MAX(id) AS max_id FROM books"))['max_id'] + 1;
    }

    // Insert new book with the determined ID
    $query = "INSERT INTO books (id, title, author, cover_image, category_id, description, published_year, download_link, read_link) 
              VALUES ('$available_id', '$title', '$author', '$cover_image_relative_path', '$category_id', '$description', '$published_year', '$download_link', '$ebook_relative_path')";

    if (mysqli_query($koneksi, $query)) {
        $_SESSION['upload_status'] = "eBook uploaded successfully.";
        header("Location: upload_ebook.php"); // Redirect to prevent form resubmission on refresh
        exit;
    } else {
        $_SESSION['upload_status'] = "Failed to upload eBook.";
        header("Location: upload_ebook.php"); // Redirect to prevent form resubmission on refresh
        exit;
    }
}
?>
    <style>

body {
    font-family: 'Roboto', sans-serif;
    background-color: #f4f4f9;
    margin: 0;
    padding-top: 200px;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    background-color: #fff;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    max-width: 500px;
    width: 100%;
}

h2 {
    margin-bottom: 1.5rem;
    font-weight: 500;
    text-align: center;
    color: #333;
}

.form-group {
    margin-bottom: 1.5rem;
}

label {
    display: block;
    margin-bottom: 0.5rem;
    color: #666;
    font-weight: 500;
}

input[type="text"],
input[type="number"],
input[type="file"],
textarea {
    width: calc(100% - 20px);
    padding: 0.5rem 1rem;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 1rem;
    color: #333;
}

input[type="file"] {
    padding: 0.5rem 0;
}

textarea {
    resize: vertical;
    min-height: 100px;
}

.btn {
    display: block;
    width: 100%;
    padding: 0.75rem;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn:hover {
    background-color: #0056b3;
}

.back-to-dashboard {
    margin-top: 1.5rem;
    text-align: center;
}

.back-to-dashboard a {
    text-decoration: none;
    color: #007bff;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.3s ease;
}

.back-to-dashboard a:hover {
    color: #0056b3;
}

.back-to-dashboard i {
    margin-right: 0.5rem;
}
</style>


<!DOCTYPE html>

<head>
    <title>Upload eBook</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Upload eBook</h2>
        <form action="upload_ebook.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Book Title:</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="author">Author:</label>
                <input type="text" id="author" name="author" required>
            </div>
            <div class="form-group">
                <label for="category_id">Category ID:</label>
                <input type="number" id="category_id" name="category_id" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="published_year">Published Year:</label>
                <input type="number" id="published_year" name="published_year" required>
            </div>
            <div class="form-group">
                <label for="cover_image">Cover Image:</label>
                <input type="file" id="cover_image" name="cover_image" required>
            </div>
            <div class="form-group">
                <label for="ebook_file">eBook File (PDF):</label>
                <input type="file" id="ebook_file" name="ebook_file" required>
            </div>
            <div class="form-group">
                <label for="download_link">Download Link:</label>
                <input type="text" id="download_link" name="download_link" required>
            </div>
            <button type="submit" class="btn">Upload</button>
        </form>
        <div class="back-to-dashboard">
            <a href="dashboard"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
