<?php
include "../../config/koneksi.php"; // Adjust the path based on your directory structure

// Get the book ID from the URL
$bookId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($bookId <= 0) {
    echo "<p>Invalid book ID.</p>";
    exit;
}

// Fetch book information based on the book ID
$query = "SELECT title, author, cover_image, description, published_year, read_link, download_link FROM books WHERE id = $bookId";
$result = mysqli_query($koneksi, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "<p>Book not found.</p>";
    exit;
}

$book = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($book['title']); ?> - Digital Library</title>
    <link rel="stylesheet" href="../../assets/dist/css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .content-wrapper {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .book-info img {
            width: 100%;
            height: auto;
        }
        .book-info {
            text-align: center;
        }
        .book-info h1 {
            font-size: 2em;
            margin-bottom: 20px;
        }
        .book-info p {
            font-size: 1.2em;
            margin-bottom: 20px;
        }
        .book-info .buttons {
            margin-top: 20px;
        }
        .book-info .buttons a {
            padding: 10px 20px;
            margin: 0 10px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <div class="book-info">
            <img src="../../assets/images/<?php echo htmlspecialchars($book['cover_image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
            <h1><?php echo htmlspecialchars($book['title']); ?></h1>
            <p>by <?php echo htmlspecialchars($book['author']); ?></p>
            <p><?php echo htmlspecialchars($book['description']); ?></p>
            <p>Published: <?php echo htmlspecialchars($book['published_year']); ?></p>
            <div class="buttons">
                <a href="<?php echo htmlspecialchars($book['read_link']); ?>" target="_blank">Read</a>
                <a href="<?php echo htmlspecialchars($book['download_link']); ?>" download>Download</a>
            </div>
        </div>
    </div>
</body>
</html>
