<?php
session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("Location:../auth/login.php");
    exit;
}

require_once "../service/config.php";
$title = "Update Guru - SMA Dogon";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

$publicKeyPath = '../security/public_key.pem';

function encryptDataWithPublicKey($data, $publicKeyPath)
{
    if (!file_exists($publicKeyPath)) {
        die("File kunci publik tidak ditemukan: $publicKeyPath");
    }
    $publicKey = file_get_contents($publicKeyPath);
    if (!$publicKey) {
        die("Gagal membaca kunci publik.");
    }
    if (!openssl_public_encrypt($data, $encrypted, $publicKey)) {
        die("Gagal mengenkripsi data dengan kunci publik.");
    }
    return base64_encode($encrypted); // Encode ke base64 agar bisa disimpan sebagai string
}

function decryptDataWithPrivateKey($encryptedData, $privateKeyPath)
{
    if (!file_exists($privateKeyPath)) {
        die("File kunci privat tidak ditemukan: $privateKeyPath");
    }
    $privateKey = file_get_contents($privateKeyPath);
    $privateKeyResource = openssl_pkey_get_private($privateKey);
    if (!$privateKeyResource) {
        die("Gagal memuat kunci privat.");
    }
    openssl_private_decrypt(base64_decode($encryptedData), $decrypted, $privateKeyResource);
    return $decrypted;
}

$id = $_GET['id'];

$queryGuru = mysqli_query($koneksi, "SELECT * FROM guruku WHERE id = '$id'");
$data = mysqli_fetch_array($queryGuru);


?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Update Guru</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="guru.php">Guru</a></li>
                <li class="breadcrumb-item active">Update Guru</li>
            </ol>
            <form action="proses-guru.php" method="POST" enctype="multipart/form-data">

                <div class="card">
                    <div class="card-header">
                        <span class="h2 my-2"><i class="fa-solid fa-pen-to-square"></i> Update Guru</span>
                        <button type="submit" name="update" class="btn btn-primary float-end"><i class="fa-solid fa-floppy-disk"></i> Update</button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                <div class="mb-3 row">
                                    <label for="nip" class="col-sm-2 col-form-label">NIP</label>
                                    <label for="nip" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <input type="text" name="nip" pattern="[0-9]{18,}" title="minimal 18 angka" class="form-control ps-2
                                    border-0 border-bottom" value="<?= $data['nip'] ?>" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                                    <label for="nama" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <input type="text" name="nama" class="form-control ps-2
                                    border-0 border-bottom" value="<?= $data['nama'] ?>" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <?php
                                    $privateKeyPath = '../path/to/private_key.pem';
                                    $decryptedTelepon = decryptDataWithPrivateKey($data['telepon'], $privateKeyPath);
                                    $decryptedAlamat = decryptDataWithPrivateKey($data['alamat'], $privateKeyPath);

                                    ?>
                                    <label for="telepon" class="col-sm-2 col-form-label">Telepon</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <input type="tel" name="telepon" pattern="[0-9]{5,}" title="minimal 5 angka" class="form-control ps-2
                                    border-0 border-bottom" value="<?= $decryptedTelepon ?>" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="agama" class="col-sm-2 col-form-label">Agama</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <select name="agama" id="agama" class="form-select border-0 border-bottom" required>
                                            <?php
                                            $agama = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha'];
                                            foreach ($agama as $rlg) {
                                                if ($data['agama'] == $rlg) { ?>
                                                    <option value="<?= $rlg ?>" selected><?= $rlg ?>
                                                    </option>
                                                <?php } else { ?>
                                                    <option value="<?= $rlg ?>"><?= $rlg ?>
                                                    </option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <textarea name="alamat" id="alamat" cols="30" rows="3" class="form-control" required><?= $decryptedAlamat ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 text-center px-5">
                                <input type="hidden" name="fotoLama" value="<?= $data['foto'] ?>">
                                <img src="../asset/image/<?= $data['foto'] ?>" class="mb-3 rounded-circle" width="40%" alt="">
                                <input type="file" name="image" class="form-control form-control-sm">
                                <small class="text-secondary">Pilih foto PNG, JPG atau JPEG dengan ukuran maximal 1
                                    MB</small>
                                <div><small class="text-secondary">width = height</small></div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <?php
    require_once "../template/footer.php";

    ?>