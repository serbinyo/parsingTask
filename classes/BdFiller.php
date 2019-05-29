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
    private $db;
    private $finder;
    private $dir;

    /**
     * BdFiller constructor.
     *
     * @param \PDO   $db   экземпляр подключения к базе данных
     * @param string $path путь до папки из которой нужно произвести выборку
     */
    public function __construct($db, $path)
    {
        $this->db = $db;
        $this->finder = new Finder();
        $this->dir = $path;
        $this->finder->files()->in($path);
    }

    public function fill()
    {
        foreach ($this->finder as $file) {
            $relativePathName = $file->getRelativePathname();
            $h1 = null;
            $body = '';
            $copy = null;

            $path = $this->dir . '/' . $relativePathName;

            //Получаем дату в формате timestampt
            $relativePath = explode('/', $relativePathName);
            $timestamp = strtotime($relativePath[0]);

            $content = file_get_contents($path);

            # Получаем копипаст
            preg_match('#(©|&copy;).*(?=<\/)|(©|&copy;).*$#u', $content, $cpocket);
            if (!empty($cpocket)) {
                foreach ($cpocket as $item) {
                    $item = rtrim($item, '©');
                    $item = str_replace('', ' ', $item);
                    $copy .= strip_tags($item);
                }
            }

            # Получаем заголовок страницы
            preg_match('#(<h1.*?<\/h1>)#si', $content, $hpocket);

            if (!empty($hpocket)) {
                $content = preg_replace('#(<h1.*?<\/h1>)#si', '', $content, 1);
                $h1 = strip_tags($hpocket[1]);
            }

            # Получаем контент <p><h1><h2><h3><h4><ul>
            preg_match_all('#(<p.*?<\/p>)|(<h1.*?<\/h1>)|(<h2.*?<\/h2>)|' .
                '(<h3.*?<\/h3>)|(<h4.*?<\/h1>)|(<ul.*?<\/ul>)#si', $content, $ppocket);
            if (!empty($ppocket)) {
                foreach ($ppocket as $items) {
                    foreach ($items as $item) {
                        # Пропускаем копирайты, остальное записываем в контент
                        if (strpos($item, '©') === false) {
                            $body .= strip_tags($item, '<p><h1></h1><h2><h3><h4><ul><li>');
                        }
                        //удаляем лишние пробелы
                        $body = preg_replace('/(\s)+/', ' ', $body);
                        //удаляем пустые теги <p>
                        $body = preg_replace('/<p[^>]*>\s*<\/p[^>]*>/', '', $body);
                        //echo $body;
                    }
                }
            }

            if ($body !== '') {
                $body = str_replace('', ' ', $body);
                $this->db->exec("INSERT INTO retail VALUES (null, '$h1', '$body', '$copy', '$timestamp')");
            }
        }
    }
}