<?php $title = 'OCR Test:' ?>
<html>
    <head>
    <title><?= $title ?></title>    
    <link rel="stylesheet" href="/main.css">
    </head>
    <body>
        <h1 class='blue'><?= $title ?></h1>
        <ul>
            <li>
                <a href="/?filename=fann.png">
                    <img width="" src="/img/fann.png" />
                </a>
            </li>
            <li>
                <a href="/?filename=Hello_world.png">
                    <img width="" src="/img/Hello_world.png" />
                </a>
            </li>   
            <li>
                <a href="/?filename=php.png">
                    <img width="" src="/img/php.png" />
                </a>
            </li>   
        </ul>
        <div class="main">
        <?php if (!empty($_GET['filename'])): ?>    
            <?php require_once "controller.php" ?>
        <?php endif; ?> 
        </div>   
        <h1 style="clear: both;"><?= $stringRes ?></h1>
    </body>
</html>