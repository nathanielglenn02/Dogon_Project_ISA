<?php

session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("location:../auth/login.php");
    exit();
}

require_once "../service/config.php";
$title = "Guru - SMA Dogon";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";
?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Guru</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php" style="text-decoration: none; color: black;">Home</a></li>
                <li class="breadcrumb-item active">Guru</li>
            </ol>
            <div class="card">
                <div class="card-header">
                    <i class="fa-solid fa-list"></i> Data Guru
                    <a href="<?= $main_url ?>guru/add-guru.php" class="btn btn-sm btn-primary float-end"><i class="fa-solid fa-plus"></i>Tambah Guru</a>
                </div>
                <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col"><center>No</center></th>
                            <th scope="col"><center>Foto</center></th>
                            <th scope="col"><center>NIP</center></th>
                            <th scope="col"><center>Nama</center></th>
                            <th scope="col"><center>Telepon</center></th>
                            <th scope="col"><center>Agama</center></th>
                            <th scope="col"><center>Alamat</center></th>
                            <th scope="col"><center>Operasi</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Foto Guru</td>
                            <td>NIP</td>
                            <td>Nama</td>
                            <td>Telepon</td>
                            <td>Agama</td>
                            <td>Alamat</td>
                            <td align = "center">
                                <a href="" class="btn btn-sm btn-warning" title="update guru"><i class="fa-solid fa-pen"></i></a>
                                <a href="" class="btn btn-sm btn-danger" title="delate guru"><i class="fa-solid fa-trash"></i></a>
                            </td>
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