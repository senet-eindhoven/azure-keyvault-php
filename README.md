# Azure KeyVault PHP

# Install

```
composer require senet-eindhoven/azure-keyvault-php
```
# Usage

```php
require 'vendor/autoload.php'

use Senet\AzureKeyVault\Secret;

$keyvault = new Secret(
    'my-tenant',
    'https://my-keyvault-entity.vault.azure.net/',
    'clienId',
    'clientSecret',
    new \GuzzleHttp\Client(), // Any PSR ClientInterface
);
echo $keyvault->getSecret('my-secret')->getValue();
```

## Support

This library does not support the full usage of the `Azure KeyVault` yet. Below a list of the supported service

| Type        | Support    | Comment                                             |
|-------------|------------|-----------------------------------------------------|
| Secret      | Partially  | Only retrieving and listing of Secrets is supported |
| Certificate | No         |                                                     |
| Keys        | No         |                                                     |

## External Api documentation

https://docs.microsoft.com/en-us/rest/api/keyvault/
