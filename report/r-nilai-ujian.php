<?php

session_start();

if (!isset($_SESSION['ssLogin'])) {
    header("location:../auth/login.php");
    exit();
}

require_once "../service/config.php";

// Validate the noUjian parameter
if (empty($_GET['noUjian'])) {
    die('No Ujian parameter is missing or invalid.');
}

$noUjian = $_GET['noUjian'];

$dataUjian = mysqli_query($koneksi, "SELECT * FROM ujian WHERE no_ujian = '$noUjian'");
$data = mysqli_fetch_array($dataUjian);

$no = 1;

$nilaiUjian = mysqli_query($koneksi, "SELECT * FROM nilai_ujian WHERE no_ujian = '$noUjian'");
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <tr>
        <td class="data-no"></td>
        <td></td>
        <td class="data-nilai"></td>
    </tr>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nilai Ujian</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .title {
            text-align: center;
        }

        h3 {
            margin-top: 20px;
        }

        h2 {
            margin-bottom: 30px;
        }

        .head-left {
            width: 100px;
            padding-left: 30px;
            padding-bottom: 10px;
        }

        .head-left-isi {
            padding-left: -60px;
            padding-right: 10px;
        }

        .head-left-jurusan {
            padding-left: -60px;
            padding-right: 35px;
        }

        .head-left-titik {
            width: 1500px;
        }

        .head-right {
            color: red;
        }

        hr {
            margin-bottom: 2px;
            margin-left: 5px;
        }

        .head-no {
            padding-left: 20px;
        }

        .head-mapel {
            width: 400px;
        }

        .head-nilai {
            width: 170px;
            text-align: center;
        }

        .data-no {
            padding-left: 50px;
        }

        .data-pelajaran {
            padding-left: 580px;
        }

        .data-nilai {
            text-align: center;
        }

        .foot {
            padding-left: -500px;
            padding-right: -500px;
            padding-bottom: 5px;
        }

        .sum-colon {
            margin-left: 30px;
        }

        .min-nilai {
            margin-left: -5px;
        }

        .max-colon {
            margin-left: 3px;
            padding-right: 2px;
        }

        .foot-nilai {
            margin-left: 30px;
        }

        .avg-colon {
            margin-left: 44px;
        }
    </style>
</head>

<body>

    <div class="title">
        <h3>Laporan Nilai Ujian</h3>
        <h2>SMA Dogon</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th class="head-left">No Ujian</th>
                <th class="head-left-titik">: <?= htmlspecialchars($data['no_ujian']) ?></th>
                <th>Surabaya, <?= date('j F Y') ?></th>
            </tr>

            <tr>
                <th class="head-left">Tgl Ujian</th>
                <th class="head-left">: <?= date('d F Y', strtotime(htmlspecialchars($data['tgl_ujian']))) ?></th>
            </tr>
            <tr>
                <th class="head-left">NIS</th>
                <th class="head-left-isi">: <?= htmlspecialchars($data['nis']) ?></th>
            </tr>
            <tr>
                <th class="head-left">Jurusan</th>
                <th class="head-left-jurusan">: <?= htmlspecialchars($data['jurusan']) ?></th>
                <th class="head-right">Hasil Ujian : <?= htmlspecialchars($data['hasil_ujian']) ?></th>
            </tr>
            <tr>
                <th colspan="3">
                    <hr>
                </th>
            </tr>
            <tr>
                <th class="head-no">No</th>
                <th class="head-mapel">Mata Pelajaran</th>
                <th class="head-nilai">Nilai</th>
            </tr>
            <tr>
                <th colspan="3">
                    <hr>
                </th>
            </tr>
        </thead>

        <!--  
        <tbody>
            <tr>
                <td class="data-no"></td>
                <td></td>
                <td class="data-nilai"></td>
            </tr>
        </tbody>
        -->

        <tbody>
            <?php while ($nilai = mysqli_fetch_array($nilaiUjian)) { ?>
                <tr>
                    <td class="data-no"><?= $no++; ?></td>
                    <td class="data-pelajaran"><?= htmlspecialchars($nilai['pelajaran']); ?></td>
                    <td class="data-nilai"><?= htmlspecialchars($nilai['nilai_ujian']); ?></td>
                </tr>
            <?php } ?>
        </tbody>

        <tfoot>
            <tr>
                <th colspan="3">
                    <hr>
                </th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th class="foot">Total Nilai :<span class="sum-colon"><?= htmlspecialchars($data['total_nilai']) ?></span></th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th class="foot">Nilai Terendah :<span class="min-nilai"> &nbsp; <?= htmlspecialchars($data['nilai_terendah']) ?></span></th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th class="foot">Nilai Tertinggi :<span class="max-colon"></span><?= htmlspecialchars($data['nilai_tertinggi']) ?><span class="foot-nilai"></span></th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th class="foot">Rata-rata :<span class="avg-colon"></span><?= htmlspecialchars($data['nilai_rata']) ?><span class="foot-nilai"></span></th>
            </tr>
        </tfoot>
    </table>
    
    <script type="text/javascript">
        window.print()
    </script>
</body>

</html>

<script type="text/javascript">
    window.print()
</script>