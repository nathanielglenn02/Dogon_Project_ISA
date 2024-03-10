<?php

//buat koneksi
$hostname = "localhost";
$username = "root";
$password = "";
$database_name = "dogon_isa";

$db = mysqli_connect($hostname, $username, $password, $database_name);

if ($db->connect_error) {
    echo "koneksi database rusak";
    die("error!");
} else {
    echo "koneksi berhasil";
}

$main_url = "http://localhost/Dogon_Project_ISA/";
