<?php
declare(strict_types=1);


////////////////////////////////////////////////////////
//  Функция поиска пустых страниц с одним заголовком  //
////////////////////////////////////////////////////////


function checkContent(string &$content): string
{
    #proand #fixme
    return $content;
    # если мы не админке - проверяем $_REQUEST
    if (!defined('ADMIN_SECTION')) {
        $request = Request::createFromGlobals();
    }
    # безусловно пропускаем
    $skipCheck = (defined('SKIP_CONTENT_CHECK') && SKIP_CONTENT_CHECK === true)
        # или мы в админке
        ?: defined('ADMIN_SECTION')
            # или это XHR
            ?: $request->isXmlHttpRequest()
                # или это POST
                ?: $request->isMethod('POST');

    # проверяем если не админка и не POST или XHR-запрос
    $check = !$skipCheck;

    # если не авторизация через ЕСИА
    $isESIA = isset($_GET['authesia']) || isset($_GET['movetoesia']);

    # если не спец. версия
    $isSpecial = 'Y' === $_GET['special_version'];

    # если не печать
    $isPrint = 'Y' === $_GET['print_version'];

    if (isset($_GET['checkContent'])) {
        $check = true;
        ([
            '$check' => $check,
            '$isESIA' => $isESIA,
            '$isSpecial' => $isSpecial,
            'isPrint' => $isPrint,
        ]);
    }

    if ($check && !$isESIA && !$isSpecial && !$isPrint) {
        $htmlRaw = self::autoCloseHtmlTags($content);

        $dom = new \DOMDocument();
        $dom->loadHTML($htmlRaw);

        $denyTags = [
            'script',
            'noscript',
            'link',
            'header',
            'picture',
            'head',
            'svg',
            'img',
            'footer',
            'meta',
            'aside',
            'nav',
            'style',
            'a'
//                'div',
//                'section'
        ];

        # удаляем запрещенные теги
        $dom = self::removeTags($dom, $denyTags);

        # удаляем комментарии
        $xpath = new \DOMXPath($dom);
        foreach ($xpath->query('//comment()') as $comment) {
            $comment->parentNode->removeChild($comment);
        }

        # получаем оставшееся содержимое тега main
        $main = [];
        /**
         * @var $item \DOMElement
         */
        foreach ($dom->getElementsByTagName('main') as $item) {
            $main = $item;
        }

        # получаем оставшееся содержимое тега h1
        $h1 = [];
        /**
         * @var $item \DOMElement
         */
        foreach ($dom->getElementsByTagName('h1') as $item) {
            $h1 = $item;
        }

        $h1Content = trim(strip_tags((string)$h1->nodeValue));
        $allContent = trim(strip_tags((string)$dom->saveHTML()));

        # удаляем h1 из main
        $main->nodeValue = str_replace([
                $h1->nodeValue,
                ' ',
                PHP_EOL,
            ]
            , '',
            $main->nodeValue
        );

        $mainContent = (string)$main->nodeValue;

        # если нет контента
        $emptyContent = '' === $mainContent ?: $allContent === $h1Content;

        if ($emptyContent) {
            $log = Log::getInstance()->getLog('badContent');
            $log->error('EMPTY_PAGE', ['request' => $_REQUEST, 'server' => $_SERVER]);
            \CHTTP::SetStatus('404 Not Found');

            $dom = new \DOMDocument();
            # повторно загружаем контет страницы
            $dom->loadHTML($htmlRaw);

            # полчаем тег main
            foreach ($dom->getElementsByTagName('main') as $item) {
                $main = $item;
            }

            # создаем заглушку
            $htmlTemplate = '<div><div class="message">' .
                '<div class="message__block">' .
                '<h2 class="message__title">На этой странице ничего нет</h2>' .
                '<div class="message__text"><ul>' .
                '<li>— Перейти на <a href="/">главную</a></li> 
                    <li>— Пожаловаться</li> ' .
                '<li>— Оставить обращение</li></ul></div></div> ' .
                '<div class="patch patch--non-events">' .
                '<img src="/local/templates/adaptive/images/message.png">' .
                '</div>
                    </div></div>';

            $newDiv = self::createNode($dom, $htmlTemplate);

            $denyTags = ['section', 'div'];

            $dom = self::removeTags($dom, $denyTags, 'main');

            if (method_exists($main, 'setAttribute')) {
                $main->setAttribute('class', 'container');
            }

            if (method_exists($main, 'appendChild')) {
                $main->appendChild($newDiv);
            }


            # сохраняем измененый контент страницы
            $html = $dom->saveHTML();
            $content = $html;
        }
    }
    return $content;
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

function autoCloseHtmlTags(string $html, string $charset = 'UTF-8'): string
{
    $dom = new \DomDocument();
    $dom->loadHTML('<?xml encoding="' . $charset . '">' . $html);
    $html = $dom->saveHTML($dom->documentElement);
    return $html;
}


$code = [];
$answer = [];
$fieldType = '.';
//  ВМЕСТО КОНКАТЕНАЦИИ ИСПОЛЬЗОВАТЬ PRINTF
# name поля формы
$fields[$code]['name'] = sprintf('form_%s_%s',
    (string)$answer[0]['FIELD_TYPE'],
    $fieldType
);