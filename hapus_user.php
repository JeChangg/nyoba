<?php
// Mengimpor file functions.php yang berisi definisi fungsi dan koneksi database
require 'functions.php';
// Memulai sesi untuk penggunaan variabel global $_SESSION
session_start();
// Mengambil nilai id dari parameter url dan menyimpannya dalam variabel $id dengan $_GET
$id = $_GET["id"];
	//Jika sudah ditekan, jalankan fungsi hapus_user pada file functions.php dan cek apakah data berhasil ditambahkan atau tidak
if (hapus_user($id)>0) {
	//Jika berhasil, tampilkan pesan sukses dan redirect ke halaman utama
	echo "
		<script>
			alert('Data Berhasil Dihapus');
			document.location.href='index.php';
		</script>
	";
}else{
	//Jika berhasil, tampilkan pesan sukses dan redirect ke halaman utama
	echo "
		<script>
			alert('Data Gagal Dihapus');
			document.location.href='index.php';
		</script>
	";
}
?>