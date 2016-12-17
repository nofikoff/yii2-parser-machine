<?php
/**
 * Created by PhpStorm.
 * User: Arnoldus
 * Date: 17.12.2016
 * Time: 1:35
 * собрал по кускам в сети
 *
 */

mb_internal_encoding("UTF-8");

class DetectUnicodeRanges
{
    public $list_lang = [];
    //rnage from http://www.google.com/jsapi
    //rnage from http://www.google.com/jsapi
    //rnage from http://www.google.com/jsapi
    public $range = [
        'Basic Latin' => ['0x0020', '0x007F'],
        'Latin-1 Supplement' => ['0x00A0', '0x00FF'],
        'Latin Extended-A' => ['0x0100', '0x017F'],
        'Latin Extended-B' => ['0x0180', '0x024F'],
        'IPA Extensions' => ['0x0250', '0x02AF'],
        'Spacing Modifier Letters' => ['0x02B0', '0x02FF'],
        'Combining Diacritical Marks' => ['0x0300', '0x036F'],
        'Greek and Coptic' => ['0x0370', '0x03FF'],
        'Cyrillic' => ['0x0400', '0x04FF'],
        'Cyrillic Supplementary' => ['0x0500', '0x052F'],
        'Armenian' => ['0x0530', '0x058F'],
        'Hebrew' => ['0x0590', '0x05FF'],
        'Arabic' => ['0x0600', '0x06FF'],
        'Syriac' => ['0x0700', '0x074F'],
        'Thaana' => ['0x0780', '0x07BF'],
        'Devanagari' => ['0x0900', '0x097F'],
        'Bengali' => ['0x0980', '0x09FF'],
        'Gurmukhi' => ['0x0A00', '0x0A7F'],
        'Gujarati' => ['0x0A80', '0x0AFF'],
        'Oriya' => ['0x0B00', '0x0B7F'],
        'Tamil' => ['0x0B80', '0x0BFF'],
        'Telugu' => ['0x0C00', '0x0C7F'],
        'Kannada' => ['0x0C80', '0x0CFF'],
        'Malayalam' => ['0x0D00', '0x0D7F'],
        'Sinhala' => ['0x0D80', '0x0DFF'],
        'Thai' => ['0x0E00', '0x0E7F'],
        'Lao' => ['0x0E80', '0x0EFF'],
        'Tibetan' => ['0x0F00', '0x0FFF'],
        'Myanmar' => ['0x1000', '0x109F'],
        'Georgian' => ['0x10A0', '0x10FF'],
        'Hangul Jamo' => ['0x1100', '0x11FF'],
        'Ethiopic' => ['0x1200', '0x137F'],
        'Cherokee' => ['0x13A0', '0x13FF'],
        'Unified Canadian Aboriginal Syllabics' => ['0x1400', '0x167F'],
        'Ogham' => ['0x1680', '0x169F'],
        'Runic' => ['0x16A0', '0x16FF'],
        'Tagalog' => ['0x1700', '0x171F'],
        'Hanunoo' => ['0x1720', '0x173F'],
        'Buhid' => ['0x1740', '0x175F'],
        'Tagbanwa' => ['0x1760', '0x177F'],
        'Khmer' => ['0x1780', '0x17FF'],
        'Mongolian' => ['0x1800', '0x18AF'],
        'Limbu' => ['0x1900', '0x194F'],
        'Tai Le' => ['0x1950', '0x197F'],
        'Khmer Symbols' => ['0x19E0', '0x19FF'],
        'Phonetic Extensions' => ['0x1D00', '0x1D7F'],
        'Latin Extended Additional' => ['0x1E00', '0x1EFF'],
        'Greek Extended' => ['0x1F00', '0x1FFF'],
        'General Punctuation' => ['0x2000', '0x206F'],
        'Superscripts and Subscripts' => ['0x2070', '0x209F'],
        'Currency Symbols' => ['0x20A0', '0x20CF'],
        'Combining Diacritical Marks for Symbols' => ['0x20D0', '0x20FF'],
        'Letterlike Symbols' => ['0x2100', '0x214F'],
        'Number Forms' => ['0x2150', '0x218F'],
        'Arrows' => ['0x2190', '0x21FF'],
        'Mathematical Operators' => ['0x2200', '0x22FF'],
        'Miscellaneous Technical' => ['0x2300', '0x23FF'],
        'Control Pictures' => ['0x2400', '0x243F'],
        'Optical Character Recognition' => ['0x2440', '0x245F'],
        'Enclosed Alphanumerics' => ['0x2460', '0x24FF'],
        'Box Drawing' => ['0x2500', '0x257F'],
        'Block Elements' => ['0x2580', '0x259F'],
        'Geometric Shapes' => ['0x25A0', '0x25FF'],
        'Miscellaneous Symbols' => ['0x2600', '0x26FF'],
        'Dingbats' => ['0x2700', '0x27BF'],
        'Miscellaneous Mathematical Symbols-A' => ['0x27C0', '0x27EF'],
        'Supplemental Arrows-A' => ['0x27F0', '0x27FF'],
        'Braille Patterns' => ['0x2800', '0x28FF'],
        'Supplemental Arrows-B' => ['0x2900', '0x297F'],
        'Miscellaneous Mathematical Symbols-B' => ['0x2980', '0x29FF'],
        'Supplemental Mathematical Operators' => ['0x2A00', '0x2AFF'],
        'Miscellaneous Symbols and Arrows' => ['0x2B00', '0x2BFF'],
        'CJK Radicals Supplement' => ['0x2E80', '0x2EFF'],
        'Kangxi Radicals' => ['0x2F00', '0x2FDF'],
        'Ideographic Description Characters' => ['0x2FF0', '0x2FFF'],
        'CJK Symbols and Punctuation' => ['0x3000', '0x303F'],
        'Hiragana' => ['0x3040', '0x309F'],
        'Katakana' => ['0x30A0', '0x30FF'],
        'Bopomofo' => ['0x3100', '0x312F'],
        'Hangul Compatibility Jamo' => ['0x3130', '0x318F'],
        'Kanbun' => ['0x3190', '0x319F'],
        'Bopomofo Extended' => ['0x31A0', '0x31BF'],
        'Katakana Phonetic Extensions' => ['0x31F0', '0x31FF'],
        'Enclosed CJK Letters and Months' => ['0x3200', '0x32FF'],
        'CJK Compatibility' => ['0x3300', '0x33FF'],
        'CJK Unified Ideographs Extension A' => ['0x3400', '0x4DBF'],
        'Yijing Hexagram Symbols' => ['0x4DC0', '0x4DFF'],
        'CJK Unified Ideographs' => ['0x4E00', '0x9FFF'],
        'Yi Syllables' => ['0xA000', '0xA48F'],
        'Yi Radicals' => ['0xA490', '0xA4CF'],
        'Hangul Syllables' => ['0xAC00', '0xD7AF'],
        'High Surrogates' => ['0xD800', '0xDB7F'],
        'High Private Use Surrogates' => ['0xDB80', '0xDBFF'],
        'Low Surrogates' => ['0xDC00', '0xDFFF'],
        'Private Use Area' => ['0xE000', '0xF8FF'],
        'CJK Compatibility Ideographs' => ['0xF900', '0xFAFF'],
        'Alphabetic Presentation Forms' => ['0xFB00', '0xFB4F'],
        'Arabic Presentation Forms-A' => ['0xFB50', '0xFDFF'],
        'Variation Selectors' => ['0xFE00', '0xFE0F'],
        'Combining Half Marks' => ['0xFE20', '0xFE2F'],
        'CJK Compatibility Forms' => ['0xFE30', '0xFE4F'],
        'Small Form Variants' => ['0xFE50', '0xFE6F'],
        'Arabic Presentation Forms-B' => ['0xFE70', '0xFEFF'],
        'Halfwidth and Fullwidth Forms' => ['0xFF00', '0xFFEF'],
        'Specials' => ['0xFFF0', '0xFFFF'],
        'Linear B Syllabary' => ['0x10000', '0x1007F'],
        'Linear B Ideograms' => ['0x10080', '0x100FF'],
        'Aegean Numbers' => ['0x10100', '0x1013F'],
        'Old Italic' => ['0x10300', '0x1032F'],
        'Gothic' => ['0x10330', '0x1034F'],
        'Ugaritic' => ['0x10380', '0x1039F'],
        'Deseret' => ['0x10400', '0x1044F'],
        'Shavian' => ['0x10450', '0x1047F'],
        'Osmanya' => ['0x10480', '0x104AF'],
        'Cypriot Syllabary' => ['0x10800', '0x1083F'],
        'Byzantine Musical Symbols' => ['0x1D000', '0x1D0FF'],
        'Musical Symbols' => ['0x1D100', '0x1D1FF'],
        'Tai Xuan Jing Symbols' => ['0x1D300', '0x1D35F'],
        'Mathematical Alphanumeric Symbols' => ['0x1D400', '0x1D7FF'],
        'CJK Unified Ideographs Extension B' => ['0x20000', '0x2A6DF'],
        'CJK Compatibility Ideographs Supplement' => ['0x2F800', '0x2FA1F'],
        'Tags' => ['0xE0000', '0xE007F'],

    ];

