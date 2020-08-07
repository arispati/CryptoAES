# CryptoAES
AES crypto library which can be used in php and javascript

## How to Install
- Install with composer
```bash
composer require arispati/crypto-aes
```

## How to Use
```php
use Arispati\CryptoAES;

// Set the secret key
$secretKey = 's3cr3tK3y'; // default secret key

// Initilize CryptoAES Class
$crypto = new CryptoAES($secretKey);

// Encrypt
$encrypted = $crypto->encrypt('Hello World');
// {"ct":"7aV+gq6hm69IO1\/pyjPliQ==","iv":"ece63be3d70d2e275396e3b82a43f425","s":"655cc08d5bcfd469"}

// Decrypt
$decrypted = $crypto->decrypt($encrypted);
// "Hello World"
```
