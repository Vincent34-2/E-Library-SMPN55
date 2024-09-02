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


$koneksi->close();
?>

<!DOCTYPE html>
<link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang E-Perpus SMPN 55 Jakarta</title>
    <style>
        /* Styling untuk halaman about */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #CBDFE3;
            
            margin: 0;
            padding: 0;
            margin-bottom: 70px;
            overflow-x: hidden;
        }
        
        .bg {
  position: absolute;
  width: 500vw;
  height: 400vh;
  background-color: #CBDFE3;
  background-image: linear-gradient(315deg, #CBDFE3 0%, #CBDFE3 74%);
  z-index: -1;
  transition: 850ms;
}

.btn {
  width: 200px;
  margin-top: 70px;
  
  
  height: 50px;
  position: relative;
  -webkit-tap-highlight-color: transparent;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Dancing Script', cursive;
  font-size: 25px;
  color: #fff;
  cursor: pointer;
  background-color: #63a4ff;
  background-image: linear-gradient(315deg, #63a4ff 0%, #83eaf1 74%);
}

.blind {
  position: absolute;
  width: 10px;
  height: 0px;
  background-color: #ffffff;
  background-image: linear-gradient(315deg, #ffffff 0%, #CBDFE3 74%);
  top: 0;
}

.string {
  width: 5px;
  height: 80px;
  border-radius: 0 5px 0 0;
  background: #eee;
  position: absolute;
  right: -5px;
  top: 0;
  transition: 850ms;
}

.string-end {
  width: 8px;
  height: 12px;
  border-radius: 0 0 10px 10px;
  background: #ccc;
  position: absolute;
  right: -6.5px;
  bottom: -42px;
  transition: 850ms;
}

.btn:hover .blind {
  height: 50px;
}

.btn:hover .bg {
  filter: invert(100%) brightness(20%);
}

.btn:hover .string {
  height: 60px;
}

.btn:hover .string-end {
  bottom: -22px;
}

.noselect {
  -webkit-touch-callout: none;
    -webkit-user-select: none;
     -khtml-user-select: none;
       -moz-user-select: none;
        -ms-user-select: none;
            user-select: none;
}

#b1 {
  left: 0;
  transition: 100ms;
}

#b2 {
  left: 10px;
  transition: 150ms;
}

#b3 {
  left: 20px;
  transition: 200ms;
}

#b4 {
  left: 30px;
  transition: 250ms;
}

#b5 {
  left: 40px;
  transition: 300ms;
}

#b6 {
  left: 50px;
  transition: 350ms;
}

#b7 {
  left: 60px;
  transition: 400ms;
}

#b8 {
  left: 70px;
  transition: 450ms;
}

#b9 {
  left: 80px;
  transition: 500ms;
}

#b10 {
  left: 90px;
  transition: 550ms;
}

#b11 {
  left: 100px;
  transition: 600ms;
}

#b12 {
  left: 110px;
  transition: 650ms;
}

#b13 {
  left: 120px;
  transition: 700ms;
}

#b14 {
  left: 130px;
  transition: 750ms;
}

#b15 {
  left: 140px;
  transition: 800ms;
}

#b16 {
  left: 150px;
  transition: 850ms;
}

        .content-wrapper {
            max-width: 800px;
            margin: 80px auto 20px; /* Adjust top margin to account for the fixed navbar */
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 1s ease forwards;
        }

        .content-wrapper img {
            max-width: 25%;
            display: block;
            margin: auto;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .content-wrapper h1 {
            font-size: 2.5em;
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .content-wrapper p {
            font-size: 1.2em;
            text-align: center;
            color: #666;
            margin-bottom: 20px;
            line-height: 1.6;
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

        footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        /* Animation Keyframes */
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            0% {
                transform: translateX(-100%);
                opacity: 0;
            }
            100% {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .animated-section {
            padding: 50px;
            background-color: #fff;
            margin: 50px 0;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transform: translateX(-100%);
            animation: slideIn 1s ease forwards;
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
    <div class="btn">
  <div class="bg"></div>
  <span class="noselect">Ubah warna</span>
  <div class="string"></div>
  <div class="string-end"></div>
  <div class="blind" id="b1"></div>
  <div class="blind" id="b2"></div>
  <div class="blind" id="b3"></div>
  <div class="blind" id="b4"></div>
  <div class="blind" id="b5"></div>
  <div class="blind" id="b6"></div>
  <div class="blind" id="b7"></div>
  <div class="blind" id="b8"></div>
  <div class="blind" id="b9"></div>
  <div class="blind" id="b10"></div>
  <div class="blind" id="b11"></div>
  <div class="blind" id="b12"></div>
  <div class="blind" id="b13"></div>
  <div class="blind" id="b14"></div>
  <div class="blind" id="b15"></div>
  <div class="blind" id="b16"></div>
</div>

    <div class="content-wrapper">
        <img src="../../assets/images/smp55.png" alt="Logo SMPN 55 Jakarta">
        <h1>E-Perpus SMPN 55 Jakarta</h1>
        <p>
            E-Perpus SMPN 55 Jakarta adalah platform perpustakaan digital kami yang dirancang untuk memudahkan siswa dan staf dalam mengakses berbagai koleksi buku secara online. Kami berkomitmen untuk menyediakan sumber daya pendidikan yang lengkap dan bermanfaat untuk mendukung proses belajar mengajar di sekolah.
        </p>
        <p>
            E-Perpus SMPN 55 Jakarta menawarkan berbagai macam koleksi buku, termasuk buku pelajaran, fiksi, non-fiksi, dan banyak lagi. Dengan antarmuka yang user-friendly, kami berharap semua pengguna dapat dengan mudah menemukan dan membaca buku yang mereka butuhkan kapan saja dan di mana saja.
        </p>
        <p>
            Terima kasih telah mengunjungi E-Perpus kami. Kami berharap Anda menikmati pengalaman membaca yang menyenangkan dan produktif.
        </p>
    </div>

    <div class="animated-section">
        <h2>Fitur Unggulan</h2>
        <p>Kami menyediakan fitur pencarian yang canggih, rekomendasi buku berdasarkan minat, dan akses offline untuk buku favorit Anda.</p>
    </div>
    
    <footer class="main-footer" style="position: fixed; bottom: 0; width: 100%; background-color: #577B8D ; color: white; text-align: center; line-height: 30px; z-index: 1000;">
        <div style="float: right; margin-right: 20px;">
            <span style="font-size: 0.8em;">Versi Beta Developer</span>
        </div>
        <div style="margin-left: -600px;">
            <strong>&copy; <?= date('Y'); ?> Megalodon Team</strong> | PT. Megalodon Abadi Sejahtera
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const animatedSection = document.querySelector('.animated-section');
            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animatedSection.style.animationPlayState = 'running';
                    }
                });
            });
            observer.observe(animatedSection);
        });
    </script>
</body>
</html>
