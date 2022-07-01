# Azure KeyVault PHP

# Install

```
composer require senet/azure-keyvault-php
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
    new \GuzzleHttp\Client(),
);
echo $keyvault->getSecret('my-secret')->getValue();
```

## Api documentation
https://docs.microsoft.com/en-us/rest/api/keyvault/
