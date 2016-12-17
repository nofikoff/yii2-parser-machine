<?php
/**
 * by Novikov.ua 2016
 * Набор функций для разбора HTMД документов
 */

namespace nofikoff\parsermachine;

use app\controllers\WaybackMachineController;
use DotPack\PhpBoilerPipe;

//use Yii;

class HtmlParser
{

    public $content = '';


    // тут основные алгоритмя как это делать - например искать дивы с макимпльгым количестом
    // http://stackoverflow.com/questions/4672060/web-scraping-how-to-identify-main-content-on-a-webpage
    // http://stackoverflow.com/questions/7021260/how-can-i-extract-only-the-main-textual-content-from-an-html-page


    //тут научный подход к этому вопросу
    //http://web.archive.org/web/20080620121103/http://ai-depot.com/articles/the-easy-way-to-extract-useful-text-from-arbitrary-html/

    // есть вроде с демкой сервис https://www.diffbot.com/benefits/


    //!!! кстати можно старым добрым СИПЛОДОМОМО echo file_get_html('http://www.google.com/')->plaintext;
    function __construct($content)
    {

        if ($this->detect_encoding($content) == 'windows-1251') {
            $this->content = mb_convert_encoding($content, 'utf-8', 'windows-1251');
        } else {
            $this->content = $content;
        }


    }


