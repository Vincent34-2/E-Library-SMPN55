<?php
require_once __DIR__ . '/../../config/koneksi.php';
session_start();
$user = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'Tamu';

// Mendapatkan informasi profil pengguna dari database
$queryUser = "SELECT profile_pic FROM user WHERE fullname = ?";
$stmtUser = $koneksi->prepare($queryUser);
$stmtUser->bind_param('s', $user);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();
$userData = $resultUser->fetch_assoc();
$profilePic = isset($userData['profile_pic']) ? $userData['profile_pic'] : 'default_profile.png';

// Query untuk menghitung jumlah buku
$query = "SELECT COUNT(*) as total_books FROM books";
$result = $koneksi->query($query);

if ($result) {
    $row = $result->fetch_assoc();
    $totalBooks = $row['total_books'];
} else {
    $totalBooks = 0;
}

// Tidak menutup koneksi di sini, lanjutkan ke query berikutnya

// Handle search query
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchSQL = '';
if (!empty($searchQuery)) {
    $searchSQL = " WHERE title LIKE ? OR author LIKE ? OR published_year LIKE ?";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku - Digital Library</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../../assets/dist/css/styles.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #CBDFE3;
            margin: 0;
            padding: 0;
            padding-top: 60px; /* Adjust based on the height of your navbar */
            padding-bottom: 60px; /* Adjust based on the height of your footer */
        }
        .navbar {
            background-color: #577B8D;
            line-height: 30px;
            color: white;
            padding: 10px 20px;
            text-align: left;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000; /* Ensure the navbar is on top */
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 0 15px;
            font-weight: normal;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        .navbar .left {
            display: flex;
            align-items: center;
        }
        .navbar img {
            max-height: 25px;
            max-width: 25px;
            margin-right: 0px;
        }
        .navbar .right {
            display: flex;
            align-items: center;
        }
        .navbar .right img {
            max-height: 35px;
            border-radius: 50%;
            margin-left: 10px;
        }
        .footer {
            background-color: #005C78;
            color: white;
            padding: 15px;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
            z-index: 1000;
        }
        .content-wrapper {
            max-width: 800px;
            margin: 80px auto 80px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .content-wrapper h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .search-form {
            text-align: center;
            margin-bottom: 20px;
        }
        .search-form input[type="text"] {
            padding: 10px;
            width: 80%;
            max-width: 400px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .search-form button {
            padding: 10px 20px;
            border: none;
            background-color: #577B8D;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        .book-count {
            text-align: center;
            font-size: 1.2em;
            margin-bottom: 20px;
        }
        .book-list {
            list-style-type: none;
            padding: 0;
        }
        .book-list li {
            background-color: #f9f9f9;
            margin: 10px 0;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
        }
        .book-list img {
            max-width: 80px;
            margin-right: 15px;
            border-radius: 4px;
        }
        .book-list .book-info {
            flex: 1;
        }
    </style>
</head>
<body>
<div class="navbar">
        <div class="left">
            <img src="../../assets/images/smp55.png" alt="Logo SMPN 55 Jakarta">
            <a href="index.php">Home</a>
            <a href="books.php">Daftar E-Book</a>
            <a href="categories.php">Kategori</a>
            <a href="about.php">Tentang</a>
        </div>
        <div class="right" style="margin-right: 30px;">
            <?= htmlspecialchars($user); ?></span>
            <img src="../../assets/images/profil/<?= htmlspecialchars($profilePic); ?>" alt="Foto Profil">
        </div>
    </div>
    <div class="content-wrapper">
        <h1>Daftar Buku</h1>
        <div class="search-form">
            <form action="books.php" method="get">
                <input type="text" name="search" placeholder="Cari buku berdasarkan judul, penulis, atau penerbit" value="<?= htmlspecialchars($searchQuery); ?>">
                <button type="submit">Cari</button>
            </form>
        </div>
        <div class="book-count">
            Total Buku: <?= $totalBooks; ?>
        </div>
        <ul class="book-list">
            <?php
            // Query untuk mengambil daftar buku beserta gambar sampul
            $query = "SELECT id, title, author, cover_image FROM books" . $searchSQL;
            $stmt = $koneksi->prepare($query);

            if (!empty($searchSQL)) {
                $likeQuery = '%' . $searchQuery . '%';
                $stmt->bind_param('sss', $likeQuery, $likeQuery, $likeQuery);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<li>';
                    echo '<img src="../../assets/images/' . htmlspecialchars($row['cover_image']) . '" alt="' . htmlspecialchars($row['title']) . '">';
                    echo '<div class="book-info">';
                    echo '<a href="book_info.php?id=' . $row['id'] . '">' . htmlspecialchars($row['title']) . '</a> oleh ' . htmlspecialchars($row['author']);
                    echo '</div>';
                    echo '</li>';
                }
            } else {
                echo '<li>Tidak ada buku yang ditemukan.</li>';
            }

            $koneksi->close();
            ?>
        </ul>
    </div>
     <!-- Footer starts here -->
     <footer class="main-footer" style="position: fixed; bottom: 0; width: 100%; background-color: #577B8D; color: white; text-align: center; line-height: 30px; z-index: 1000;">
        <div style="float: right; margin-right: 20px;">
            <span style="font-size: 0.8em;">Versi Beta Developer</span>
        </div>
        <div style="margin-left: -600px;">
            <strong>&copy; <?= date('Y'); ?> Megalodon Team</strong> | PT. Megalodon Abadi Sejahtera
        </div>
    </footer>
</body>
</html>
