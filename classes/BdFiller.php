<?php
declare(strict_types=1);

namespace Classes\BdFiller;

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Finder\Finder;

/**
 * Class BdFiller
 *
 * @package Classes\BdFiller
 */
class BdFiller
{
    private $bd;
    private $finder;
    private $dir;

    /**
     * BdFiller constructor.
     *
     * @param \PDO   $bd   экземпляр подключения к базе данных
     * @param string $path путь до папки из которой нужно произвести выборку
     */
    public function __construct($bd, $path)
    {
        $this->bd = $bd;
        $this->finder = new Finder();
        $this->dir = $path;
        $this->finder->files()->in($path);
    }

    public function fill()
    {
        foreach ($this->finder as $file) {
            $h1 = null;
            $body = '';

            $path = $this->dir . '/' . $file->getRelativePathname();

            $content = file_get_contents($path);

            preg_match('#(<h1.*?<\/h1>)#si', $content, $hpocket);

            preg_match_all('#(<p.*?<\/p>)#si', $content, $ppocket);

            if (!empty($hpocket)) {
                $h1 = strip_tags($hpocket[1]);
                echo $h1 . PHP_EOL;
            }

            if (!empty($ppocket)) {
                foreach ($ppocket as $items) {
                    foreach ($items as $item) {
                        $body .= strip_tags($item, '<p>') . PHP_EOL;
                        //preg_replace('/(\s)+/', ' ', $body);
                        echo $body;
                    }
                }
            }


        }
    }
}