<?php

function detectCurCharset($content)
{
    define('LOWERCASE', 3);
    define('UPPERCASE', 1);

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
            if (($char > 191 && $char < 223)) {
                $charsets['KOI8-R'] += LOWERCASE;
            }

            if (($char > 222 && $char < 256)) {
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

$path = 'files/test_directory/20030720113825/_conf/default.asp?action=show_mess&message_id=1&branch_id=1&file=trade1.xml';
$content = file_get_contents($path);
$charset = detectCurCharset($content);
echo $charset . "\n";

if ($charset !== 'UTF-8') {
    $content = mb_convert_encoding($content, 'UTF-8', $charset);
    file_put_contents($path, $content);
}

