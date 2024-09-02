<?php
session_start();
include "../config/koneksi.php";

function update_last_login($koneksi, $id_user) {
    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date('d-m-Y');
    $jam = date('H:i:s');
    $query = "UPDATE user SET terakhir_login = '$tanggal ( $jam )' WHERE id_user = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param('i', $id_user);
    $stmt->execute();
}

if ($_GET['aksi'] == "masuk") {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    
    // Using prepared statements to prevent SQL Injection
    $query = "SELECT * FROM user WHERE username = ? AND password = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $cek = $result->num_rows;

    if ($cek > 0) {
        $row = $result->fetch_assoc();

        $_SESSION['id_user'] = $row['id_user'];
        $_SESSION['username'] = $username;
        $_SESSION['profile_pic'] = $row['profile_pic'];
        $_SESSION['fullname'] = $row['fullname'];
        $_SESSION['password'] = $row['password'];
        $_SESSION['status'] = "Login";
        $_SESSION['level'] = $row['role'];

        update_last_login($koneksi, $row['id_user']);

        if ($row['role'] == "Admin") {
            header("location: ../admin");
        } else if ($row['role'] == "Anggota") {
            header("location: ../user");
        } else {
            $_SESSION['user_tidak_terdaftar'] = "Maaf, User tidak terdaftar pada database !!";
            header("location: ../masuk");
        }
    } else {
        $_SESSION['gagal_login'] = "Nama Pengguna atau Kata Sandi salah !!";
        header("location: ../masuk");
    }
} elseif ($_GET['aksi'] == "daftar") {
    $fullname = htmlspecialchars($_POST['funame']);
    $username = addslashes(strtolower($_POST['uname']));
    $username1 = str_replace(' ', '', $username);
    $password = htmlspecialchars($_POST['passw']);
    $kls = htmlspecialchars($_POST['kelas']);
    $jrs = htmlspecialchars($_POST['jurusan']);
    $kelas = $kls . $jrs;
    $alamat = htmlspecialchars($_POST['alamat']);
    $verif = "Tidak";
    $role = "Anggota";
    $join_date = date('d-m-Y');

    $query = mysqli_query($koneksi, "SELECT max(kode_user) as kodeTerakhir FROM user");
    $data = mysqli_fetch_array($query);
    $kodeTerakhir = $data['kodeTerakhir'];
    $urutan = (int) substr($kodeTerakhir, 3, 3);
    $urutan++;
    $huruf = "AP";
    $Anggota = $huruf . sprintf("%03s", $urutan);

    $query = "INSERT INTO user (kode_user, fullname, username, password, kelas, alamat, verif, role, join_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param('sssssssss', $Anggota, $fullname, $username1, $password, $kelas, $alamat, $verif, $role, $join_date);

    if ($stmt->execute()) {
        $_SESSION['berhasil'] = "Pendaftaran Berhasil !";
        header("location: ../masuk");
    } else {
        $_SESSION['gagal'] = "Pendaftaran Gagal !";
        header("location: ../masuk");
    }
}
?>
