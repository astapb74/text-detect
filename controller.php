<?php

/**
* Функция распознования текста
*
*@return chart
*/
function OCR($img, $expected, $input, $lookup_array, $ann) {
    echo '<div class="block_chart">' . PHP_EOL;
    echo "Image: <img src='images.php?code=$img'><br>" . PHP_EOL;

    $calc_out = fann_run($ann, $input);
    
    echo 'Raw: ' .  $calc_out[0] . '<br>' . PHP_EOL;
    echo 'Trimmed: ' . floor($calc_out[0]*100)/100 . '<br>' . PHP_EOL;
    echo 'Decoded Symbol: ';
    
    for($i = 0; $i < count($lookup_array); $i++) {
       if( floor($lookup_array[$i][0]*100)/100 == floor($calc_out[0]*100)/100) {
            echo $lookup_array[$i][1] . '<br>' . PHP_EOL;
            echo "Expected: $expected <br>" . PHP_EOL;
            echo 'Result: ';
            if($expected == $lookup_array[$i][1]){
                $res = $expected;
                echo '<span class="green">Correct!</span>';
            }else{
                $res = false;
                echo '<span class="red">Incorrect!</span> <a href="train_ocr.php">Retrain OCR</a>';
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
    die('<span class="red">The file ocr_float.net has not been created!</span><a href="train_ocr.php">Train OCR</a>' . PHP_EOL);

$ocr_ann = fann_create_from_file($train_file);
if ($ocr_ann) {
    
    $result_lookup_array = [];
    $curr = 0.00;
    for($i = 33; $i <= 126; $i++) {
        array_push($result_lookup_array, array($curr, chr($i)));
        $curr+= 0.01;
    }

    // Картинка которую распознать
    $im = imagecreatefrompng("fann.png");

    $alphavit = ['F', 'A', 'N', 'N'];
    $codes = [37, 32, 45, 45];
    
    $stringRes = '';
    foreach ($alphavit as $key => $value) {
        $testChar = [];
        //Режим картинку на символы
        for ($y = 0; $y<16; $y++) {
            for ($x = $key*10; $x<$key*10+10; $x++) {
                $testChar[] = imagecolorat($im, $x, $y) ? 1 : 0;
            }
        }
        
        // Распознаем текс
        $res = OCR($codes[$key], $value, $testChar, $result_lookup_array, $ocr_ann);
        if ($res)
            $stringRes .= $res; // Распознаный текст
    }


    fann_destroy($ocr_ann);
} else {
    die("<span class='red'>Invalid file format.</span>" . PHP_EOL);
}
