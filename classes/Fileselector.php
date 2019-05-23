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
     * @param string|array $filter критерий для выборки по имени файла
     *                               может принимать маску типа *.txt, массив масок или регулярное выражение
     * @param string $destPath путь до папки назначения
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

    public function setUtfCharset(): void
    {

        foreach ($this->finder as $file) {

            $relativePath = __DIR__ . '/../../archive/selected/' . $file->getRelativePathname();
            echo $relativePath . "\n";
            $content = file_get_contents($relativePath);

            $charset = $this->detectCurCharset($content);
            //echo $charset . "\n";

            if ($charset !== 'UTF-8') {
                $content = mb_convert_encoding($content, 'UTF-8', $charset);
                file_put_contents($relativePath, $content);
            }

        }
    }

    public function detectCurCharset($content)
    {

        $charset = '';
        $isUtf = mb_detect_encoding($content, 'UTF-8', true);

        if (!$isUtf) {
            $charsets = [
                'KOI8-R' => 0,
                'windows-1251' => 0,
            ];

            for ($i = 0, $length = strlen($content); $i < $length; $i++) {

                $char = ord($content[$i]);

                if ($char < 128) {
                    continue;
                }

                //KOI8-R
                if ($char > 191 && $char < 223) {
                    $charsets['KOI8-R'] += LOWERCASE;
                }

                if ($char > 222 && $char < 256) {
                    $charsets['KOI8-R'] += UPPERCASE;
                }

                //windows-1251
                if ($char > 223 && $char < 256) {
                    $charsets['windows-1251'] += LOWERCASE;
                }

                if ($char > 191 && $char < 224) {
                    $charsets['windows-1251'] += UPPERCASE;
                }

            }

        } else {
            $charset = 'UTF-8';
        }

        if ($charset !== 'UTF-8') {
            arsort($charsets);
            $charset = key($charsets);
        }

        return $charset;
    }
}
