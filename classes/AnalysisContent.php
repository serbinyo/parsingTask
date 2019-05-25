<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: perale
 * Date: 22.05.19
 * Time: 12:15
 */

namespace Classes;

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;

class AnalysisContent
{
    /**
     * @param string $dir
     * @param string $linkToFolder
     *
     */
    public static function deleteUnnecessaryTags(string $dir, string $linkToFolder)
    {
        $imgMaskName = [
            '*.jpg',
            '*.JPG',
            '*.png',
            '*.PNG',
            '*.gif',
            '*.GIF',
            '*.ico',
            '*.ICO',
            '*.pdf',
            '*.PDF'
        ];
        $finder = new Finder();
        $finder->files()->in($dir . $linkToFolder)->notName($imgMaskName);

        # проходим по всем файлам
        foreach ($finder as $file) {
            $link = $file->getRelativePathname();

            # получаем контент по ссылке
            $html = $file->getContents();

            # убираем все лишнее
            $html = preg_replace('#(<head.*?<\/head>)|(<script.*?<\/script>)|'
                . '(<noscript.*?<\/noscript>)|(<style.*?<\/style>)|'
                . '(<footer.*?<\/footer>)#si', '', $html);

            $html = preg_replace('#(<object.*?<\/object>)|(<param.*?>)|'
                . '(<embed.*?<\/embed>)|(<form.*?<\/form>)|'
                . '(<noindex.*?<\/noindex>)#si', '', $html);

            $html = preg_replace('#(<map.*?<\/map>)|(<\/body>)|'
                . '(<html.*?>)|(<\/html>)|(<body.*?>)|'
                . '(<\!--.*?-->)|(<\!doctype.*?>)#si', '', $html);

            # поиск тега h1 в тексте
            # если нашли, удаляем все, что выше тега h1
            if (mb_stripos($html, '<h1') > 0) {
                preg_match('#(<h1.*)#si', $html, $pocket);
                $html = $pocket[1];
            }

            $fileSystem = new Filesystem();
            $fileSystem->dumpFile($dir . '/archive_edit/' . $link, $html);
        }
    }

    public static function getContentFirst(string $dir, string $linkToFolder)
    {
        $imgMaskName = [
            '*.jpg',
            '*.JPG',
            '*.png',
            '*.PNG',
            '*.gif',
            '*.GIF',
            '*.ico',
            '*.ICO',
            '*.pdf',
            '*.PDF'
        ];
        $finder = new Finder();
        $finder->files()->in($dir . $linkToFolder)->notName($imgMaskName);

        # проходим по всем файлам
        foreach ($finder as $file) {
            $link = $file->getRelativePathname();

            # получаем контент по ссылке
            $html = $file->getContents();

            $masTagsRegex = 'p|h1|h2|h3|h4|h5|span';
            preg_match_all('#(<(' . $masTagsRegex . ').*?<\/(' . $masTagsRegex . ')>)#si', $html, $tags, PREG_PATTERN_ORDER);


            # возвращаем данные только с нужными тегами
            $masTagsStrip = '<p><h1><h2><h3><h4><h5><span><ul><ol><li><br>';
            $result = strip_tags(implode('', $tags[0]), $masTagsStrip);

            $fileSystem = new Filesystem();
            $fileSystem->dumpFile($dir . '/archive_edit/' . $link, $result);
        }
    }












    /*
      #region date
            // получили название папки с датой формата "20151117145645"
            preg_match('#(\d{14}.*?)#m', $link, $date);

            // преобразовали формат для преобразования в timestamp
            $date = strptime($date[1], '%Y%m%d');
            // преобразовали в timestamp
            $timestamp = mktime(0, 0, 0, $date['tm_mon']+1, $date['tm_mday'], $date['tm_year']+1900);
            // проверили правильность преобразования
            $date = date('d.m.Y', $timestamp);
            #endregion

     * */

}