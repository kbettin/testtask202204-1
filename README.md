# Csv calculator

This project is a PHP CLI application that can be used to run basic calculations on the first two columns of a given csv file and write the result of the calculation into a new csv file if the calculated result value is greater than 0. 

## Requirements

- Composer
- PHP (7.4 or 8.0)

## Installation

After checkout from the repository run `composer install` to get the required vendor packages installed.

## Usage

### Parameters

#### Action

The application can handle the following actions:

* <b>plus</b> - to count sum of the numbers on each row in the file
* <b>minus</b> - to count difference between first number in the row and second
* <b>multiply</b> - to multiply the numbers on each row in the file
* <b>division</b> - to divide first number in the row and second

#### File

The input csv file to process. 

### Examples

    php console.php --action {action} --file {file}

or

    php console.php -a {action} -f {file}

## Test

Run `composer test` to have the application tested in your environment. It executes the following checks:

- phpcs - Coding standards (PSR-12)
- phpstan - Static code analysis
- phpunit - Unit- and Integration tests
