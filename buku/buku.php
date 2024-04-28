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

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
} else {
    $msg = "";
}

$alert = '';
if ($msg == 'deleted') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check"></i> Data Buku berhasil dihapus..
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
}
if ($msg == 'notborrowed') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-exclamation"></i>  Mohon pinjam buku terlebih dahulu
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
}
if ($msg == 'terima') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check"></i> Pinjam Buku berhasil diterima..
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
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
            <?php
            if ($msg != "") {
                echo $alert;
            }
            ?>
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
                                    <center>User Peminjam</center>
                                </th>
                                <th scope="col">
                                    <center>User Pelayanan</center>
                                </th>
                                <th scope="col">
                                    <center class="<?= $displaySiswa ?>">Operasi</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $queryBuku = mysqli_query($koneksi, "
                            SELECT 
                                buku.*,
                                user_peminjam.username AS username_peminjam,
                                user_pelayanan.username AS username_pelayanan
                            FROM 
                                buku
                                LEFT JOIN user AS user_peminjam ON buku.id_user_peminjam = user_peminjam.id
                                LEFT JOIN user AS user_pelayanan ON buku.id_user_pelayanan = user_pelayanan.id
                        ");
                            while ($data = mysqli_fetch_array($queryBuku)) {
                            ?>
                                <tr>
                                    <td><?= $data['id'] ?></td>
                                    <td align="center">
                                        <center>
                                            <img src="../asset/image/<?= $data['sampul'] ?>" class="rounded-circle" alt="Sampul Buku" width="60px">
                                        </center>
                                    </td>
                                    <td><?= $data['isbn'] ?></td>
                                    <td><?= $data['title'] ?></td>
                                    <td><?= $data['penerbit'] ?></td>
                                    <td><?= $data['tahun_buku'] ?></td>
                                    <td>
                                        <?= $data['stok_buku'] ?>
                                        <button type="button" data-id="<?= $data['id'] ?>" id="btnPinjam" class="btn btn-sm btn-warning" title="pinjam buku"><i class="fa-solid fa-cart-shopping"></i></button>

                                        <button type="button" data-id="<?= $data['id'] ?>" id="btnKembali" class="btn btn-sm btn-warning" title="kembali buku"><i class="fa-solid fa-rotate-left"></i></button>
                                    </td>
                                    <td>
                                        <a href="proses-buku.php?id=<?= $data['id'] ?>" id="btnTerima" class="btn btn-sm btn-warning <?= $displaySiswa ?>" title="update Status"><i class="fa-solid fa-shuffle"></i></a>
                                        <?= $data['dipinjam'] ?>
                                    </td>
                                    <td>
                                        <?= $data['username_peminjam'] ?>
                                    </td>
                                    <td>
                                        <?= $data['username_pelayanan'] ?>
                                    </td>
                                    <td>
                                        <button type="button" data-id="<?= $data['id'] ?>" id="btnHapus" class="btn btn-sm btn-danger <?= $displaySiswa ?>" title="hapus buku"><i class="fa-solid fa-trash"></i></button>
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
                            </div>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- modal pinjam buku -->
    <div class="modal" id="mdlPinjam" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Anda yakin ingin meminjam buku ini ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="" id="btnMdlPinjam" class="btn btn-primary">Ya</a>
                </div>
            </div>
        </div>
    </div>

    <!-- modal kembali buku -->
    <div class="modal" id="mdlKembali" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Anda yakin ingin mengembalikan buku ini ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="" id="btnMdlKembali" class="btn btn-primary">Ya</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $(document).on('click', "#btnPinjam", function() {
                $('#mdlPinjam').modal('show');
                let id = $(this).data('id');
                $('#btnMdlPinjam').attr('href', "proses-buku.php?id=" + id + "&simpan=ubah");
            })

            $(document).on('click', "#btnKembali", function() {
                $('#mdlKembali').modal('show');
                let id = $(this).data('id');
                $('#btnMdlKembali').attr('href', "proses-buku.php?id=" + id + "&kembali=ubah");
            })

            $(document).on('click', "#btnTerima", function() {
                $('#mdlTerima').modal('show');
                let id = $(this).data('id');
                $('#btnMdlTerima').attr('href', "proses-buku.php?id=" + id + "&terima=ubah");
            })

            setTimeout(function() {
                $('#added').fadeIn('slow');
            }, 300)
            setTimeout(function() {
                $('#added').fadeOut('slow');
            }, 3000)

            setTimeout(function() {
                $('#updated').slideDown(700);
            }, 300)

            setTimeout(function() {
                $('#updated').slideUp(700);
            }, 5000)
        })
    </script>


    <?php

    require_once "../template/footer.php"

    ?>