    public $range_basic_english = [
        'Basic Latin' => ['0x0020', '0x007F'],
//        'Latin-1 Supplement' => ['0x00A0', '0x00FF'],
//        'Latin Extended-A' => ['0x0100', '0x017F'],
//        'Latin Extended-B' => ['0x0180', '0x024F'],
//        'IPA Extensions' => ['0x0250', '0x02AF'],
        'Spacing Modifier Letters' => ['0x02B0', '0x02FF'],
        'Combining Diacritical Marks' => ['0x0300', '0x036F'],
//        'Greek and Coptic' => ['0x0370', '0x03FF'],
//        'Cyrillic' => ['0x0400', '0x04FF'],
//        'Cyrillic Supplementary' => ['0x0500', '0x052F'],
//        'Armenian' => ['0x0530', '0x058F'],
//        'Hebrew' => ['0x0590', '0x05FF'],
//        'Arabic' => ['0x0600', '0x06FF'],
//        'Syriac' => ['0x0700', '0x074F'],
//        'Thaana' => ['0x0780', '0x07BF'],
//        'Devanagari' => ['0x0900', '0x097F'],
//        'Bengali' => ['0x0980', '0x09FF'],
//        'Gurmukhi' => ['0x0A00', '0x0A7F'],
//        'Gujarati' => ['0x0A80', '0x0AFF'],
//        'Oriya' => ['0x0B00', '0x0B7F'],
//        'Tamil' => ['0x0B80', '0x0BFF'],
//        'Telugu' => ['0x0C00', '0x0C7F'],
//        'Kannada' => ['0x0C80', '0x0CFF'],
//        'Malayalam' => ['0x0D00', '0x0D7F'],
//        'Sinhala' => ['0x0D80', '0x0DFF'],
//        'Thai' => ['0x0E00', '0x0E7F'],
//        'Lao' => ['0x0E80', '0x0EFF'],
//        'Tibetan' => ['0x0F00', '0x0FFF'],
//        'Myanmar' => ['0x1000', '0x109F'],
//        'Georgian' => ['0x10A0', '0x10FF'],
//        'Hangul Jamo' => ['0x1100', '0x11FF'],
//        'Ethiopic' => ['0x1200', '0x137F'],
//        'Cherokee' => ['0x13A0', '0x13FF'],
//        'Unified Canadian Aboriginal Syllabics' => ['0x1400', '0x167F'],
//        'Ogham' => ['0x1680', '0x169F'],
//        'Runic' => ['0x16A0', '0x16FF'],
//        'Tagalog' => ['0x1700', '0x171F'],
//        'Hanunoo' => ['0x1720', '0x173F'],
//        'Buhid' => ['0x1740', '0x175F'],
//        'Tagbanwa' => ['0x1760', '0x177F'],
//        'Khmer' => ['0x1780', '0x17FF'],
//        'Mongolian' => ['0x1800', '0x18AF'],
//        'Limbu' => ['0x1900', '0x194F'],
//        'Tai Le' => ['0x1950', '0x197F'],
//        'Khmer Symbols' => ['0x19E0', '0x19FF'],
        'Phonetic Extensions' => ['0x1D00', '0x1D7F'],
//        'Latin Extended Additional' => ['0x1E00', '0x1EFF'],
//        'Greek Extended' => ['0x1F00', '0x1FFF'],
        'General Punctuation' => ['0x2000', '0x206F'],
        'Superscripts and Subscripts' => ['0x2070', '0x209F'],
        'Currency Symbols' => ['0x20A0', '0x20CF'],
        'Combining Diacritical Marks for Symbols' => ['0x20D0', '0x20FF'],
        'Letterlike Symbols' => ['0x2100', '0x214F'],
        'Number Forms' => ['0x2150', '0x218F'],
        'Arrows' => ['0x2190', '0x21FF'],
        'Mathematical Operators' => ['0x2200', '0x22FF'],
        'Miscellaneous Technical' => ['0x2300', '0x23FF'],
        'Control Pictures' => ['0x2400', '0x243F'],
        'Optical Character Recognition' => ['0x2440', '0x245F'],
        'Enclosed Alphanumerics' => ['0x2460', '0x24FF'],
        'Box Drawing' => ['0x2500', '0x257F'],
        'Block Elements' => ['0x2580', '0x259F'],
        'Geometric Shapes' => ['0x25A0', '0x25FF'],
        'Miscellaneous Symbols' => ['0x2600', '0x26FF'],
        'Dingbats' => ['0x2700', '0x27BF'],
        'Miscellaneous Mathematical Symbols-A' => ['0x27C0', '0x27EF'],
        'Supplemental Arrows-A' => ['0x27F0', '0x27FF'],
//        'Braille Patterns' => ['0x2800', '0x28FF'],
        'Supplemental Arrows-B' => ['0x2900', '0x297F'],
        'Miscellaneous Mathematical Symbols-B' => ['0x2980', '0x29FF'],
        'Supplemental Mathematical Operators' => ['0x2A00', '0x2AFF'],
//        'Miscellaneous Symbols and Arrows' => ['0x2B00', '0x2BFF'],
//        'CJK Radicals Supplement' => ['0x2E80', '0x2EFF'],
//        'Kangxi Radicals' => ['0x2F00', '0x2FDF'],
//        'Ideographic Description Characters' => ['0x2FF0', '0x2FFF'],
//        'CJK Symbols and Punctuation' => ['0x3000', '0x303F'],
//        'Hiragana' => ['0x3040', '0x309F'],
//        'Katakana' => ['0x30A0', '0x30FF'],
//        'Bopomofo' => ['0x3100', '0x312F'],
//        'Hangul Compatibility Jamo' => ['0x3130', '0x318F'],
//        'Kanbun' => ['0x3190', '0x319F'],
//        'Bopomofo Extended' => ['0x31A0', '0x31BF'],
//        'Katakana Phonetic Extensions' => ['0x31F0', '0x31FF'],
//        'Enclosed CJK Letters and Months' => ['0x3200', '0x32FF'],
//        'CJK Compatibility' => ['0x3300', '0x33FF'],
//        'CJK Unified Ideographs Extension A' => ['0x3400', '0x4DBF'],
//        'Yijing Hexagram Symbols' => ['0x4DC0', '0x4DFF'],
//        'CJK Unified Ideographs' => ['0x4E00', '0x9FFF'],
//        'Yi Syllables' => ['0xA000', '0xA48F'],
//        'Yi Radicals' => ['0xA490', '0xA4CF'],
//        'Hangul Syllables' => ['0xAC00', '0xD7AF'],
//        'High Surrogates' => ['0xD800', '0xDB7F'],
//        'High Private Use Surrogates' => ['0xDB80', '0xDBFF'],
//        'Low Surrogates' => ['0xDC00', '0xDFFF'],
//        'Private Use Area' => ['0xE000', '0xF8FF'],
//        'CJK Compatibility Ideographs' => ['0xF900', '0xFAFF'],
//        'Alphabetic Presentation Forms' => ['0xFB00', '0xFB4F'],
//        'Arabic Presentation Forms-A' => ['0xFB50', '0xFDFF'],
//        'Variation Selectors' => ['0xFE00', '0xFE0F'],
//        'Combining Half Marks' => ['0xFE20', '0xFE2F'],
//        'CJK Compatibility Forms' => ['0xFE30', '0xFE4F'],
//        'Small Form Variants' => ['0xFE50', '0xFE6F'],
//        'Arabic Presentation Forms-B' => ['0xFE70', '0xFEFF'],
//        'Halfwidth and Fullwidth Forms' => ['0xFF00', '0xFFEF'],
//        'Specials' => ['0xFFF0', '0xFFFF'],
//        'Linear B Syllabary' => ['0x10000', '0x1007F'],
//        'Linear B Ideograms' => ['0x10080', '0x100FF'],
//        'Aegean Numbers' => ['0x10100', '0x1013F'],
//        'Old Italic' => ['0x10300', '0x1032F'],
//        'Gothic' => ['0x10330', '0x1034F'],
//        'Ugaritic' => ['0x10380', '0x1039F'],
//        'Deseret' => ['0x10400', '0x1044F'],
//        'Shavian' => ['0x10450', '0x1047F'],
//        'Osmanya' => ['0x10480', '0x104AF'],
//        'Cypriot Syllabary' => ['0x10800', '0x1083F'],
//        'Byzantine Musical Symbols' => ['0x1D000', '0x1D0FF'],
//        'Musical Symbols' => ['0x1D100', '0x1D1FF'],
//        'Tai Xuan Jing Symbols' => ['0x1D300', '0x1D35F'],
        'Mathematical Alphanumeric Symbols' => ['0x1D400', '0x1D7FF'],
//        'CJK Unified Ideographs Extension B' => ['0x20000', '0x2A6DF'],
//        'CJK Compatibility Ideographs Supplement' => ['0x2F800', '0x2FA1F'],
//        'Tags' => ['0xE0000', '0xE007F'],

    ];


