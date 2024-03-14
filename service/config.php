<?php

//buat koneksi
$hostname = "localhost";
$username = "root";
$password = "";
$database_name = "dogon_isa";

$koneksi = mysqli_connect($hostname, $username, $password, $database_name);

if ($koneksi->connect_error) {
    echo "koneksi database rusak";
    die("error!");
}
// else {
//     echo "koneksi berhasil";
// }

$main_url = "http://localhost/Dogon_Project_ISA/";

function uploadimg($url)
{
    $namafile = $_FILES['image']['name'];
    $ukuran = $_FILES['image']['size'];
    $error = $_FILES['image']['error'];
    $tmp = $_FILES['image']['tmp_name'];

    //cek file yang dipload 
    $validExtension = ['jpg', 'jpeg', 'png'];
    $fileExtension = explode('.', $namafile);
    $fileExtension = strtolower(end($fileExtension));
    if (!in_array($fileExtension, $validExtension)) {
        header("location:" . $url . '?msg=notimage');
        die;
    }

    //cek ukuran gambar
    if ($ukuran > 1000000) {
        header("location:" . $url . '?msg=oversize');
        die;
    }

    //generate nama file gambar
    if ($url == 'profile-sekolah.php') {
        $namafilebaru = rand(0, 50) . '-bgLogin' . '.' . $fileExtension;
    } else {
        $namafilebaru = rand(10, 1000) . '-' . $namafile;
    }
    //upload gambar
    move_uploaded_file($tmp, "../asset/image/" . $namafilebaru);
    return $namafilebaru;
}
