<?php
session_start();

if (!isset($_SESSION['ssLogin'])) {
    header('Location:../auth/login.php');
    exit;
}

require_once "../service/config.php";
$title = "Ganti Password - SMA Dogon";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

?>


<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Password</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php" style="text-decoration: none; color:black;">Home</a></li>
                <li class="breadcrumb-item active">Ganti Password</li>
            </ol>

            <div class="card">
                <div class="card-header">
                    <span class="h5 my-2"><i class="fa-solid fa-pen-to-square"></i>Ganti Password</span>
                    <button type="submit" name="simpan" class="btn btn-primary float-end"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                    <button type="reset" name="reset" class="btn btn-danger float-end me-1"><i class="fa-solid fa-xmark"></i> Simpan</button>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Special title treatment</h5>
                    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                    <a href="#" class="btn btn-primary">Go somewhere</a>
                </div>
            </div>
        </div>
    </main>
</div>

<?php

require_once "../template/footer.php";

?>