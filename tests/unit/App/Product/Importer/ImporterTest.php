<?php

namespace Unit\App\Product\Importer;

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger as Logger;
use App\Product\Importer;

class ImporterTest extends TestCase
{
    /**
     * @var Logger|\Mockery\MockInterface
     */
    private $loggerMock;

    public function setUp(): void
    {
        $this->loggerMock = \Mockery::mock(Logger::class);
    }

    public function testIfImportReturnsNothingWhenImporterWasNotFound(): void
    {
        $xmlImporterMock = \Mockery::mock(Importer\ImporterInterface::class);
        $xmlImporterMock->shouldReceive('import')
            ->once()
            ->with('file_path.foo')
            ->andThrow(
                Importer\Exception\ExtensionNotSupportedException::class,
                'Extension `csv` not supported. Supported extension is `xml`'
            );

        $this->loggerMock->shouldReceive('error')
            ->once()
            ->with('Importer for `file_path.foo` file was not found');

        $this->loggerMock->shouldReceive('info')
            ->once()
            ->with(
                'Importer does not match to given file `file_path.foo`, skipping.',
                [
                    'previousExceptionMessage' => 'Extension `csv` not supported. Supported extension is `xml`',
                ]
            );

        $importer = new Importer\Importer(
            [
                $xmlImporterMock,
            ],
            $this->loggerMock
        );

        $importer->import('file_path.foo');
        $this->assertTrue(true);
    }

    public function testIfImportReturnsNothingWhenImporterWasFoundAsFirst(): void
    {
        $xmlImporterMock = \Mockery::mock(Importer\ImporterInterface::class);
        $xmlImporterMock->shouldReceive('import')
            ->once()
            ->with('file_path.xml');

        $importer = new Importer\Importer(
            [
                $xmlImporterMock,
            ],
            $this->loggerMock
        );

        $importer->import('file_path.xml');
        $this->assertTrue(true);
    }

    public function testIfImportReturnsNothingWhenImporterWasFoundAsNth(): void
    {
        $xmlImporterMock = \Mockery::mock(Importer\ImporterInterface::class);
        $xmlImporterMock->shouldReceive('import')
            ->once()
            ->with('file_path.xml');

        $csvImporterMock = \Mockery::mock(Importer\ImporterInterface::class);
        $csvImporterMock->shouldReceive('import')
            ->once()
            ->with('file_path.xml');

        $this->loggerMock->shouldReceive('info')
            ->once()
            ->with(
                'Importer for `file_path.xml` file was not found',
                [
                    'previousExceptionMessage' => 'Extension `xml` not supported. Supported extension is `csv`',
                ]
            );

        $importer = new Importer\Importer(
            [
                $csvImporterMock,
                $xmlImporterMock,
            ],
            $this->loggerMock
        );

        $importer->import('file_path.xml');
        $this->assertTrue(true);
    }
}
