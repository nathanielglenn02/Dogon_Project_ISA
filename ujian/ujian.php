<?php

session_start();

if (!isset($_SESSION['ssLogin'])) {
    header("location:../auth/login.php");
    exit();
}

require_once "../service/config.php";
$title = "Data Ujian - SMK Pelita";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Ujian</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item active">Data Ujian</li>
            </ol>
            <div class="card">
                <div class="card-header">
                    <i class="fa-solid fa-list"></i> Data Ujian
                    <a href="nilai-ujian.php" class="btn btn-sm btn-primary float-end ms-1"><i class="fa-solid fa-plus"></i> Tambah Data Ujian</a>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <center>No Ujian</center>
                                </th>
                                <th scope="col">
                                    <center>NIS</center>
                                </th>
                                <th scope="col">
                                    <center>Jurusan</center>
                                </th>
                                <th scope="col">
                                    <center>Nilai Terendah</center>
                                </th>
                                <th scope="col">
                                    <center>Nilai Tertinggi</center>
                                </th>
                                <th scope="col">
                                    <center>Nilai Rata-rata</center>
                                </th>
                                <th scope="col">
                                    <center>Hasil Ujian</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>


    <?php

    require_once "../template/footer.php";

    ?>