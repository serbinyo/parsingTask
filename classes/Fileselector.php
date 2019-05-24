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
     * @param null|string|array $filter
     *
     * @return int возвращает количество файлов
     */
    public function filesCount($filter = null): int
    {
        if (isset($filter)) {
            $this->finder->files()->name($filter);
        }

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


    public function cleanUnnecessary(): void
    {
        foreach ($this->finder as $file) {
            $path = $this->dir . '/' . $file->getRelativePathname();

            # получаем контент по ссылке
            $content = file_get_contents($path);

            # убираем все лишнее
            $content = preg_replace('#(<head.*?<\/head>)|(<script.*?<\/script>)|'
                . '(<noscript.*?<\/noscript>)|(<style.*?<\/style>)|'
                . '(<footer.*?<\/footer>)#si', '', $content);

            $content = preg_replace('#(<object.*?<\/object>)|(<param.*?>)|'
                . '(<embed.*?<\/embed>)|(<form.*?<\/form>)|'
                . '(<noindex.*?<\/noindex>)#si', '', $content);

            $content = preg_replace('#(<map.*?<\/map>)|(<\/body>)|'
                . '(<html.*?>)|(<\/html>)|(<body.*?>)|'
                . '(<\!--.*?-->)|(<\!doctype.*?>)#si', '', $content);

            # поиск тега h1 в тексте
            # если нашли, удаляем все, что выше тега h1
            if (mb_stripos($content, '<h1') > 0) {
                preg_match('#(<h1.*)#si', $content, $pocket);
                $content = $pocket[1];
            }

            # записываем контент
            file_put_contents($path, $content);
        }
    }
}
