<?php
/**
 * by Novikov.ua 2016
 */

namespace nofikoff\parsermachine;

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

    public function html_pars()
    {
        // грязная очистка
        $this->content = preg_replace('/(<script[^>]*>.*?<\/script>)/si', '', $this->content);


        $result['error_descr'] = '';
        $result['max_paragraf'] = 0;
        $result['mdl_paragraf'] = 0;
        //
        if (!trim($this->content)) {
            $result['status'] = 'parsered_notext';
            $result['error'] = 1;
            $result['error_descr'] = 'zerro size page AI';
            $result['content'] = '';

        } elseif (preg_match('/invalid request/i', $this->content)) {
            $result['status'] = 'parsered_notext';
            $result['error'] = 1;
            $result['error_descr'] = 'Message: invalid request in AI';
            $result['content'] = '';

        } else if (preg_match('/t have that page archived/i', $this->content)) {
            $result['status'] = 'parsered_notext';
            $result['error'] = 1;
            $result['error_descr'] = 'AI NO this page archived';
            $result['content'] = '';

        } else if (preg_match('/The machine that serves this file is down/i', $this->content)) {
            $result['status'] = 'temporary_error';
            $result['error'] = 1;
            $result['error_descr'] = 'Message AI: that serves this file is down Temporary ERROR';
            $result['content'] = '';
        }


        if (isset($result['error'])) return $result;

        // продолжим

        $ae = new PhpBoilerPipe\ArticleExtractor();
        $out = $ae->getContent($this->content);
        preg_match_all("/(.*)\\n/", $out, $d);

        $lengths = array_map('strlen', $d[0]);
        $lengths = array_unique($lengths);

        // Безье меньше 5 апаметров не воспринимает
        if (sizeof($lengths) < 5) $lengths = array_merge($lengths, $lengths);
        if (sizeof($lengths) < 5) $lengths = array_merge($lengths, $lengths);
        if (sizeof($lengths) < 5) $lengths = array_merge($lengths, $lengths);
        if (sizeof($lengths) < 5) $lengths = array_merge($lengths, $lengths);
        if (sizeof($lengths) < 5) $lengths = array_merge($lengths, $lengths);

        //Общий абстрактный класс SmoothCurve
        include_once('../vendor/novikov/bizie/SmoothCurve.class.php');
        include_once('../vendor/novikov/bizie/BezierCurve.class.php');

        $curve = new \BezierCurve();
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

        if (strlen($out) < 1500) {
            $result['status'] = 'parsered_notext';
            $result['error'] = 1;
            $result['content'] = '';
            $result['max_paragraf'] = max($lengths);
            $result['mdl_paragraf'] = round($bezie_middle);

            // если средний по бие абзац меньше 200 знаков это мусорная страница
        } else if (round($bezie_middle) < 200) {
            $result['status'] = 'parsered_notext';
            $result['error'] = 1;
            $result['content'] = '';
            $result['max_paragraf'] = max($lengths);
            $result['mdl_paragraf'] = round($bezie_middle);

            // это хорошая статья
        } else {
            $result['status'] = 'parsered_success';
            $result['error'] = 0;
            $result['content'] = $out;
            $result['max_paragraf'] = max($lengths);
            $result['mdl_paragraf'] = round($bezie_middle);
        }

        return $result;
    }

}
