# Vault

The Vault component securely stores secrets using pluggable storage backends and encryption with key rotation and support for additional authenticated data (AAD).

## Basic Usage

```php
use SonsOfPHP\Component\Vault\Cipher\OpenSSLCipher;
use SonsOfPHP\Component\Vault\KeyRing\InMemoryKeyRing;
use SonsOfPHP\Component\Vault\Storage\InMemoryStorage;
use SonsOfPHP\Component\Vault\Vault;

$keyRing = new InMemoryKeyRing(['v1' => '32_byte_master_key_example!!'], 'v1');
$vault   = new Vault(new InMemoryStorage(), new OpenSSLCipher(), $keyRing);
$vault->set('db_password', 'secret', ['app']);
$secret = $vault->get('db_password', ['app']);

// Store a new version of the secret
$vault->set('db_password', 'new-secret', ['app']);
$oldSecret = $vault->get('db_password', ['app'], 1); // retrieve version 1

// Rotate the master key
$vault->rotateKey('v2', 'another_32_byte_master_key!!');

// Store non-string data
$vault->set('config', ['user' => 'root']);
$config = $vault->get('config');
```
