<?php
declare(strict_types=1);

namespace Classes\Fileselector;

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Class Fileselector
 */
class Fileselector
{
    private $finder;
    private $dir;
    private $fileSystem;

    /**
     * Fileselector constructor.
     *
     * @param string $path путь до папки из которой нужно произвести выборку
     *
     * @throws \Exception
     */
    public function __construct($path)
    {
        $this->finder = new Finder();
        $this->dir = $path;
        $this->finder->files()->in($path);
        $this->fileSystem = new Filesystem();
    }

    /**
     * @return int возвращает количество файлов
     */
    public function filesCount(): int
    {
        return iterator_count($this->finder);
    }

    /**
     * @param string|array $filter   критерий для выборки по имени файла
     *                               может принимать маску типа *.txt, массив масок или регулярное выражение
     * @param string       $destPath путь до папки назначения
     */
    public function select($filter, $destPath): void
    {
        $this->finder->files()->name($filter);

        foreach ($this->finder as $file) {
            $relativePath = $file->getRelativePath();

            $this->fileSystem->mirror($this->dir . '/' . $relativePath, __DIR__
                . $destPath . '/' . $relativePath);
        }
    }
}
