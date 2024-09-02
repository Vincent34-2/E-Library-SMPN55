<?php
include "../../config/koneksi.php";

// Check connection
if (!$koneksi) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch books along with their category name
$query = "SELECT books.id, books.title, books.author, books.cover_image, books.description, books.published_year, categories.name as category_name 
          FROM books 
          JOIN categories ON books.category_id = categories.id";

$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) > 0) {
    while ($book = mysqli_fetch_assoc($result)) {
        // Convert category name to lowercase and replace spaces with hyphens
        $category_class = strtolower(str_replace(' ', '-', $book['category_name']));
        
        echo '<div class="book ' . htmlspecialchars($category_class) . '">';
        echo '<a href="book_info.php?id=' . htmlspecialchars($book['id']) . '" class="book-link" data-id="' . htmlspecialchars($book['id']) . '">';
        echo '<img src="../../assets/images/' . htmlspecialchars($book['cover_image']) . '" alt="Book Cover">';
        echo '<div class="book-title">' . htmlspecialchars($book['title']) . '</div>';
        echo '<div class="book-author">' . htmlspecialchars($book['author']) . '</div>';
        echo '<div class="book-category">' . htmlspecialchars($book['category_name']) . '</div>';
        echo '<div class="book-description">' . htmlspecialchars($book['description']) . '</div>';
        echo '<div class="book-published-year">Published: ' . htmlspecialchars($book['published_year']) . '</div>';
        echo '</a>';
        echo '</div>';
    }
} else {
    echo '<p>No books available in the library.</p>';
}

mysqli_close($koneksi);
?>
