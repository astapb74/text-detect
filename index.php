<?php $title = 'OCR Test:' ?>
<html>
    <head>
    <title><?= $title ?></title>    
    <link rel="stylesheet" href="/main.css">
    </head>
    <body>
        <h1 class='blue'><?= $title ?></h1>
        <ul class="menu">
            <li>
                <a href="/?filename=fann.png">
                    <img <?= (!empty($_GET['filename']) && $_GET['filename'] == 'fann.png' ? 'class="active"' : '') ?> src="/img/fann.png" />
                </a>
            </li>
            <li>
                <a href="/?filename=Hello_world.png">
                    <img <?= (!empty($_GET['filename']) && $_GET['filename'] == 'Hello_world.png' ? 'class="active"' : '') ?> src="/img/Hello_world.png" />
                </a>
            </li>   
            <li>
                <a href="/?filename=php.png">
                    <img <?= (!empty($_GET['filename']) && $_GET['filename'] == 'php.png' ? 'class="active"' : '') ?> src="/img/php.png" />
                </a>
            </li>
             <li>
                <a href="/?filename=web.png">
                    <img <?= (!empty($_GET['filename']) && $_GET['filename'] == 'web.png' ? 'class="active"' : '') ?> src="/img/web.png" />
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