    function entityToUTF8($number)
    {
        if ($number < 0)
            return false;

        # Replace ASCII characters
        if ($number < 128)
            return chr($number);

        # Replace illegal Windows characters
        if ($number < 160) {
            switch ($number) {
                case 128:
                    $conversion = 8364;
                    break;
                case 129:
                    $conversion = 160;
                    break;
                case 130:
                    $conversion = 8218;
                    break;
                case 131:
                    $conversion = 402;
                    break;
                case 132:
                    $conversion = 8222;
                    break;
                case 133:
                    $conversion = 8230;
                    break;
                case 134:
                    $conversion = 8224;
                    break;
                case 135:
                    $conversion = 8225;
                    break;
                case 136:
                    $conversion = 710;
                    break;
                case 137:
                    $conversion = 8240;
                    break;
                case 138:
                    $conversion = 352;
                    break;
                case 139:
                    $conversion = 8249;
                    break;
                case 140:
                    $conversion = 338;
                    break;
                case 141:
                    $conversion = 160;
                    break;
                case 142:
                    $conversion = 381;
                    break;
                case 143:
                    $conversion = 160;
                    break;
                case 144:
                    $conversion = 160;
                    break;
                case 145:
                    $conversion = 8216;
                    break;
                case 146:
                    $conversion = 8217;
                    break;
                case 147:
                    $conversion = 8220;
                    break;
                case 148:
                    $conversion = 8221;
                    break;
                case 149:
                    $conversion = 8226;
                    break;
                case 150:
                    $conversion = 8211;
                    break;
                case 151:
                    $conversion = 8212;
                    break;
                case 152:
                    $conversion = 732;
                    break;
                case 153:
                    $conversion = 8482;
                    break;
                case 154:
                    $conversion = 353;
                    break;
                case 155:
                    $conversion = 8250;
                    break;
                case 156:
                    $conversion = 339;
                    break;
                case 157:
                    $conversion = 160;
                    break;
                case 158:
                    $conversion = 382;
                    break;
                case 159:
                    $conversion = 376;
                    break;
            }

            return $conversion;
        }

        if ($number < 2048)
            return chr(($number >> 6) + 192) . chr(($number & 63) + 128);
        if ($number < 65536)
            return chr(($number >> 12) + 224) . chr((($number >> 6) & 63) + 128) . chr(($number & 63) + 128);
        if ($number < 2097152)
            return chr(($number >> 18) + 240) . chr((($number >> 12) & 63) + 128) . chr((($number >> 6) & 63) + 128) . chr(($number & 63) + 128);

        return false;
    }

