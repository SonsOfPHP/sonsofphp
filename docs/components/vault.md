# Vault

The Vault component securely stores secrets using pluggable storage backends and encryption with key rotation and support for additional authenticated data (AAD).

## Basic Usage

```php
use SonsOfPHP\Component\Vault\Cipher\OpenSSLCipher;
use SonsOfPHP\Component\Vault\Storage\InMemoryStorage;
use SonsOfPHP\Component\Vault\Vault;

$keys  = ['v1' => '32_byte_master_key_example!!'];
$vault = new Vault(new InMemoryStorage(), new OpenSSLCipher(), $keys, 'v1');
$vault->set('db_password', 'secret', 'app');
$secret = $vault->get('db_password', 'app');

// Rotate the master key
$vault->rotateKey('v2', 'another_32_byte_master_key!!');

// Store non-string data
$vault->set('config', ['user' => 'root']);
$config = $vault->get('config');
```
