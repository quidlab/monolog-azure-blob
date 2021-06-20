<?php

declare(strict_types=1);

namespace Blue32a\Monolog\Handler;

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class AzureBlobStorageHandler extends AbstractProcessingHandler
{
    /** @var BlobRestProxy */
    protected $client;

    /** @var string */
    protected $container;

    /** @var string */
    protected $blob;

    /**
     * @param int|string $level
     */
    public function __construct(
        BlobRestProxy $client,
        string $container,
        string $blob,
        $level = Logger::DEBUG,
        bool $bubble = true
    ) {
        $this->client    = $client;
        $this->container = $container;
        $this->blob      = $blob;

        parent::__construct($level, $bubble);
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record): void
    {
        $this->client->appendBlock($this->container, $this->blob, $record['formatted']);
    }
}
