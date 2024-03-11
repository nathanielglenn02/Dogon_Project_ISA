<?php

require_once "../service/config.php";

$title = "Tambah User - SMA Dogon";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Tambah User</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item active">Tambah User</li>
            </ol>
            <div class="card">
                <div class="card-header">
                    <span class="h5 my-4"><i class="fa-solid fa-square-plus"></i> Tambah User</span>
                    <button type="submit" name="simpan" class="btn btn-primary float-end"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                    <button type="reset" name="reset" class="btn btn-danger float-end me-1"><i class="fa-solid fa-xmark"></i>
                        Reset</button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <div class="mb-3 row">
                                <label for="username" class="col-sm-2 col-form-label">Username</label>
                                <label for="" class="col-sm-1 col-form-label">:</label>
                                <div class="col-sm-9" style="margin-left: -50px;">
                                    <input type="text" pattern="[A-Za-z0-9]{3,}" title="Minimal 3 karakter kombinasi huruf besar huruf kecil dan angka" class="form-control border-0 border-bottom" id="username" name="username" maxlength="20" required>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="username" class="col-sm-2 col-form-label">Nama</label>
                                <label for="" class="col-sm-1 col-form-label">:</label>
                                <div class="col-sm-9" style="margin-left: -50px;">
                                    <input type="text" class="form-control border-0 border-bottom" id="nama" name="nama" maxlength="20" required>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="username" class="col-sm-2 col-form-label">Jabatan</label>
                                <label for="" class="col-sm-1 col-form-label">:</label>
                                <div class="col-sm-9" style="margin-left: -50px;">
                                    <select name="jabatan" id="jabatan" class="form-select border-0 border-bottom" required>
                                        <option value="" selected>-- Pilih Jabatan --</option>
                                        <option value="Kepsek">Kepala Sekolah</option>
                                        <option value="Staf TU">Staf TU</option>
                                        <option value="Guru">Guru Mata Pelajaran</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="username" class="col-sm-2 col-form-label">Alamat</label>
                                <label for="" class="col-sm-1 col-form-label">:</label>
                                <div class="col-sm-9" style="margin-left: -50px;">
                                    <textarea name="alamat" id="alamat" cols="30" rows="3" class="form-control" placeholder="domisili" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 text-center px-5">
                            <img src="../asset/image/default.png" alt="gambar user" class="mb-3" width="40%">
                            <input type="file" name="image" id="image" class="form-control form-control-sm">
                            <small class="text-secondary">Pilih Foto PNG, JPG, atau JPEG dengan ukuran maximal 1
                                MB</small>
                            <div><small class="text-secondary">Width = Height</small></div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php

    require_once "../template/footer.php";


    ?>