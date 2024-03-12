<?php
function encrypt3DES($data, $key)
{
    $iv = '12345678'; // Initial Vector, dapat disesuaikan sesuai kebutuhan

    // Enkripsi
    $encryptedData = openssl_encrypt($data, 'des-ede3-cbc', $key, 0, $iv);

    return $encryptedData;
}

// Fungsi untuk mendekripsi menggunakan 3DES
function decrypt3DES($encryptedData, $key)
{
    $iv = '12345678'; // Initial Vector, harus sesuai dengan IV yang digunakan saat enkripsi

    // Dekripsi
    $decryptedData = openssl_decrypt($encryptedData, 'des-ede3-cbc', $key, 0, $iv);

    return $decryptedData;
}

// Contoh penggunaan
$dataToEncrypt = "Hello, World!";
$encryptionKey = "YourEncryptionKey"; // Gantilah dengan kunci yang lebih aman

// Enkripsi
$encryptedData = encrypt3DES($dataToEncrypt, $encryptionKey);
echo "Encrypted: $encryptedData\n";

// Dekripsi
$decryptedData = decrypt3DES($encryptedData, $encryptionKey);
echo "Decrypted: $decryptedData\n";
