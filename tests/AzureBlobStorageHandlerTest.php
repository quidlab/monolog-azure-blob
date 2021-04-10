<?php

declare(strict_types=1);

namespace Tests;

use Blue32a\Monolog\Handler\AzureBlobStorageHandler;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class AzureBlobStorageHandlerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @return \Mockery\MockInterface|\Mockery\LegacyMockInterface
     */
    protected function createTargetMock()
    {
        return Mockery::mock(AzureBlobStorageHandler::class);
    }

    /**
     * @return \ReflectionClass
     */
    protected function createTargetReflection()
    {
        return new \ReflectionClass(AzureBlobStorageHandler::class);
    }

    /**
     * @return \Mockery\MockInterface|\Mockery\LegacyMockInterface
     */
    protected function createBlobRestProxyMock()
    {
        return Mockery::mock(BlobRestProxy::class);
    }

    /**
     * @test
     */
    public function testConstruct(): void
    {
        $clientMock = $this->createBlobRestProxyMock();
        $container  = 'example';
        $blob       = 'test.log';

        $targetMock = $this->createTargetMock()
            ->makePartial();

        $targetRef = $this->createTargetReflection();

        $targetConstructorRef = $targetRef->getConstructor();
        $targetConstructorRef->invoke($targetMock, $clientMock, $container, $blob);

        $clientPropertyRef = $targetRef->getProperty('client');
        $clientPropertyRef->setAccessible(true);
        $this->assertEquals($clientMock, $clientPropertyRef->getValue($targetMock));

        $containerPropertyRef = $targetRef->getProperty('container');
        $containerPropertyRef->setAccessible(true);
        $this->assertEquals($container, $containerPropertyRef->getValue($targetMock));

        $blobPropertyRef = $targetRef->getProperty('blob');
        $blobPropertyRef->setAccessible(true);
        $this->assertEquals($blob, $blobPropertyRef->getValue($targetMock));
    }

    /**
     * @test
     */
    public function testWrite(): void
    {
        $container = 'example';
        $blob      = 'test.log';
        $formatted = 'formatted';

        $clientMock = $this->createBlobRestProxyMock();
        $clientMock
            ->shouldReceive('appendBlock')
            ->once()
            ->with($container, $blob, $formatted);

        $targetMock = $this->createTargetMock()
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $targetRef = $this->createTargetReflection();

        $clientPropertyRef = $targetRef->getProperty('client');
        $clientPropertyRef->setAccessible(true);
        $clientPropertyRef->setValue($targetMock, $clientMock);

        $containerPropertyRef = $targetRef->getProperty('container');
        $containerPropertyRef->setAccessible(true);
        $containerPropertyRef->setValue($targetMock, $container);

        $blobPropertyRef = $targetRef->getProperty('blob');
        $blobPropertyRef->setAccessible(true);
        $blobPropertyRef->setValue($targetMock, $blob);

        $targetMock->write(['formatted' => $formatted]);
    }
}
