<?php
require_once __DIR__ . '/../../config/koneksi.php';

// Mulai sesi untuk mendapatkan informasi pengguna yang masuk
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

$category_id = filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT);

if (!$category_id) {
    echo "Invalid category ID";
    exit;
}

$query = "SELECT id, title, author, cover_image FROM books WHERE category_id = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param('i', $category_id);
$stmt->execute();
$result = $stmt->get_result();

$books = [];
while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

$stmt->close();
$koneksi->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku dalam Kategori</title>
    <style>
        /* Styling untuk halaman */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #CBDFE3;
            margin: 0;
            padding: 0;
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
        .content-wrapper {
            max-width: 800px;
            margin: 60px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .books {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .book {
            text-align: center;
            padding: 15px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .book:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        .book img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .book h2 {
            margin-bottom: 5px;
            font-size: 18px;
            color: #333;
        }

        .book p {
            margin: 0;
            font-size: 14px;
            color: #666;
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
    </style>
   
</head>
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
<body>

   
    
    <div class="content-wrapper">
        <h1 style="text-align: center;">Buku dalam Kategori</h1>
        <div class="books">
            <?php foreach ($books as $book): ?>
                <div class="book">
                    <a href="book_info.php?id=<?= $book['id'] ?>">
                        <img src="../../assets/images/<?= htmlspecialchars($book['cover_image']) ?>" alt="<?= htmlspecialchars($book['title']) ?>">
                        <h2><?= htmlspecialchars($book['title']) ?></h2>
                        <p>Penulis: <?= htmlspecialchars($book['author']) ?></p>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

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
