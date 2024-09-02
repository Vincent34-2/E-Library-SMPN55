<?php
session_start();
include "../../config/koneksi.php";

// Handle Tambah Kategori
if (isset($_POST['tambahKategori'])) {
    $namaKategori = mysqli_real_escape_string($koneksi, $_POST['namaKategori']);
    $query = "INSERT INTO categories (name) VALUES ('$namaKategori')";
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['berhasil'] = "Kategori berhasil ditambahkan.";
    } else {
        $_SESSION['gagal'] = "Gagal menambahkan kategori: " . mysqli_error($koneksi);
    }
    header("Location: list_ebooks.php");
    exit();
}

// Handle Kategori Deletion
if (isset($_GET['delete_kategori_id'])) {
    $delete_id = mysqli_real_escape_string($koneksi, $_GET['delete_kategori_id']);
    $query = "DELETE FROM categories WHERE id = '$delete_id'";
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['berhasil'] = "Kategori berhasil dihapus.";
    } else {
        $_SESSION['gagal'] = "Gagal menghapus kategori: " . mysqli_error($koneksi);
    }
    header("Location: list_ebooks.php");
    exit();
}

// Handle eBook Deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Prepare statement to prevent SQL injection
    $stmt = $koneksi->prepare("DELETE FROM books WHERE id = ?");
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        $_SESSION['berhasil'] = "eBook berhasil dihapus.";
    } else {
        $_SESSION['gagal'] = "Gagal menghapus eBook: " . $stmt->error;
    }

    $stmt->close();
    header("Location: list_ebooks.php");
    exit();
}

// Fetch Categories
$categoriesQuery = "SELECT * FROM categories"; 
$categoriesResult = mysqli_query($koneksi, $categoriesQuery);

if (!$categoriesResult) {
    die("Query Error: " . mysqli_error($koneksi));
}

// Fetch eBooks
$ebooksQuery = "SELECT books.*, categories.name as category_name FROM books 
                LEFT JOIN categories ON books.category_id = categories.id"; 
$ebooksResult = mysqli_query($koneksi, $ebooksQuery);

