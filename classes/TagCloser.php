<?php
declare(strict_types=1);


namespace Classes\TagCloser;

use Symfony\Component\Finder\Finder;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Class TagCloser
 *
 * @package Classes\Encoder
 */
class TagCloser
{
    private $finder;
    private $dir;

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
    }

    public function closeTags(): void
    {
        foreach ($this->finder as $file) {
            $path = $this->dir . '/' . $file->getRelativePathname();

            $content = file_get_contents($path);

            $content = $this->autoCloseHtmlTags($content);

            file_put_contents($path, $content);
        }
    }

    /**
     *
     * Преобразовывает строку и закрывает незакрытые html теги
     *
     * @param string $html    -строка HTML, которую необходимо обработать
     * @param string $charset - кодировка , например 'UTF-8'
     *
     * @return string
     */
    private function autoCloseHtmlTags(string $html, string $charset = 'UTF-8'): string
    {
        $dom = new \DomDocument();
        $dom->loadHTML('<?xml encoding="' . $charset . '">' . $html);
        $html = $dom->saveHTML($dom->documentElement);
        return $html;
    }
}