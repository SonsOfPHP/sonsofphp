# Vault

The Vault component securely stores secrets using pluggable storage backends and encryption.

## Basic Usage

```php
use SonsOfPHP\Component\Vault\Cipher\OpenSSLCipher;
use SonsOfPHP\Component\Vault\Storage\InMemoryStorage;
use SonsOfPHP\Component\Vault\Vault;

$vault = new Vault(new InMemoryStorage(), new OpenSSLCipher(), 'encryption-key');
$vault->set('db_password', 'secret');
$secret = $vault->get('db_password');
```
