<?php
declare(strict_types=1);

namespace Classes\Encoder;

use Symfony\Component\Finder\Finder;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Class Encoder
 *
 * @package Classes\Encoder
 */
class Encoder
{
    private $finder;
    private $dir;
    private const LOWERCASE = 3;
    private const UPPERCASE = 1;

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


    public function encode(): void
    {
        foreach ($this->finder as $file) {
            $path = $this->dir . '/' . $file->getRelativePathname();

            $content = file_get_contents($path);
            $charset = $this->detectCurCharset($content);

            if ($charset !== 'UTF-8') {
                $content = mb_convert_encoding($content, 'UTF-8', $charset);
                file_put_contents($path, $content);
            }
        }
    }

    /**
     * @param $content
     *
     * @return int|string|null
     */
    private function detectCurCharset($content)
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
                    $charsets['KOI8-R'] += self::LOWERCASE;
                }

                if ($char > 222 && $char < 256) {
                    $charsets['KOI8-R'] += self::UPPERCASE;
                }

                //windows-1251
                if ($char > 223 && $char < 256) {
                    $charsets['windows-1251'] += self::LOWERCASE;
                }

                if ($char > 191 && $char < 224) {
                    $charsets['windows-1251'] += self::UPPERCASE;
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