if (!$ebooksResult) {
    die("Query Error: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kategori Buku dan List of eBooks</title>
    <link rel="stylesheet" href="../../assets/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../assets/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <!-- Header -->
    <header class="main-header">
        <a href="dashboard" class="logo">
            <span class="logo-mini"><b>A</b>LT</span>
            <span class="logo-lg"><b>Admin</b>LTE</span>
        </a>
        <nav class="navbar navbar-static-top">
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
        </nav>
    </header>

    <!-- Sidebar -->
    <aside class="main-sidebar">
        <section class="sidebar">
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">MAIN NAVIGATION</li>
                <li>
                    <a href="dashboard">
                        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>
               
            </ul>
        </section>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content-header">
            <h1 style="font-family: sans-serif; font-weight: bold;">
                Kategori Buku dan List of eBooks
                <small>
                    <script type='text/javascript'>
                        var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                        var myDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum&#39;at', 'Sabtu'];
                        var date = new Date();
                        var day = date.getDate();
                        var month = date.getMonth();
                        var thisDay = date.getDay(),
                            thisDay = myDays[thisDay];
                        var yy = date.getYear();
                        var year = (yy < 1000) ? yy + 1900 : yy;
                        document.write(thisDay + ', ' + day + ' ' + months[month] + ' ' + year);
                    </script>
                </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li class="active">Kategori Buku dan List of eBooks</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_kategori" data-toggle="tab">Kategori Buku</a></li>
                            <li><a href="#tab_ebooks" data-toggle="tab">List of eBooks</a></li>
                        </ul>
                        <div class="tab-content">
                            <!-- Kategori Buku Tab -->
                            <div class="tab-pane active" id="tab_kategori">
                                <div class="box">
                                    <div class="box-header">
                                        <h3 class="box-title" style="font-family: sans-serif; font-weight: bold;">Kategori Buku</h3>
                                        <div class="form-group m-b-2 text-right" style="margin-top: -20px; margin-bottom: -5px;">
                                            <button type="button" onclick="tambahKategori()" class="btn btn-info"><i class="fa fa-plus"></i> Tambah Kategori</button>
                                        </div>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body table-responsive">
                                        <table id="table_kategori" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Kategori</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $no = 1;
                                                while ($row = mysqli_fetch_assoc($categoriesResult)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $no++ . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['name']) . "</td>"; 
                                                    echo "<td>
                                                            <a href='#' class='btn btn-info btn-sm' data-toggle='modal' data-target='#modalEditKategori" . htmlspecialchars($row['id']) . "'><i class='fa fa-edit'></i></a>
                                                            <a href='list_ebooks.php?delete_kategori_id=" . htmlspecialchars($row['id']) . "' class='btn btn-danger btn-sm btn-del-kategori'><i class='fa fa-trash'></i></a>
                                                          </td>";
                                                    echo "</tr>";
                                                    echo "<div class='modal fade' id='modalEditKategori" . htmlspecialchars($row['id']) . "'>
                                                            <div class='modal-dialog'>
                                                                <div class='modal-content' style='border-radius: 5px;'>
                                                                    <div class='modal-header'>
                                                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                                            <span aria-hidden='true'>&times;</span></button>
                                                                        <h4 class='modal-title' style='font-family: sans-serif; font-weight: bold;'>Edit Kategori (" . htmlspecialchars($row['id']) . " - " . htmlspecialchars($row['name']) . ")</h4>
                                                                    </div>
                                                                    <form action='pages/function/Kategori.php?act=edit' enctype='multipart/form-data' method='POST'>
                                                                        <div class='modal-body'>
                                                                            <input type='hidden' value='" . htmlspecialchars($row['id']) . "' name='idKategori'>
                                                                            <div class='form-group'>
                                                                                <label>ID Kategori</label>
                                                                                <input type='text' class='form-control' value='" . htmlspecialchars($row['id']) . "' name='idKategori' readonly>
                                                                            </div>
                                                                            <div class='form-group'>
                                                                                <label>Nama Kategori</label>
                                                                                <input type='text' class='form-control' value='" . htmlspecialchars($row['name']) . "' name='namaKategori' required>
                                                                            </div>
                                                                        </div>
                                                                        <div class='modal-footer'>
                                                                            <button type='button' class='btn btn-danger pull-left' data-dismiss='modal'>Batal</button>
                                                                            <button type='submit' class='btn btn-primary' name='editKategori'>Simpan</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- eBooks Tab -->
                            <div class="tab-pane" id="tab_ebooks">
                                <div class="box">
                                    <div class="box-header">
                                        <h3 class="box-title" style="font-family: sans-serif; font-weight: bold;">List of eBooks</h3>
                                    </div>
                                    <div class="box-body table-responsive">
                                        <table id="table_ebooks" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Cover</th>
                                                    <th>Judul</th>
                                                    <th>Kategori</th>
                                                    <th>Penulis</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $no = 1;
                                                while ($row = mysqli_fetch_assoc($ebooksResult)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $no++ . "</td>";
                                                    echo "<td><img src='../../assets/images/" . htmlspecialchars($row['cover_image']) . "' alt='Book Cover' style='width: 50px; height: auto;'></td>";
                                                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['category_name']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['author']) . "</td>";
                                                    echo "<td>
                                                            <a href='#' class='btn btn-info btn-sm' data-toggle='modal' data-target='#modalEditEbook" . htmlspecialchars($row['id']) . "'><i class='fa fa-edit'></i></a>
                                                            <a href='list_ebooks.php?delete_ebook_id=" . htmlspecialchars($row['id']) . "' class='btn btn-danger btn-sm btn-del-ebook'><i class='fa fa-trash'></i></a>
                                                          </td>";
                                                    echo "</tr>";
                                                    echo "<div class='modal fade' id='modalEditEbook" . htmlspecialchars($row['id']) . "'>
                                                            <div class='modal-dialog'>
                                                                <div class='modal-content' style='border-radius: 5px;'>
                                                                    <div class='modal-header'>
                                                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                                            <span aria-hidden='true'>&times;</span></button>
                                                                        <h4 class='modal-title' style='font-family: sans-serif; font-weight: bold;'>Edit eBook (" . htmlspecialchars($row['id']) . " - " . htmlspecialchars($row['title']) . ")</h4>
                                                                    </div>
                                                                    <form action='pages/function/Ebook.php?act=edit' enctype='multipart/form-data' method='POST'>
                                                                        <div class='modal-body'>
                                                                            <input type='hidden' value='" . htmlspecialchars($row['id']) . "' name='idEbook'>
                                                                            <div class='form-group'>
                                                                                <label>ID eBook</label>
                                                                                <input type='text' class='form-control' value='" . htmlspecialchars($row['id']) . "' name='idEbook' readonly>
                                                                            </div>
                                                                            <div class='form-group'>
                                                                                <label>Judul</label>
                                                                                <input type='text' class='form-control' value='" . htmlspecialchars($row['title']) . "' name='judul' required>
                                                                            </div>
                                                                            <div class='form-group'>
                                                                                <label>Kategori</label>
                                                                                <input type='text' class='form-control' value='" . htmlspecialchars($row['category_name']) . "' name='kategori' required>
                                                                            </div>
                                                                            <div class='form-group'>
                                                                                <label>Penulis</label>
                                                                                <input type='text' class='form-control' value='" . htmlspecialchars($row['author']) . "' name='penulis' required>
                                                                            </div>
                                                                            <div class='form-group'>
                                                                                <label>Cover</label>
                                                                                <input type='file' class='form-control' name='cover_image'>
                                                                            </div>
                                                                        </div>
                                                                        <div class='modal-footer'>
                                                                            <button type='button' class='btn btn-danger pull-left' data-dismiss='modal'>Batal</button>
                                                                            <button type='submit' class='btn btn-primary' name='editEbook'>Simpan</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 2.4.0
        </div>
        <strong>Copyright &copy; 2014-2016 <a href="https://adminlte.io">Almsaeed Studio</a>.</strong> All rights reserved.
    </footer>
</div>

<script src="../../assets/bower_components/jquery/dist/jquery.min.js"></script>
<script src="../../assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../../assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../../assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="../../assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="../../assets/bower_components/fastclick/lib/fastclick.js"></script>
<script src="../../assets/dist/js/adminlte.min.js"></script>
<script>
    $(function () {
        $('#table_kategori').DataTable();
        $('#table_ebooks').DataTable();
    });
</script>
</body>
</html>
