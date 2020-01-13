<?php

require_once "./db.php";

define('DB_STRING', 'sqlite:text.db');
define('DB_USER', NULL);
define('DB_PASS', NULL);

$db = new \Common\Repository;

/**
* Функция распознования текста
*
*@return chart
*/
function OCR($img, $expected, $input, $lookup_array, $ann) {
    echo '<div class="block_chart">' . PHP_EOL;
    echo "Image: <img src='/img?code=$img'><br>" . PHP_EOL;

    // Выбираем значение из нейронной сети если пиксели кодировки сошлись
    $calc_out = fann_run($ann, $input);
    
    echo 'Raw: ' .  $calc_out[0] . '<br>' . PHP_EOL;
    echo 'Trimmed: ' . floor($calc_out[0]*100)/100 . '<br>' . PHP_EOL;
    echo 'Decoded Symbol: ';
    
    for($i = 0; $i < count($lookup_array); $i++) {
        // Если символы сошлись не только по битам, но и по коду символа
       if( floor($lookup_array[$i][0]*100)/100 == floor($calc_out[0]*100)/100) {
            echo $lookup_array[$i][1] . '<br>' . PHP_EOL;
            echo "Expected: $expected <br>" . PHP_EOL;
            echo 'Result: ';
            if($expected == $lookup_array[$i][1]){
                $res = $expected;
                echo '<span class="green">Correct!</span>';
            }else{
                $res = false;
                echo '<span class="red">Incorrect!</span> <a href="/train_ocr">Retrain OCR</a>';
            }
        }
    }
    echo '<br><br>' . PHP_EOL;
    echo '</div>' . PHP_EOL;

    return $res;
}

// Файл обученной сети (знания)
$train_file = (dirname(__FILE__) . '/ocr_float.net');
if (!is_file($train_file))
    die('<span class="red">The file ocr_float.net has not been created!</span><a href="/train_ocr">Train OCR</a>' . PHP_EOL);

$ocr_ann = fann_create_from_file($train_file);
if ($ocr_ann) {
    
    // Кодируем ansi по коду для поиска
    $result_lookup_array = [];
    $curr = 0.00;
    for($i = 32; $i <= 126; $i++) {
        array_push($result_lookup_array, array($curr, chr($i)));
        $curr+= 0.01;
    }

    // Картинка которую распознать
    $im = imagecreatefrompng('img' . DIRECTORY_SEPARATOR . $_GET['filename']);
    $width = getimagesize('img' . DIRECTORY_SEPARATOR . $_GET['filename'])[0];
    $countChars = $width / 10;

    $stringRes = '';
    $testChar = [];
    //Режим картинку на символы
    for ($key = 0; $key<$countChars;$key++) {
        for ($y = 0; $y<16; $y++) {
            for ($x = $key*10; $x<$key*10+10; $x++) {
                $testChar[$key][] = imagecolorat($im, $x, $y) ? 1 : 0;
            }
        }
                
    }

    $alphavit = $db->getMany("SELECT code, title FROM alphavit;");

// Распознаем текс
    foreach ($testChar as $char) {
        foreach ($alphavit as $key => $value) {
            $res = OCR($value['code'], $value['title'], $char, $result_lookup_array, $ocr_ann);
            if ($res || $res == ' ')
                $stringRes .= $res; // Распознаный текст
        }
        
    }


    fann_destroy($ocr_ann);
} else {
    die("<span class='red'>Invalid file format.</span>" . PHP_EOL);
}