    public function html_pars($more_jently)
    {


        $result['description_parser'] = '';
        $result['status'] = '';
        $result['max_paragraf'] = 0;
        $result['mdl_paragraf'] = 0;


        $result['lang'] = '';
        $result['title'] = '';
        $result['keywords_meta'] = '';
        $result['description_parser'] = '';
        $result['h1'] = '';


        //
        if (!trim($this->content)) {
            $result['status'] = 'parsered_notext';
            $result['error'] = 1;
            $result['description_parser'] = 'zerro size page AI';
            $result['result'] = '';
            return $result;

        } elseif (preg_match('/invalid request/i', $this->content)) {
            $result['status'] = 'parsered_notext';
            $result['error'] = 1;
            $result['description_parser'] = 'Message: invalid request in AI';
            $result['result'] = '';
            return $result;

        } else if (preg_match('/t have that page archived/i', $this->content)) {
            $result['status'] = 'parsered_notext';
            $result['error'] = 1;
            $result['description_parser'] = 'AI NO this page archived';
            $result['result'] = '';
            return $result;

        } else if (preg_match('/The machine that serves this file is down/i', $this->content)) {
            $result['status'] = 'temporary_error';
            $result['error'] = 1;
            $result['description_parser'] = 'Message AI: that serves this file is down Temporary ERROR';
            $result['result'] = '';
            return $result;

        }


        // основное мсясо
        // основное мсясо
        // основное мсясо
        //занес в нутрь след функции см ниже
        //$ae = new PhpBoilerPipe\ArticleExtractor();
        //$out = $ae->getContent($this->content);
        // ЭТО ПИЗДЕЦ
        // костыль что парсит и сксчивает картинки АРХИВА ИНТЕРНЕТ (не путать 008 что скачиват недостающие кратинки ГУГЛ КАРТИНКИ)
        // костыль что парсит и сксчивает картинки АРХИВА ИНТЕРНЕТ (не путать 008 что скачиват недостающие кратинки ГУГЛ КАРТИНКИ)
        // костыль что парсит и сксчивает картинки АРХИВА ИНТЕРНЕТ (не путать 008 что скачиват недостающие кратинки ГУГЛ КАРТИНКИ)
        // костыль что парсит и сксчивает картинки АРХИВА ИНТЕРНЕТ (не путать 008 что скачиват недостающие кратинки ГУГЛ КАРТИНКИ)
        $out = WaybackMachineController::parseTxtArticleAndImagesAI($this->content, md5($this->content), $more_jently);


//        $out = str_replace(" \r\n", "\r\n", $out);
//        $out = str_replace("\r\n\r\n\r\n", "\r\n\r\n",$out);
//        $out = str_replace("\r\r\r", "\r\r",$out);
//        $out = str_replace("\n\n\n", "\n\n",$out);
        //
        if (!trim($out)) {
            $result['status'] = 'parsered_notext';
            $result['error'] = 1;
            $result['description_parser'] = 'zerro size page AI';
            $result['result'] = '';
            return $result;
        }

        $result['result'] = $out;

        if (isset($result['error'])) {
            echo "Выход по косвенным признакам это ошибка";
            return $result;
        }

        // продолжим

        if (!preg_match_all("/(.*)\\n/", $out, $d)) {
            $result['status'] = 'parsered_notext';
            $result['error'] = 1;
            $result['description_parser'] = 'Не вижу абазацы';
            return $result; //!
        }


        $lengths = array_map('strlen', $d[0]);
        $lengths = array_unique($lengths);

        // Безье меньше 5 апаметров не воспринимает
        if (sizeof($lengths) < 5) $lengths = array_merge($lengths, $lengths);
        if (sizeof($lengths) < 5) $lengths = array_merge($lengths, $lengths);
        if (sizeof($lengths) < 5) $lengths = array_merge($lengths, $lengths);
        if (sizeof($lengths) < 5) $lengths = array_merge($lengths, $lengths);
        if (sizeof($lengths) < 5) $lengths = array_merge($lengths, $lengths);


        // случайно заметил что индексы бывают спропусками и не полнвеы тут
        $lengths = array_values($lengths);
        // не более 170
        $lengths = array_slice($lengths, 0, 169);


        //Общий абстрактный класс SmoothCurve
//        include_once('../vendor/nofikoff/bizie/SmoothCurve.class.php');
//        include_once('../vendor/nofikoff/bizie/BezierCurve.class.php');

        $curve = new BezierCurve();
        //Передаем ему координаты, шаг просчета = 1
        $curve->setCoords($lengths, 1);
        $curveCoords = $curve->process();
        // случайно заметил что индексы бывают спропусками и не полнвеы тут
        // делаем реиндекс
        $curveCoords = array_values($curveCoords);
        $bezie_middle = $curveCoords[round(sizeof($curveCoords) / 2)];
        //
        //        echo "Длина: " . strlen($out) . "\n";
        //        echo "Максимальный абзац длина: " . max($lengths) . "\n";
        //        echo "Средний абзац длина: " . round($bezie_middle) . "\n";
        //        echo $out . "\n";
        //TODO: ВЫнести в парамсы
        if ($more_jently)
            $min_length = 400;
        else
            $min_length = 1500;
        //


        if (mb_strlen($out) < $min_length) {
            $result['status'] = 'parsered_notext';
            $result['error'] = 1;
            $result['description_parser'] = 'Меньше 1500';
            $result['max_paragraf'] = max($lengths);
            $result['mdl_paragraf'] = round($bezie_middle);

            // если средний по бие абзац меньше 200 знаков это мусорная страница
        } else if (round($bezie_middle) < 200 AND !$more_jently) {
            $result['status'] = 'parsered_notext';
            $result['description_error'] = 'Это жидкий текст средний абзац меньше 200';
            $result['error'] = 1;
            $result['max_paragraf'] = max($lengths);
            $result['mdl_paragraf'] = round($bezie_middle);

            // это хорошая статья
        } else {
            $result['lang'] = $this->detectTextLanguage($out);


            // языки офдтруем только русский и ангилсуйи
            if ($result['lang'] == 'not detected') {
                $result['description_error'] = 'Язык НЕ определен';
                $result['status'] = 'parsered_notext';
                $result['error'] = 1;

            } else {
                $result['status'] = 'parsered_success';
                $result['error'] = 0;
            }


            $result['result'] = $out;
            $result['max_paragraf'] = max($lengths);
            $result['mdl_paragraf'] = round($bezie_middle);


            $result['title'] = $this->get_title();
            $result['keywords_meta'] = json_encode($this->get_keywords());
            $result['description_parser'] = $this->get_description();
            $result['h1'] = $this->get_h1();
        }


        return $result;
    }


    public function get_title()
    {
        $out = '';
        if (preg_match('~<title>([^<]+)</title>~uis', $this->content, $d)) {
            $out = $d[1];
        }
        return $out;
    }

    public function get_keywords()
    {
        $out = '';
        //<meta name="Keywords" content="окна ПВХ, металлопластиковые окна, деревянные окна, алюминиевые окна, металлопластиковые двери, пластиковые окна, монтаж окон, изготовление окон, доставка окон, производство окон"/>
        //<meta id="MetaKeywords" name="KEYWORDS" content="Let’s Go, sustainable transport, sustainable travel, bike, ride, cycle, walk, bus, biking, cycling, riding, walking, bussing, maps, pathways, walkways, BDO Cyclist Skills, Get Going, cycle lanes, street project, bus timetable, travel plan,DotNetNuke,DNN" />
        if (preg_match('~<meta[^>]+name\s*=\s*[\'"]{1}KEYWORDS[\'"]{1}[^>]+CONTENT=([^>]+)~uis', $this->content, $d)) {
            $out = $d[1];
        }

        $a = explode(',', trim($out, '\'" /'));
        $a = array_map(
            function ($a) {
                return trim($a, ", ");
            },
            $a
        );

        return $a;
    }

