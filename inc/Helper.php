<?php
/*
  * @package IMKServicePlugin
  * */
namespace TBC\IMK;
class Helper {
    public static function slugify($text){
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }

    static function from_camel_case($str) {
        $str[0] = strtolower($str[0]);
        $func = create_function('$c', 'return "_" . strtolower($c[1]);');
        $strSlug = preg_replace_callback('/([A-Z])/', $func, $str);
        $journalName = str_replace('_', ' ', $strSlug);
        return strtoupper( $journalName );
    }
    static function limit_words($string, $word_limit){
        $endStr = '';
        $words = explode(" ", $string);
        if(count($words)>$word_limit){
            $endStr = '...';
        }
        return implode(" ", array_splice($words, 0, $word_limit)).$endStr;
    }
}
