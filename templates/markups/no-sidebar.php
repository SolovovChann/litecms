<!DOCTYPE html>
<html lang="ru">

<head>
  <title><?= $title ?> | <?= \Litecms\Config\Name ?></title>

  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="/static/css/style.min.css">
  <link rel="shortcut icon" href="/static/favicon.ico" type="image/x-icon">
</head>

<body>
  <? \Litecms\Utils\Message::show(); ?>
  <main class="main container">
    <?= $MAIN ?>
  </main>
</body>

</html>