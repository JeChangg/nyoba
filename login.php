<?php
// Mengimpor file functions.php yang berisi definisi fungsi dan koneksi database
require 'functions.php';
// Memulai sesi untuk penggunaan variabel global $_SESSION
session_start();

// Pengecekan apakah cookie sudah ter-set sebelumnya
if (isset($_COOKIE['id']) && isset($_COOKIE['key'])) {
    
	// Mengambil nilai cookie yang tersimpan
	$id = $_COOKIE['id'];
	$key = $_COOKIE['key'];

	// Query ke database untuk mengambil username dengan id yang didapat dari cookie
	$result = mysqli_query($conn, "SELECT username FROM user WHERE id ='$id'");
	$row = mysqli_fetch_assoc($result);

	// Mengecek apakah hash dari username sama dengan nilai key pada cookie
	if ($key === hash('sha256', $row['username'])) {
		// Jika sama, maka user dianggap sudah login dan session di-set
		$_SESSION['login'] = true;
	}
}

// Cek apakah user sudah login, jika sudah redirect ke halaman index
if (isset($_SESSION["login"])) {
	header("Location: index.php");
	exit;
}

// Jika tombol dengan name login ditekan, maka:
if (isset($_POST["login"])) {
	// Mengambil data dengan atribut name : username dan name : password dari form login
	$username = $_POST["username"];
	$password = $_POST["password"];
	// Menjalankan query pada tabel user untuk mengambil data user dengan username yang sesuai dengan inputan username
	$result = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");
	// Jika query menghasilkan satu baris, maka:
	if (mysqli_num_rows($result) === 1) {
		// Mengambil data user dari hasil query
		$row = mysqli_fetch_assoc($result);
		// Melakukan verifikasi password
		if (password_verify($password, $row["password"])) {
			// Jika password benar, maka set session login menjadi true
			$_SESSION["login"] = true;
			// Jika checkbox remember me di-check, maka set cookie
			if (isset($_POST['remeber'])) {
				// Membuat cookie dengan nama 'id' dan nilai id user yang sedang login
				setcookie('id', $row['id'], time() + 60);
				// Membuat cookie dengan nama 'key' dan nilai hash sha256 dari username user yang sedang login
				setcookie('key', hash('sha256', $row['username']), time() + 120);
			}
			// Redirect ke halaman index.php
			header("Location: index.php");
			exit;
		} else {
			// Jika password salah, maka set variabel error menjadi true
			$error = true;
		}
	} else {
		// Jika username tidak ditemukan di database, maka set variabel 
		$error = true;
	}
	
}
?>

<!DOCTYPE html>
<html>
    
<head>
	<title>Tes</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<!--Coded with love by Mutiullah Samim-->
<body>
	<div class="container h-100">
		<div class="d-flex justify-content-center h-100">
			<div class="user_card">
				<div class="d-flex justify-content-center">
					<div class="brand_logo_container">
						<img src="https://cdn.freebiesupply.com/logos/large/2x/pinterest-circle-logo-png-transparent.png" class="brand_logo" alt="Logo">
						
					</div>
				</div>
				<div class="d-flex justify-content-center form_container">
					<form action="" method="POST">
						<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fas fa-user"></i></span>
							</div>
							<input type="text" name="username" class="form-control input_user" value="" placeholder="username">
						</div>
						<div class="input-group mb-2">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fas fa-key"></i></span>
							</div>
							<input type="password" name="password" class="form-control input_pass" value="" placeholder="password">
						</div>
						<div class="form-group">
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" id="customControlInline">
								<label class="custom-control-label" for="customControlInline">Remember me</label>
							</div>
						</div>
						<div class="d-flex justify-content-center mt-3 login_container">
				 			<button type="submit" name="login" class="btn login_btn">Login</button>
				   		</div>
					</form>
				</div>
		
				<!-- <div class="mt-4">
					<div class="d-flex justify-content-center links">
						Don't have an account? <a href="#" class="ml-2">Sign Up</a>
					</div> -->
					<div class="d-flex justify-content-center links">
						<a href="#">Forgot your password?</a>
					</div>

					<?php if (isset($error)) : ?>
							<!-- Jika variabel $error telah di-set oleh kode sebelumnya, maka akan menampilkan pesan kesalahan pada halaman web. -->
							<p style="color: red; font-style: italic; text-align: center ">username / password salah</p>
							<!-- Syntax endif digunakan untuk menutup blok kondisi IF di baris sebelumnya. -->
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</body>
</html>