<?php

session_start();

if (!isset($_SESSION['ssLogin'])) {
    header("location:../auth/login.php");
    exit();
}

require_once "../service/config.php";

function encryptDataWithPublicKey($data, $publicKeyPath)
{
    // Memuat kunci publik dari file
    $publicKeyContents = file_get_contents($publicKeyPath);
    if (!$publicKeyContents) {
        die("Gagal membaca kunci publik dari path: $publicKeyPath");
    }

    $publicKey = openssl_pkey_get_public($publicKeyContents);
    if (!$publicKey) {
        die("Gagal memuat kunci publik.");
    }

    // Melakukan enkripsi
    if (!openssl_public_encrypt($data, $encrypted, $publicKey)) {
        die("Gagal mengenkripsi data dengan kunci publik.");
    }
    return base64_encode($encrypted);
}

if (isset($_POST['simpan'])) {
    $sampul = htmlspecialchars($_FILES['image']['name']);
    $isbn = htmlspecialchars($_POST['isbn']);
    $judul = htmlspecialchars($_POST['judul']);
    $penerbit = htmlspecialchars($_POST['penerbit']);
    $tahunbuku = htmlspecialchars($_POST['tahun-buku']);

    $penerbit_encrypted = encryptDataWithPublicKey($penerbit, '../path/to/public_key.pem');

    $cekIsbn = mysqli_query($koneksi, "SELECT isbn FROM buku WHERE isbn = '$isbn'");
    if (mysqli_num_rows($cekIsbn) > 0) {
        header('location:add-buku.php?msg=cancel');
        exit;
    }

    if ($sampul != null) {
        $url = "add-buku.php";
        $sampul = uploadimg($url);
    } else {
        $sampul = "default-cover-book.png";
    }

    mysqli_query($koneksi, "INSERT INTO buku VALUES (null, '$sampul', '$isbn', '$judul', '$penerbit_encrypted', '$tahunbuku', 1, 'EMP', null, null);");

    echo "<script>
                alert('Buku baru berhasil disimpan');
                document.location.href = 'add-buku.php';
        </script>";
    return;
}

if (isset($_GET['pinjam'])) {
    $id = $_GET['id'];
    $statusCheckQuery = mysqli_query($koneksi, "SELECT dipinjam FROM buku WHERE id = $id");
    $status = mysqli_fetch_array($statusCheckQuery);

    if ($status['dipinjam'] == 'PND') {
        header("Location: buku.php?msg=sedangdipinjam");
        exit;
    } else {
        // Fetch the current stock
        $stockCheckQuery = mysqli_query($koneksi, "SELECT stok_buku FROM buku WHERE id = $id");
        $stock = mysqli_fetch_array($stockCheckQuery);

        if ($stock['stok_buku'] > 0) {
            // Stock is available, perform the borrowing logic
            $idUserPeminjam = $_SESSION['userId']; // This should be set to the ID of the current logged-in user
            $status = 'PND'; // Make sure to use single quotes for string values

            // $idUserPeminjam_encrypted = encryptDataWithPublicKey($idUserPeminjam, '../path/to/public_key.pem');

            // Corrected SQL UPDATE query with commas between column updates
            $updateQuery = "UPDATE buku SET 
                        id_user_peminjam = $idUserPeminjam,
                        stok_buku = stok_buku,
                        dipinjam = '$status' /* Enclose string values in quotes */
                        WHERE id = $id";

            if (mysqli_query($koneksi, $updateQuery)) {
                // Redirect with success message
                header("Location: buku.php?msg=borrowed");
                exit;
            } else {
                // Redirect with error message
                header("Location: buku.php?msg=error");
                exit;
            }
        } else {
            // No stock available, redirect with a different message
            header("Location: buku.php?msg=outofstock");
            exit;
        }
    }
}
if (isset($_GET['kembali'])) {
    $id = $_GET['id'];

    // Ambil stok buku saat ini
    $stockCheckQuery = mysqli_query($koneksi, "SELECT stok_buku FROM buku WHERE id = $id");
    $stock = mysqli_fetch_array($stockCheckQuery);

    $statusCheckQuery = mysqli_query($koneksi, "SELECT dipinjam FROM buku WHERE id = $id");
    $status = mysqli_fetch_array($statusCheckQuery);

    // Cek apakah stoknya 0 (buku sedang dipinjam)
    if ($stock['stok_buku'] == 0 && $status['dipinjam'] == 'ACC') {
        // Status untuk buku yang dikembalikan
        $status = 'EMP'; // Pastikan menggunakan tanda kutip untuk nilai string

        $cekIdPelayan = mysqli_query($koneksi, "SELECT id_user_pelayanan FROM buku WHERE id = $id");

        if ($cekIdPelayan == null) {
            // Perbarui query SQL dengan benar
            $updateQuery = "UPDATE buku SET 
            id_user_peminjam = null,
            stok_buku = stok_buku +1 ,
            dipinjam = '$status'
            WHERE id = $id";
        } else {
            // Perbarui query SQL dengan benar
            $updateQuery = "UPDATE buku SET 
            id_user_peminjam = null,
            id_user_pelayanan = null,
            stok_buku = stok_buku + 1,
            dipinjam = '$status'
            WHERE id = $id";
        }

        if (mysqli_query($koneksi, $updateQuery)) {
            // Alihkan dengan pesan sukses
            header("Location: buku.php?msg=returned");
            exit;
        } else {
            // Alihkan dengan pesan error
            header("Location: buku.php?msg=error");
            exit;
        }
    } else if ($stock['stok_buku'] == 1) {
        $statusCheckQuery = mysqli_query($koneksi, "SELECT dipinjam FROM buku WHERE id = $id");
        $status = mysqli_fetch_array($statusCheckQuery);

        if ($status['dipinjam'] == 'PND') {
            header("Location: buku.php?msg=waitingacc");
            exit;
        } else {
            // Jika stok buku lebih dari 0, tidak perlu diupdate

            header("Location: buku.php?msg=notborrowed");
            exit;
        }
    }
}

if (isset($_GET['terima'])) {
    $id = $_GET['id'];

    // Ambil stok buku saat ini
    $stockCheckQuery = mysqli_query($koneksi, "SELECT stok_buku FROM buku WHERE id = $id");
    $stock = mysqli_fetch_array($stockCheckQuery);

    $statusCheckQuery = mysqli_query($koneksi, "SELECT dipinjam FROM buku WHERE id = $id");
    $status = mysqli_fetch_array($statusCheckQuery);

    if ($stock['stok_buku'] == 1 && $status['dipinjam'] == 'PND') {
        // Status untuk buku yang dikembalikan
        $status = 'ACC'; // Pastikan menggunakan tanda kutip untuk nilai string
        $idUserPelayan = $_SESSION['userId'];

        // $idUserPelayan_encrypted = encryptDataWithPublicKey($idUserPelayan, '../path/to/public_key.pem');

        // Corrected SQL UPDATE query with commas between column updates
        $updateQuery = "UPDATE buku SET 
                        id_user_pelayanan = $idUserPelayan,
                        stok_buku = stok_buku - 1,
                        dipinjam = '$status' /* Enclose string values in quotes */
                        WHERE id = $id";

        if (mysqli_query($koneksi, $updateQuery)) {
            // Alihkan dengan pesan sukses
            header("Location: buku.php?msg=terima");
            exit;
        } else {
            // Alihkan dengan pesan error
            header("Location: buku.php?msg=error");
            exit;
        }
    }
}
