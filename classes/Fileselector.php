<?php
declare(strict_types=1);

namespace Classes\Fileselector;

require_once __DIR__ . '/../vendor/autoload.php';

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Class Fileselector
 */
class Fileselector
{
    private $finder;
    private $dir;
    private $logger;
    private $fileSystem;

    public function __construct($path)
    {
        $this->finder = new Finder();
        $this->dir = $path;
        $this->finder->files()->in($path);
        $this->fileSystem = new Filesystem();

        $log = new Logger('name');
        $this->logger = $log->pushHandler(new StreamHandler('logs/info.log', Logger::INFO));
    }

    public function filesCount()
    {
        $count = (iterator_count($this->finder) + 1);
        $this->logger->info('Количество файлов:', ['до обработки' => $count]);
    }

    public function select($filter): void
    {
        $content_files = $this->finder;
        $content_files->files()->name($filter);

        foreach ($content_files as $file) {
            $relativePath = $file->getRelativePath();

            $this->fileSystem->mirror(__DIR__ . '/../spart/' . $relativePath, __DIR__
                . '/../../archive/selected/' . $relativePath);
        }
        $count = (iterator_count($content_files) + 1);
        $this->logger->info('Количество файлов:', ['выбрано файлов' => $count,
            'критерий отбора' => $filter]);
    }
}
