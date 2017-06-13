Upgrading from REST PKI client lib 1.x
======================================

This library was previosly shipped as a single file named `RestPki.php` ("version 1.x"). Follow the steps below
to upgrade your application to use the new version of the library, now shipped as a composer package:

1. Edit your `composer.json` file and remove the dependency on the ~~**guzzlehttp/guzzle**~~ package add a dependency on
   the **lacuna/restpki-client** package:

	```json
	"require": {
		"lacuna/restpki-client": "^2.1.0"
	}
	```

2. In a command prompt, navigate to your application's folder and run:

	```shell
	composer update
	```
	
3. Remove the inclusion of the `RestPki.php` file of all your PHP files. Your files should now only
   have the standard inclusion of the autoloader, for instance:
   
	```PHP
	require __DIR__ . '/vendor/autoload.php';
	```
   
4. Change the namespace of any `use` statements or any fully qualified class names of classes in the
   *REST PKI client lib* from `Lacuna` to `Lacuna\RestPki`
   
	```php
	use Lacuna\RestPki\PadesSignatureStarter;
	use Lacuna\RestPki\StandardSignaturePolicies;
	use Lacuna\RestPki\PadesMeasurementUnits;
	use Lacuna\RestPki\StandardSecurityContexts;
	```
	
	```php
	$signatureStarter->securityContext = \Lacuna\RestPki\StandardSecurityContexts::PKI_BRAZIL;
	```

Apart from the namespace change, the new version of the library is 100% compatible with the older RestPki.php file.
