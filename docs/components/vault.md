# Vault

The Vault component securely stores secrets using pluggable storage backends and encryption with key rotation, versioning, and additional authenticated data (AAD).

## Setup

```php
use SonsOfPHP\Component\Vault\Cipher\OpenSSLCipher;
use SonsOfPHP\Component\Vault\KeyRing\InMemoryKeyRing;
use SonsOfPHP\Component\Vault\Marshaller\JsonMarshaller;
use SonsOfPHP\Component\Vault\Storage\InMemoryStorage;
use SonsOfPHP\Component\Vault\Vault;

$storage    = new InMemoryStorage();
$cipher     = new OpenSSLCipher();
$keyRing    = new InMemoryKeyRing(['v1' => '32_byte_master_key_example!!'], 'v1');
$marshaller = new JsonMarshaller();
$vault      = new Vault($storage, $cipher, $keyRing, $marshaller);
```

## Storing and Retrieving Secrets

```php
$vault->set('db_password', 'secret');
$secret = $vault->get('db_password');
```

## Using Additional Authenticated Data

```php
$vault->set('token', 'secret', ['app']);
$secret = $vault->get('token', ['app']);
```

## Versioned Secrets

```php
$vault->set('db_password', 'old-secret');
$vault->set('db_password', 'new-secret');

// Retrieve specific version
$old = $vault->get('db_password', [], 1);
```

## Rotating Keys

```php
$vault->rotateKey('v2', 'another_32_byte_master_key!!');
```

Secrets encrypted with previous keys remain accessible because the key ring keeps old keys.

## Storing Non-String Data

```php
$vault->set('config', ['user' => 'root']);
$config = $vault->get('config');
```

## Custom Marshallers

Vault uses a marshaller to convert secrets to strings before encryption. The default `JsonMarshaller` handles arrays and scalars, but you can provide your own implementation of `MarshallerInterface` for advanced use cases.
