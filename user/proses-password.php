<?php

session_start();

if (!isset($_SESSION["ssLogin"])) {
    header('Location:../auth/login.php');
    exit;
}

require_once "../service/config.php";

if (isset($_POST['simpan'])) {
    $curPass = trim(htmlspecialchars($_POST['curPass']));
    $newPass = trim(htmlspecialchars($_POST['newPass']));
    $confirmPass = trim(htmlspecialchars($_POST['confirmPass']));

    $userName = $_SESSION['ssUser'];
    $queryUser = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$userName'");
    $data = mysqli_fetch_array($queryUser);

    if ($newPass != $confirmPass) {
        header('Location:../user/password.php?msg=err1');
        exit;
    }

    if (!password_verify($curPass, $data['password'])) {
        header('Location:../user/password.php?msg=err2');
        exit;
    } else {
        $pass = password_hash($newPass, PASSWORD_DEFAULT);
        $query = mysqli_query($koneksi, "UPDATE user SET password = '$pass' WHERE username = '$userName'");
        header('Location:../user/password.php?msg=updated');
        exit;
    }
}
