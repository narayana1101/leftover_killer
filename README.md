## Instructions 
1) Setup the server and MySQL database
2) After the server and database are setup, this repository can be cloned into the server.
3) The db_config file should be changed to point to the database, and the connection details.
4) After successful configuration, these scripts can be exposed via the server.
5) The clients can execute the exposed scripts.

The test folder contains the Unit tests for these scripts.
The codeCoverageResult folder contains the code coverage reports for the unti tests.


## Test Instruction
1) Setup the PHPUnit, Xdebug, and Composer.
2) Setup the test files and target files for testing
3) Use the command: ./vendor/bin/phpunit --coverage-html
