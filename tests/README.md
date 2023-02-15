# Test suite for RestPkiPhpClient
# Configuration
For the tests to work, enter in the `config.php` and provide the filepath for a file larger than the upload threshold (=> 11MB). This file is not provided in this test suite.

## Running all tests
The tests are made with PHP Unit. In order to run all tests, please use the `phpunit.xml` configuration files with the following command

```
[pathToPhpUnit] --configuration phpunit.xml
```

Where `pathToPhpUnit` is the path where phpUnit is installed. If you are using composer (as recommended for this project), the path would be `vendor\bin\phpunit`.

## Running tests individually
If you wish to run tests individually, simply navigate to the `tests/` folder and run the desired test using PHPUnit. For instance, if you wish to run `AuthTests.php`, execute the following command:

```
[pathToPhpUnit] AuthTests.php
```