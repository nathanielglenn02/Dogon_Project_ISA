<?php

session_start();

if (!isset($_SESSION['ssLogin'])) {
    header("Location: ../auth/login.php");
    exit;
}

$title = "Tambah Buku - Perpustakaan";
require_once "../service/config.php";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Tambah Buku</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php" style="text-decoration: none; color: black;">Home</a></li>
                <li class="breadcrumb-item"><a href="buku.php" style="text-decoration: none; color: black;">Buku</a></li>
                <li class="breadcrumb-item active">Tambah Buku</li>
            </ol>
            <form action="proses-buku.php" method="POST">
                <div class="card">
                    <div class="card-header">
                        <span class="h5 my-2"><i class="fa-solid fa-square-plus"></i> Tambah Buku</span>
                        <button type="submit" name="simpan" class="btn btn-primary float-end"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                        <button type="reset" name="reset" class="btn btn-danger float-end me-1"><i class="fa-solid fa-xmark"></i> Reset</button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3 text-center px-5">
                                <img src="../asset/image/default.png" class="mb-3" width="40%" alt="">
                                <input type="file" name="image" class="form-control form-control-sm">
                                <small class="text-secondary">Pilih foto PNG, JPG atau JPEG dengan ukuran maximal 1
                                    MB</small>
                                <div><small class="text-secondary">width = height</small></div>
                            </div>
                            <div class="col-8">
                                <div class="mb-3 row">
                                    <label for="isbn" class="col-sm-2 col-form-label">ISBN</label>
                                    <label for="isbn" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <input type="text" name="isbn" class="form-control ps-2 border-0 border-bottom" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="judul" class="col-sm-2 col-form-label">Judul</label>
                                    <label for="judul" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <input type="text" name="judul" class="form-control ps-2 border-0 border-bottom" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="penerbit" class="col-sm-2 col-form-label">Penerbit</label>
                                    <label for="penerbit" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <input type="text" name="penerbit" class="form-control ps-2 border-0 border-bottom" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="tahun-buku" class="col-sm-2 col-form-label">Tahun Buku</label>
                                    <label for="tahun-buku" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <input type="text" name="tahun-buku" class="form-control ps-2 border-0 border-bottom" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
</div>

<?php

require_once "../template/footer.php";

?>