<?php
// Extract Python's modulus and exponent
$modulus = base64_encode($openssl_public_key_details['rsa']['n']);
$exponent = base64_encode($openssl_public_key_details['rsa']['e']);

// Pass $modulus and $exponent to your Python script

// In your Python script, load the PHP-generated modulus and exponent
$php_modulus = base64_decode("...");
$php_exponent = base64_decode("...");

$php_public_key = "-----BEGIN PUBLIC KEY-----\n" .
                  "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA{$php_modulus}" .
                  "{$php_exponent}" .
                  "AQAB\n-----END PUBLIC KEY-----";

// // Encrypt data using PHP's public key
// $encrypted_data = "";
// openssl_public_encrypt($data_to_encrypt, $encrypted_data, $php_key, OPENSSL_PKCS1_OAEP_PADDING);

// // Save or send $encrypted_data to Python
// // Load Python-generated public key
// $python_public_key = file_get_contents("public.pem");

// // Your data to be encrypted
// $data_to_encrypt = "Hello, Python!";

// // Encrypt data using Python's public key
// openssl_public_key = openssl_get_publickey($python_public_key);
// openssl_public_key_details = openssl_pkey_get_details($openssl_public_key);

// // Extract Python's modulus and exponent
// $modulus = base64_encode($openssl_public_key_details['rsa']['n']);
// $exponent = base64_encode($openssl_public_key_details['rsa']['e']);

// // Pass $modulus and $exponent to your Python script

// // In your Python script, load the PHP-generated modulus and exponent
// $php_modulus = base64_decode("...");
// $php_exponent = base64_decode("...");

// $php_public_key = "-----BEGIN PUBLIC KEY-----\n" .
//                   "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA{$php_modulus}" .
//                   "{$php_exponent}" .
//                   "AQAB\n-----END PUBLIC KEY-----";

// $php_key = openssl_get_publickey($php_public_key);
// openssl_public_key_details = openssl_pkey_get_details($php_key);

// // Encrypt data using PHP's public key
// $encrypted_data = "";
// openssl_public_encrypt($data_to_encrypt, $encrypted_data, $php_key, OPENSSL_PKCS1_OAEP_PADDING);

// // Save or send $encrypted_data to Python
?>