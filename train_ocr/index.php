<html>
<head>
	<title>Test OCR</title>
	<script type="text/javascript">
		setTimeout(function() {
			location.href = '/';	
		}, 500);
	</script>
</head>	
<body>
<?php
set_time_limit ( 9999 ); // do not run longer than 5 minutes (adjust as needed)

$num_input = 160; // Размер входного вектора
$num_output = 1; // Выход, результат один
$num_layers = 3; // 3-ох слойная нейросеть
$num_neurons_hidden = 108;
$desired_error = 0.00001;
$max_epochs = 5000100; // Максимальное эпоха обучения нейросети
$epochs_between_reports = 10; // 10 эпох для обучения (временная шкала)

//Создает стандартную полностью подключенную нейронную сеть обратного распространения
$ocr_ann = fann_create_standard($num_layers, $num_input, $num_neurons_hidden, $num_output);

if ($ocr_ann) {
    echo 'Training OCR... '; 
    // Устанавливает функцию активации для всех скрытых слоев
    fann_set_activation_function_hidden($ocr_ann, FANN_SIGMOID_SYMMETRIC);
    // Устанавливает функцию активации для выходного слоя
    fann_set_activation_function_output($ocr_ann, FANN_SIGMOID_SYMMETRIC);

    $filename = dirname(__FILE__) . "/../ocr.data";
    // Обучение на полном наборе данных, прочитанном из файла, на временном интервале
    if (fann_train_on_file($ocr_ann, $filename, $max_epochs, $epochs_between_reports, $desired_error))
        fann_save($ocr_ann, dirname(__FILE__) . "/../ocr_float.net");

    fann_destroy($ocr_ann);
}

echo 'All Done! Now run <a href="/">Test OCR</a><br>' . PHP_EOL;
?>
</body>
</html>