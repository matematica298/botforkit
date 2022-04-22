<?php

namespace App\Modules;

use App\Models\Group;

class Auxiliary
{
    /**
     * Эмодзи с мордочкой мужчины с усами
     */
    const EMOJI_CLASSIC = '&#129333; ';

    /**
     * Прилагательные
     */
    const ADJECTIVES = [
        'Бездушный',
        'Бездарный',
        'Великий',
        'Веселый',
        'Высокомерный',
        'Вялый',
        'Гадкий',
        'Глупый',
        'Гениальный',
        'Дерзкий',
        'Добрый',
        'Дурной',
        'Жуткий',
        'Женоподобный',
        'Женственный',
        'Забавный',
        'Злой',
        'Идеальный',
        'Классный',
        'Любопытный',
        'Мерзкий',
        'Милый',
        'Модный',
        'Наглый',
        'Нежный',
        'Нелепый',
        'Неплохой',
        'Обаятельный',
        'Отвратный',
        'Особенный',
        'Очаровательный',
        'Пленительный',
        'Позитивный',
        'Пошлый',
        'Редкий',
        'Романтичный',
        'Самолюбивый',
        'Сексуальный',
        'Серьёзный',
        'Скромный',
        'Скучный',
        'Сложный',
        'Смешной',
        'Странный',
        'Хитрый',
        'Хороший',
        'Четкий',
        'Чудовищный',
        'Чокнутый',
        'Безумный',
        'Шикарный',
        'Эгоистичный',
        'Элитный',
        'Эффектный',
    ];

    /**
     * Вернуть в зависимости от поданного числа (sm_num) правильную форму слова.
     * @param $number
     * @param $manyAnswer
     * @param $someAnswer
     * @param $oneAnswer
     * @return mixed
     */
    public static function getRightWord($number, $manyAnswer, $someAnswer, $oneAnswer)
    {
        $remTen = ($remHun = $number % 100) % 10;

        if ((($remTen == 2) || ($remTen == 3) || ($remTen == 4)) && ($remHun != 12) && ($remHun != 13) && ($remHun != 14)) {
            return $someAnswer;
        } elseif (($remTen == 1) && ($remHun != 11)) {
            return $oneAnswer;
        } else {
            return $manyAnswer;
        }
    }

    /**
     * Вычленить айди из строки вида [id363636|Text]
     * @param string $str
     * @return false|string
     */
    public static function getStringId(string $str)
    {
        return substr($str, 3, strpos($str, "|") - 3);
    }

    /**
     * Получить случайный элемент из переданного массива array
     * @param array $array
     * @return mixed
     */
    public static function getRandomElement(array $array)
    {
        return $array[rand(0, count($array) - 1)];
    }

    /**
     * Получить случайный индекс из переданного массива array
     * @param $array
     * @return int
     */
    public static function getRandomKey($array)
    {
        return rand(0, count($array) - 1);
    }

    /**
     * Получить число (num) случайных элементов из массива (array)
     * @param $num
     * @param $array
     * @return string|void
     */
    public static function chooseFrom($num, $array)
    {
        if ($num > count($array)) {
            return; # Если $num больше длины массива, выходим из функции
        }

        $answer = array(); # создаём пустой массива ответа

        for ($i = 0; $i < $num; $i++) {
            $key = Auxiliary::getRandomKey($array); # формируем случайный ключ массива
            array_push($answer, $array[$key]); # помещаем в массива ответа случайный элемент
            array_splice($array, $key, 1); # вырезаем из нашего массива сформированный случайный ключ
        }

        return implode(" ", $answer); # возвращаем строку из массива, разделённую пробелами
    }

    /**
     * Перевести кашу в нормальный текст
     * @param $input_string
     * @return string
     */
    public static function transformEngRus($input_string)
    {
        $translate_arr = array('a' => 'ф','b' => 'и','c' => 'с','d' => 'в','e' => 'у','f' => 'а','g' => 'п','h' => 'р','i' => 'ш','j' => 'о','k' => 'л','l' => 'д',
            'm' => 'ь','n' => 'т','o' => 'щ','p' => 'з','q' => 'й','r' => 'к','s' => 'ы','t' => 'е','u' => 'г','v' => 'м','w' => 'ц','x' => 'ч','y' => 'н',
            'z' => 'я','[' => 'х',']' => 'ъ',';' => 'ж',"'" => 'э',',' => 'б','.' => 'ю','{' => 'х','}' => 'ъ',':' => 'ж','"' => 'э','<' => 'б','>' => 'ю','&' => '?');
        $result = '';

        for ($i = 0; $i < strlen($input_string); $i++) {
            $result .= array_key_exists(mb_strtolower($input_string[$i]), $translate_arr) ? $translate_arr[$input_string[$i]] : $input_string[$i];
        }

        return $result;
    }

    /**
     * Получить составляющие даты
     * @param $date_param
     * @param $array_param
     * @return string
     */
    public static function transformMyDate($date_param, $array_param)
    {
        $diff = $date_param->diff(date_create());
        $strAnswer = "";

        foreach($array_param as $key => $part) {
            $tmp = $diff->format($key);

            if ($tmp > 0)
                $strAnswer .= $tmp." ".((is_array($part)) ? self::getRightWord($tmp, $part[0], $part[1], $part[2]) : $part)." ";
        }

        return rtrim($strAnswer);
    }

    /**
     * @param $string
     * @param string $e
     * @return string
     */
    public static function skz_ucfirst($string, $e ='utf-8')
    {
        if (function_exists('mb_strtoupper') && function_exists('mb_substr') && !empty($string)) {
            $string = mb_strtolower($string, $e);
            $upper = mb_strtoupper($string, $e);
            preg_match('#(.)#us', $upper, $matches);
            $string = $matches[1] . mb_substr($string, 1, mb_strlen($string, $e), $e);
        } else {
            $string = ucfirst($string);
        }

        return $string;
    }

    public static function getGroupByNumber(string $number)
    {
        return Group::query()->where('number', $number)->get()->first() ?? false;
    }
}
