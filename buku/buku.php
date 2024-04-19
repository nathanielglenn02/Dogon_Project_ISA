<?php

session_start();

if (!isset($_SESSION['ssLogin'])) {
    header("location:../auth/login.php");
    exit();
}

require_once "../service/config.php";
$title = "Data Buku - SMA Dogon";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

$username = $_SESSION["ssUser"];
$queryUser = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username'");
$profile = mysqli_fetch_array($queryUser);

if ($profile['jabatan'] == "Siswa") {
    $displaySiswa = "visually-hidden";
} else if ($profile['jabatan'] == "Kepsek") {
    $displayKepsek = "visually-hidden";
} else {
    $displaySiswa = "";
}
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Buku</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item active">Data Buku</li>
            </ol>
            <div class="card">
                <div class="card-header">
                    <i class="fa-solid fa-list"></i> Data Buku
                    <a href="add-buku.php" class="btn btn-sm btn-primary float-end ms-1"><i class="fa-solid fa-plus"></i> Tambah Data Buku</a>
                </div>
                <div class="card-body">

                    <table class="table table-hover" id="datatablesSimple">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <center>No Buku</center>
                                </th>
                                <th scope="col">
                                    <center>Sampul</center>
                                </th>
                                <th scope="col">
                                    <center>ISBN</center>
                                </th>
                                <th scope="col">
                                    <center>Title</center>
                                </th>
                                <th scope="col">
                                    <center>Penerbit</center>
                                </th>
                                <th scope="col">
                                    <center>Tahun Buku</center>
                                </th>
                                <th scope="col">
                                    <center>Stok Buku</center>
                                </th>
                                <th scope="col">
                                    <center>Status</center>
                                </th>
                                <th scope="col">
                                    <center>Operasi</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $queryUjian = mysqli_query($koneksi, "SELECT * FROM buku");
                            while ($data = mysqli_fetch_array($queryUjian)) {
                            ?>
                                <tr>
                                    <td><?= $data['id'] ?></td>
                                    <td><?= $data['sampul'] ?></td>
                                    <td><?= $data['isbn'] ?></td>
                                    <td><?= $data['title'] ?></td>
                                    <td><?= $data['penerbit'] ?></td>
                                    <td><?= $data['tahun_buku'] ?></td>
                                    <td><?= $data['stok_buku'] ?></td>
                                    <td>
                                        <a href="edit-pelajaran.php?id=<?= $data['id'] ?>" class="btn btn-sm btn-warning" title="update pelajaran"><i class="fa-solid fa-shuffle"></i></a>
                                        <?= $data['dipinjam'] ?>
                                    </td>
                                    <td>
                                        <button type="button" data-id="<?= $data['id'] ?>" id="btnHapus" class="btn btn-sm btn-danger" title="hapus pelajaran"><i class="fa-solid fa-trash"></i></button>

                                    </td>
                                </tr>
                            <?php } ?>

                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <form action="" method="GET" class="col-4">
                                        <?php
                                        if (@$_GET['cari']) {
                                            $cari = @$_GET['cari'];
                                        } else {
                                            $cari = '';
                                        }
                                        ?>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="keyword" name="cari" value="<?= $cari ?>">
                                            <button class="btn btn-secondary" type="submit" id="btnCari"><i class="fa-solid fa-magnifying-glass"></i></span>
                                        </div>
                                    </form>
                                    <?php
                                    $page = 5;

                                    if (isset($_GET['hal'])) {
                                        $halaktif = $_GET['hal'];
                                    } else {
                                        $halaktif = 1;
                                    }

                                    if (@$_GET['cari']) {
                                        $keyword = @$_GET['cari'];
                                    } else {
                                        $keyword = '';
                                    }

                                    $start = ($page * $halaktif) - $page;
                                    $no = $start + 1;
                                    $queryPelajaran = mysqli_query($koneksi, "SELECT * FROM pelajaran WHERE pelajaran like '%$keyword%' or jurusan like '%$keyword%' or guru like '%$keyword%' limit $page offset $start");

                                    $queryJmlData = mysqli_query($koneksi, "SELECT * FROM buku WHERE isbn like '%$keyword%' or title like '%$keyword%' or penerbit like '%$keyword%' or tahun_buku like '%$keyword%'");
                                    $jmlData = mysqli_num_rows($queryJmlData);
                                    $pages = ceil($jmlData / $page);
                                    ?>
                                    <div class="col-4 text-end">
                                        <label class="col-8 col-form-label text-end">Jumlah Data :
                                            <?= $jmlData ?></label>
                                    </div>
                                </div>
                        </tbody>
                </div>
            </div>
        </div>
</div>
</main>
</div>