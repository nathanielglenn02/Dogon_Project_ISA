<?php

session_start();

if (!isset($_SESSION['ssLogin'])) {
    header("location:../auth/login.php");
    exit();
}

require_once "../service/config.php";

if (isset($_POST['simpan'])) {
    $sampul = htmlspecialchars($_FILES['image']['name']);
    $isbn = htmlspecialchars($_POST['isbn']);
    $judul = htmlspecialchars($_POST['judul']);
    $penerbit = htmlspecialchars($_POST['penerbit']);
    $tahunbuku = htmlspecialchars($_POST['tahun-buku']);

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

    mysqli_query($koneksi, "INSERT INTO buku VALUES (null, '$sampul', '$isbn', '$judul', '$penerbit', '$tahunbuku', 1, 'EMP', null, null);");

    echo "<script>
                alert('Tambah buku berhasil!');
                document.location.href = 'buku.php';
        </script>";
    return;
}

if (isset($_GET['simpan'])) {
    $id = $_GET['id'];

    // Fetch the current stock
    $stockCheckQuery = mysqli_query($koneksi, "SELECT stok_buku FROM buku WHERE id = $id");
    $stock = mysqli_fetch_array($stockCheckQuery);

    if ($stock['stok_buku'] > 0) {
        // Stock is available, perform the borrowing logic
        $idUserPeminjam = $_SESSION['userId']; // This should be set to the ID of the current logged-in user
        $status = 'PND'; // Make sure to use single quotes for string values

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
if (isset($_GET['kembali'])) {
    $id = $_GET['id'];

    // Ambil stok buku saat ini
    $stockCheckQuery = mysqli_query($koneksi, "SELECT stok_buku FROM buku WHERE id = $id");
    $stock = mysqli_fetch_array($stockCheckQuery);

    // Cek apakah stoknya 0 (buku sedang dipinjam)
    if ($stock['stok_buku'] == 0) {
        // Status untuk buku yang dikembalikan
        $status = 'EMP'; // Pastikan menggunakan tanda kutip untuk nilai string

        $cekIdPelayan = mysqli_query($koneksi, "SELECT id_user_pelayanan FROM buku WHERE id = $id");

        if ($cekIdPelayan == null) {
            // Perbarui query SQL dengan benar
            $updateQuery = "UPDATE buku SET 
            id_user_peminjam = null,
            id_user_pelayan = null,
            stok_buku = stok_buku + 1,
            dipinjam = '$status'
            WHERE id = $id";
        } else {
            // Perbarui query SQL dengan benar
            $updateQuery = "UPDATE buku SET 
            id_user_peminjam = null,
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
    } else {
        // Jika stok buku lebih dari 0, tidak perlu diupdate

        header("Location: buku.php?msg=notborrowed");
        exit;
    }
}

if (isset($_GET['terima'])) {
    $id = $_GET['id'];

    // Ambil stok buku saat ini
    $stockCheckQuery = mysqli_query($koneksi, "SELECT stok_buku FROM buku WHERE id = $id");
    $stock = mysqli_fetch_array($stockCheckQuery);

    if ($stock['stok_buku'] == 1 && $status == 'PND') {
        // Status untuk buku yang dikembalikan
        $status = 'ACC'; // Pastikan menggunakan tanda kutip untuk nilai string

        $cekIdPelayan = mysqli_query($koneksi, "SELECT id_user_pelayanan FROM buku WHERE id = $id");

        if ($cekIdPelayan == null) {
            // Perbarui query SQL dengan benar
            $updateQuery = "UPDATE buku SET 
            id_user_pelayan = '$cekIdPelayan',
            stok_buku = stok_buku - 1,
            dipinjam = '$status'
            WHERE id = $id";
        } else {
            // Perbarui query SQL dengan benar
            $updateQuery = "UPDATE buku SET 
            stok_buku = stok_buku - 1,
            dipinjam = '$status'
            WHERE id = $id";
        }

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
