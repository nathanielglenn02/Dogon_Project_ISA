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

if (isset($_GET['msg']) && isset($_GET['nis'])) {
    $msg = $_GET['msg'];
    $nis = $_GET['nis'];
} else {
    $msg = "";
    $nis = "";
}

$alert = '';
if ($msg == 'LULUS') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check"></i> SELAMAT.. Siswa dengan NIS : ' . $nis . ' berhasil LULUS UJIAN
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
}
if ($msg == 'GAGAL') {
    $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-xmark"></i> SELAMAT.. Siswa dengan NIS : ' . $nis . ' GAGAL UJIAN
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
}

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
            <?php if ($msg !== "") {
                echo $alert;
            } ?>
            <form action="proses-ujian.php" methode="POST">
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
                                    <span class="input-group-text"><i class="fa-solid fa-calendar-days fa-sm"></i></span>
                                    <input type="date" name="tgl " class="form-control" required>
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
                                        <option value="IPA">-- IPA--</option>
                                        <option value="IPS">-- IPS--</option>
                                        <option value="Bahasa">-- Bahasa--</option>
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
            </form>
        </div>
    </main>

    <script>
        const jurusan = document.getElementById('jurusan');
        const mapelKejuruan = document.getElementById('kejuruan');

        jurusan.addEventListener('change', function() {
            let ajax = new XMLHttpRequest();


            ajax.onreadystatechange = function() {
                if (ajax.readyState == 4 & ajax.status == 200) {
                    mapelKejuruan.innerHTML = ajax.responseText;

                }
            }

            ajax.open('GET', 'ajax-mapel.php?jurusan=' + jurusan.value, true)
            ajax.send();


        })

        const total = document.getElementById('total_nilai');
        const minValue = document.getElementById('nilai_terendah');
        const maxValue = document.getElementById('nilai_tertinggi');
        const average = document.getElementById('nilai_rata');

        function fnhitung() {
            let nilaiUjian = document.getElementsByClassName('nilai');

            let totalNilai = 0;
            let listNilai = [];
            for (let i = 0; i < nilaiUjian.length; i++) {
                totalNilai = parseInt(totalNilai) + parseInt(nilaiUjian[i].value);
                total.value = totalNilai;

                listNilai.push(nilaiUjian[i].value);

                listNilai.sort(function(a, b) {
                    return a - b
                });

                minValue.value = listNilai[0];
                maxValue.value = listNilai[listNilai.length - 1];
                average.value = Math.round(totalNilai / listNilai.length);

            }
        }
    </script>

    <?php

    require_once "../template/footer.php";

    ?>