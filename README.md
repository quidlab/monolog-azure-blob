# monolog-azure-blob

![Test](https://github.com/blue32a/monolog-azure-blob/workflows/Test/badge.svg)

## About

Azure Blob Storage Handler for [Monolog](https://github.com/Seldaek/monolog).

## Installation

```console
$ composer require blue32a/monolog-azure-blob
```

## Usage

`Append Blob` must be created.

```php
$connection = 'DefaultEndpointsProtocol=https;AccountName=<name>;AccountKey=<key>';
$client = \MicrosoftAzure\Storage\Blob\BlobRestProxy::createBlobService($connection);

$logger = new \Monolog\Logger();

$handler = \Blue32a\Monolog\Handler\AzureBlobStorageHandler($client, 'container', 'append-blob.log');
$logger->pushHandler($handler);

// Write to Append Blob
$logger->info('Hello World!');
```
