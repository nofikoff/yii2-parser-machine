<?php
/**
 * by Novikov.ua 2016
 */


/**
 * этот алгоритм взять тут http://www.c-laboratory.ru/seo/unik/
 *
 *
 * то есть у нас должны оставаться только те котоые оригинальные или те которые с удаленного сайта но еще в
 * кэше и нигде больше нет....если например статья в кэше и еще где то. то ее нет смысла хранить, а если она
 * только в кэше а нигде больше нет то ее просто в отстойник и ждать пока выйдет из кэша и как выйдет,
 * мгновенно использовать ее(но вообще то ее для себя уже можно использовать, а как кэш пропадет то она
 * станет сразу оригинальной)
 *
 *
 * да, это основа всей системы
 * добавлю, что при удалении НЕ укниального текста из нашей БД
 * можем оставлять адрес истчника себе на память, чтобы снова не проскнаировать его по ошибке
 *
 *
 * [23:06:25] Maxim  Rudenko: но принцып у нас такой, получили текст, проверили его по кключевым фразам статей конкурента, если нашли, то сразу в оригинальные тексты, если не нашли то проверяем на оригинальность, те что не оригинальны в одно место, те сто оригинальны в другое и их там на кэш проверяем и сортируем оригинальные с кэшем  и оригинальные без кэша, как я выше написал  и все
 * [23:07:55] Ruslan Novikov: это типа для экономии ресурсов и времени на проверку уникальности
 * ок- попробуем
 *
 *
 * Храним в БД
 * Есть замеры. Производительность начинает заметно сильнее просидать после того как фаил переваливает около 5 мб.
 * Замеры были на оракле и mssql про mysql мне кажется тоже можно нарыть. Исследование проводилось каким-то институтом американским. Резюме было такое, что хранить до 5 мб файлы можно со свистом в БД, после 5 лучше в файловой системе, именно по этому в оракле и mssql есть на этот счет специальный функционал (filestreaming и т.д.) это когда база сама следит за целостностью файлов, которые хранятся в файловой системе.
 *
 *
 * Указываем сайт из архива и есть три опции: 1 слить просто все что есть один к одному HTML,  то есть точно так как у них с url  2 слить только тексты с заглавными h1 h2 h3, метатегами с разбивкой по url как у них есть 3 тоже самое как во втором, но только в xml образец который я дам.  Нигде для этого проверять ни на кэш ни на оригинальной не надо.! Вот такое думаю вообще не сложно сделать
 *
 */


namespace nofikoff\parsermachine;

use app\controllers\SystemMessagesLogController;
use nofikoff\yashagosha\GoogleMachine;


//use Yii;

class UniqueTestTxt
{

    // минимальная длина предложения что выираем из статьи для анализа
    public $min_length_str = 50; // в оригианле = 50
    public $number_need_str = 5; // в оригинале = 10
    //полноценная статья с предложеиями на входе
    public $article_str = '';
    public $debug = false;


    public $google;

    function __construct()
    {
        $this->google = new GoogleMachine();
        $this->google->exactly = true;
        $this->google->debug = $this->debug;


    }

    public function result()
    {
        $index = 0; // количество найденных дублей предложений
        $total = 0; // количество учтенных дублей предложений

        // получаем массив чищенный прдложений от большего к меньшему
        $list_str = $this->get_array_n_max_str_from_article($this->article_str);

        $result['error'] = false;
        $result['desc'] = '';


        foreach ($list_str as $str) {
            $str = $this->get_str_with_n_max_words($str);
            $this->google->use_antigate = 1;
            $this->google->use_my_external_php_proxy = 1;
            $out_page = $this->google->get_page($str);
            sleep(1);


//            $out_page['result'] = preg_replace('/(<script[^>]*>.*?<\/script>)/siu', '', $out_page['result']);
//            $out_page['result'] = str_replace('<script', '<', $out_page['result']);
//            $out_page['result'] = str_replace('<\script', '<\\', $out_page['result']);
//            print_r($out_page);
//            exit;


            if ($out_page['error']) {
                $result['error'] = true;
                $result['desc'] = $out_page['description'];
                return $result;
            }


            $num_result = $this->google->parse_number_finded_results($out_page['result']);
            if ($num_result['error']) {
                $result['error'] = true;
                $result['desc'] = $num_result['description'];
                return $result;
            }


            $result['ResultPhrases'][] = $str;
            $result['ResultFound'][] = $num_result['result'];
            if ($num_result['result'] > 0) {
                $index = $index + $num_result['result'];
                $total++;
            }
        }

        if ($total > 0) {
            $result['level_uniq'] = round(100 * (1 / ($index / $total)));
            $result['desc'] = 'Уникальность по Гуглу';
        } else {
            //$result['level_uniq'] = "-1";
            $result['level_uniq'] = 101;
            $result['desc'] = "Абсолютно уникальный текст, не встречается в Гугле";
        }

        return $result;
    }



    // подготавливает строку запроса, убирает знаки препинания,
    // лишние пробелы, обрезает до максимально допустимйо длины
    public function get_str_with_n_max_words($strReq)
    {
        // обрезаем до 30 слов в запросе
        $a = explode(' ', $strReq);
        $a = array_slice($a, 0, $this->google->query_max_length);
        $strReq = implode(' ', $a);
        return $strReq;
    }

    // подготавливает строку запроса, убирает знаки препинания,
    // лишние пробелы, обрезает до максимально допустимйо длины
    public function get_array_n_max_str_from_article($strReq)
    {
        // чистим статью от спецзнаков и пр
        $strReq = preg_replace('/[^а-яa-z0-9\.]+/ui', ' ', $strReq);
        $strReq = preg_replace('/[\s]+/', ' ', $strReq);
        // разбиваем напредложения
        $a = explode('.', $strReq);
        // тримаем полученный массив предложений
        $a = array_map("trim", $a);
        // сортируем от больших к меньгим
        usort($a, function ($a, $b) {
            return mb_strlen($b) - mb_strlen($a);
        });

        $total_str = 0;
        foreach ($a as $str) {
            if (mb_strlen($str) < $this->min_length_str) break;
            $total_str++;
            if ($total_str > ($this->number_need_str - 1)) break;
        }
        // возвращаем обрещанный до нужного числа предложений массив
        return array_slice($a, 0, $total_str);
    }


}
