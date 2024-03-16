<?php

session_start();

if (!isset($_SESSION['ssLogin'])) {
    header("location:../auth/login.php");
    exit();
}

require_once "../service/config.php";
$title = "Nilai Ujian - SMK Pelita";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

$queryNoUjian = mysqli_query($koneksi, "SELECT max(no_ujian) as maxno FROM ujian");
$data = mysqli_fetch_array($queryNoUjian);
$maxno = $data['maxno'];

$noUrut = (int) substr($maxno, 4, 3);
$noUrut++;
$maxno = "UTS-" . sprintf("%03s", $noUrut);
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="row">
                <div class="col-7">
                    <h1 class="mt-4">Nilai Ujian</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="ujian.php">Ujian</a></li>
                        <li class="breadcrumb-item active">Nilai Ujian</li>
                    </ol>
                </div>
                <div class="col-5">
                    <div class="card mt-3 border-0">
                        <h5>Syarat Kelulusan</h5>
                        <ul class="ps-3">
                            <li><small>Nilai minimal tiap mata pelajaran tidak boleh dibawah 50</small></li>
                            <li><small>Nilai rata-rata mata pelajaran tidak boleh dibawah 60</small></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa-solid fa-plus"></i> Data Peserta Ujian
                        </div>
                        <div class="card-body">
                            <div class="input-group mb-2">
                                <span class="input-group-text"><i class="fa-solid fa-rotate fa-sm"></i></span>
                                <input type="text" name="noUjian" value="<?= $maxno ?>" class="form-control bg-transparent" readonly>
                            </div>
                            <div class="input-group mb-2">
                                <span class="input-group-text"><i class="fa-solid fa-user fa-sm"></i></span>
                                <select name="nis" id="nis" class="form-select" required>
                                    <option value="">-- Pilih Siswa--</option>
                                    <?php
                                    $querySiswa = mysqli_query($koneksi, "SELECT * FROM siswa");
                                    while ($data = mysqli_fetch_array($querySiswa)) { ?>
                                        <option value="<?= $data['nis'] ?>"><?= $data['nis'] . '-' . $data['nama'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="input-group mb-2">
                                <span class="input-group-text"><i class="fa-solid fa-location-arrow fa-sm"></i></span>
                                <select name="jurusan" id="jurusan" class="form-select" required>
                                    <option value="">-- Jurusan--</option>
                                    <option value="Kimia Industri">-- Kimia Industri--</option>
                                    <option value="Kimia Analis">-- Kimia Analis--</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card card-body border mt-2 rounded">
                        <div class="input-group mb-2">
                            <span class="input-group-text col-2 ps-2 fw-bold">Sum</i></span>
                            <input type="text" name="sum" class="form-control" placeholder="Total Nilai" id="total_nilai" required readonly>
                        </div>
                        <div class="input-group mb-2">
                            <span class="input-group-text col-2 ps-2 fw-bold">Min</i></span>
                            <input type="text" name="min" class="form-control" placeholder="Nilai Terendah" id="nilai_terendah" required readonly>
                        </div>
                        <div class="input-group mb-2">
                            <span class="input-group-text col-2 ps-2 fw-bold">Max</i></span>
                            <input type="text" name="max" class="form-control" placeholder="Nilai Tertinggi" id="nilai_tertinggi" required readonly>
                        </div>
                        <div class="input-group mb-2">
                            <span class="input-group-text col-2 ps-2 fw-bold">Avg</i></span>
                            <input type="text" name="avg" class="form-control" placeholder="Nilai Rata-rata" id="rata2" required readonly>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa-solid fa-list"></i> Input Nilai Ujian
                            <button type="submit" name="simpan" class="btn btn-sm btn-primary float-end"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                            <button type="reset" name="reset" class="btn btn-sm btn-danger me-1 float-end"><i class="fa-solid fa-xmark"></i> Reset</button>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            <center>No</center>
                                        </th>
                                        <th scope="col">
                                            <center>Mata Pelajaran</center>
                                        </th>
                                        <th scope="col">
                                            <center>Jurusan</center>
                                        </th>
                                        <th scope="col" style="width: 20%">
                                            <center>Nilai Ujian</center>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="kejuruan">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php

    require_once "../template/footer.php";

    ?>