    function MBStrToHexes($str)
    {
        $str = mb_convert_encoding($str, 'UCS-4BE');
        $hexs = array();
        for ($i = 0; $i < mb_strlen($str, 'UCS-4BE'); $i++) {
            $s2 = mb_substr($str, $i, 1, 'UCS-4BE');
            $val = unpack('N', $s2);
            $hexs[] = str_pad(dechex($val[1]), 4, 0, STR_PAD_LEFT);
        }
        return ($hexs);
    }

    function detectRanges($str)
    {
        $flag_result = 0;

        $hexes = $this->MBStrToHexes($str);
        foreach ($hexes as $hex) {
            $hex = '0x' . $hex;
            foreach ($this->range as $name_sr => $subrange) {
                if (($hex >= $subrange[0]) && ($hex <= $subrange[1]) && $name_sr != 'Basic Latin') {
                    //echo $this->entityToUTF8(hexdec($hex)) . ' - ' . $name_sr . ' '.$hex.'   -> '.$subrange[0].'+'.$subrange[1].'<br />';
                    $this->list_lang[$name_sr] = 1;
                    $flag_result = 1;
                }
            }
            //if (!$flag_result AND !sizeof($this->list_lang)) echo $this->entityToUTF8($hex) . ' - Some Other Range<br />';


        }
    }





}