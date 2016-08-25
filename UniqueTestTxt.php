<?php
/**
 * by Novikov.ua 2016
 */


namespace nofikoff\parsermachine;

use DotPack\PhpBoilerPipe;
//use Yii;

class UniqueTestTxt
{


public    function go_foundpages( $strOnePhrase) {
        // функция ищет на гугле фразу и возвращает кол-во найденных страниц
        // http://www.google.ru/search?q=
        $strReq = trim($strOnePhrase);
        $strReq = '"'.$strReq.'"';
        $strReq = urlencode($strReq);

        $strGoAnswer = file_get_contents('http://www.google.ru/search?filter=0&q=' . $strReq);

        if( !strlen($strGoAnswer)) {
            print("<br><br>гугль забанил IP! ждем 5 минут, пока разбанит");
            exit;
        }

        //print( RemScripts($strGoAnswer));
        $strGoAnswer = preg_replace ("'<head>.*?</head>'si", "", $strGoAnswer); // целиком удаляем хедер
        //print( $strGoAnswer);

        //Результаты <b>1</b> - <b>1</b> из <b>1</b> для <b>
        //Результаты <b>1</b> - <b>10</b> из примерно <b>66</b> для

        if( preg_match( "|Результаты <b>\d+</b> - <b>\d+</b> из .{0,9}<b>(\d+)</b> для <b>|si", $strGoAnswer, $arrTmp)) {
            $intAnswer = 0 + $arrTmp[1];
        } else {
            $intAnswer = 0;
        }
        //print( $strOnePhrase ." - " . $intAnswer . "<br>");
        return $intAnswer;
    }

}
