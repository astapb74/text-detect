<?php $title = 'OCR Test:' ?>
<html>
    <head>
    <title><?= $title ?></title>    
    <style>
        .blue{color:blue;}
        .green{color:green;}
        .red{color:red;}
    </style>
    </head>
    <body>
        <h1 class='blue'><?= $title ?></h1>
<?php

function OCR($img, $expected, $input, $lookup_array, $ann) {
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
                echo '<span class="green">Correct!</span>';
            }else{
                echo '<span class="red">Incorrect!</span> <a href="train_ocr.php">Retrain OCR</a>';
            }
        }
    }
    echo '<br><br>' . PHP_EOL;
    
}


$train_file = (dirname(__FILE__) . '/ocr_float.net');
if (!is_file($train_file))
    die('<span class="red">The file ocr_float.net has not been created!</span><a href="train_ocr.php">Train OCR</a>' . PHP_EOL);

$ocr_ann = fann_create_from_file($train_file);
if ($ocr_ann) {
    
    $result_lookup_array = array();
    $curr = 0.00;
    for($i = 33; $i <= 126; $i++) {
        array_push($result_lookup_array, array($curr, chr($i)));
        $curr+= 0.01;
    }

    $im = imagecreatefrompng("37.png");

    $test_F = [];
    for ($y = 0; $y<16; $y++) {
        for ($x = 0; $x<10; $x++) {
            $test_F[] = imagecolorat($im, $x, $y);
        }
    }
    
    
    $im = imagecreatefrompng("32.png");

    $test_A = [];
    for ($y = 0; $y<16; $y++) {
        for ($x = 0; $x<10; $x++) {
            $test_A[] = imagecolorat($im, $x, $y);
        }
    }
    
    $im = imagecreatefrompng("45.png");

    $test_N = [];
    for ($y = 0; $y<16; $y++) {
        for ($x = 0; $x<10; $x++) {
            $test_N[] = imagecolorat($im, $x, $y);
        }
    }

    OCR('37', 'F', $test_F, $result_lookup_array, $ocr_ann);
    OCR('32', 'A', $test_A, $result_lookup_array, $ocr_ann);
    OCR('45', 'N', $test_N, $result_lookup_array, $ocr_ann);
    OCR('45', 'N', $test_N, $result_lookup_array, $ocr_ann);
    
    fann_destroy($ocr_ann);
} else {
    die("<span class='red'>Invalid file format.</span>" . PHP_EOL);
}

?>
    </body>
</html>