    public function get_description()
    {
        $out = '';
        if (preg_match('~"description"\s+content="([^"]+)"~uis', $this->content, $d)) {
            $out = $d[1];
        }
        return $out;

    }

    public function get_h1()
    {
        $out = '';
        if (preg_match('~<h1[^>]*>([^<]+)</h1>~uis', $this->content, $d)) {
            $out = $d[1];
        }
        return $out;

    }

    static function detectTextLanguage($text, $default = 'not detected')
    {
        $supported_languages = array(
            'en',
//            'de', в этом проекте немецкий НЕ нужен!!!!
            'ru',
        );
        // German word list
        // from http://wortschatz.uni-leipzig.de/Papers/top100de.txt
        $wordList['de'] = array('der', 'die', 'und', 'in', 'den', 'von',
            'zu', 'das', 'mit', 'sich', 'des', 'auf', 'für', 'ist', 'im',
            'dem', 'nicht', 'ein', 'Die', 'eine');

        // English word list
        // from http://en.wikipedia.org/wiki/Most_common_words_in_English
        $wordList['en'] = array('the', 'be', 'to', 'of', ' and ', 'a', 'in',
            'that', 'have', 'I', 'it', 'for', 'not', 'on', 'with', 'he',
            'as', 'you', 'do', 'at');

        // ru.wiktionary.org/wiki/Приложение:Список_частотности_по_НКРЯ
        $wordList['ru'] = array(
            'и',
            'в',
            'не',
            'на',
            'я',
            'быть',
            'он',
            'с',
            'что',
            'а',
            'по',
            'это',
            'она',
            'этот',
            'к',
            'но',
            'они',
            'мы',
            'как',
            'из');

        // clean out the input string - note we don't have any non - ASCII
        // characters in the word lists... change this if it is not the
        // case in your language wordlists!
        $text = preg_replace("/[^a-zа-я]/ui", ' ', $text);
        // count the occurrences of the most frequent words
        foreach ($supported_languages as $language) {
            $counter[$language] = 0;
        }
        for ($i = 0; $i < 20; $i++) {
            foreach ($supported_languages as $language) {
                $counter[$language] = $counter[$language] +
                    // I believe this is way faster than fancy RegEx solutions
                    substr_count($text, ' ' . $wordList[$language][$i] . ' ');;
            }
        }

        print_r($counter);
        echo mb_strlen($text);

        // костыль
        // мы определяем тут английский
        // минимум 1500 знаков текст
        //на глаз додно бы ть минимум 30-40 англ фрагментов а не 5 англ фрагментов как в сиспанкских текстах - они ломают нам всю картину
        if ($counter['en'] > round(mb_strlen($text) / 40)) return 'en';
        if ($counter['ru'] > 10) return 'ru';
        return 'not detected';


        // get max counter value
        // from http://stackoverflow.com/a/1461363
        $max = max($counter);
        $maxs = array_keys($counter, $max);
        // if there are two winners - fall back to default!
        if (count($maxs) == 1) {
            $winner = $maxs[0];
            $second = 0;
            // get runner-up (second place)
            foreach ($supported_languages as $language) {
                if ($language <> $winner) {
                    if ($counter[$language] > $second) {
                        $second = $counter[$language];
                    }
                }
            }
            // apply arbitrary threshold of 10%
            if (($second / $max) < 0.1) {
                return $winner;
            }
        }


        return $default;
    }

    static function detect_encoding($string)
    {
        static $list = array('utf-8', 'windows-1251');

        foreach ($list as $item) {
            $sample = mb_convert_encoding($string, $item, $item);
            if (md5($sample) == md5($string))
                return $item;
        }
        return null;
    }


    static function remove_html_images($html)
    {
        return preg_replace("~<img\s[^>]*?src\s*=\s*['\"]([^'\"]*?)['\"][^>]*?>~uis", '', $html);
    }

    static function only_latina_remove_no_utf8_chars_spaces($Str)
    {
        $StrArr = str_split($Str);
        $NewStr = '';
        foreach ($StrArr as $Char) {
            $CharNo = ord($Char);
            if ($CharNo == 163) {
                $NewStr .= $Char;
                continue;
            } // keep £
            if ($CharNo > 31 && $CharNo < 127) {
                $NewStr .= $Char;
            }
        }
        return $NewStr;
    }

    static function sting_contents_no_latina($Str)
    {
        $StrArr = str_split($Str);
        $NewStr = '';
        foreach ($StrArr as $Char) {
            $CharNo = ord($Char);
            if ($CharNo < 32 OR $CharNo > 126) {
                return true;
            }
        }
        return false;
    }

}

