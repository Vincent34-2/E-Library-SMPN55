<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | Perpustakaan SMPN 55 Jakarta</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/bower_components/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="assets/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="assets/plugins/iCheck/square/blue.css">
    <link rel="stylesheet" href="assets/dist/css/toastr.min.css">
    <link rel="stylesheet" href="assets/dist/css/custom.css">
    <link rel="icon" type="icon" href="assets/dist/img/smp55.png">
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        .background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #3f87a6, #ebf8e1, #f69d3c);
            background-size: 400% 400%;
            z-index: -1;
        }
    </style>
</head>
<body>
    <div class="background" id="background"></div>
    <div class="login-box">
        <div class="login-logo">
            <h1 class="font-weight-bold">PERPUSTAKAAN SMPN 55 JAKARTA</h1>
        </div>
        <div class="login-box-body" style="border-radius: 10px;">
            <img src="assets/dist/img/smp55.png" height="80px" width="80px" style="display: block; margin: auto;">
            <form name="formLogin" action="function/Process.php?aksi=masuk" method="POST" onsubmit="return validateForm()">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="username" id="username" placeholder="Nama Pengguna">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Kata Sandi">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Masuk</button>
                    </div>
                </div>
            </form>
            <div class="social-auth-links text-center">
                <p style="font-size: 11px;">- ATAU -</p>
                <div class="row">
                    <div class="col-xs-12">
                        <button type="button" onclick="Register()" class="btn btn-block btn-warning btn-flat">
                            <i class="fa fa-user-plus"></i> Daftar sebagai member
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <p style="font-weight: bold; text-align: center; color: black;">Megalodon Inc.</p>
    </div>

    <script src="assets/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/json/lottie-player.js"></script>
    <script src="assets/dist/js/sweetalert.min.js"></script>
    <script src="assets/dist/js/toastr.min.js"></script>
    <script>
        function Register() {
            window.location.href = "pendaftaran";
        }

        function validateForm() {
            var username = document.getElementById("username").value.trim();
            var password = document.getElementById("password").value.trim();

            if (username === "" || password === "") {
                toastr.warning("Nama Pengguna dan Kata Sandi harus diisi !!");
                return false;
            }
            return true;
        }

        <?php if(isset($_SESSION['gagal_login'])): ?>
            toastr.error("<?= $_SESSION['gagal_login'] ?>");
            <?php unset($_SESSION['gagal_login']); ?>
        <?php endif; ?>
    </script>
</body>
</html>
