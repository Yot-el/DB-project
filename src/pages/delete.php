<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . '/core/DeletePage.php';
  $db = new Database();
  $page = new DeletePage();
  $page->setDatabase($db);

  $queries = array();
  parse_str($_SERVER['QUERY_STRING'], $queries);

  try {
    if (count($queries)) {
      $page->delete($queries);
    }
  } catch (Exception $exception) {
    $error = $exception;
  }
?>
<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/base.css"> 
    <title>Аренда офисов</title>
  </head>
  <body class="main">
    <header class="header">
      <h1 class="title">Удаление объекта ИС</h1>
      <a class="header__link" href="../index.php">Вернуться на главную</a>
    </header>
    <section class="container">
      <p>
        <?=$page->message?>
      </p>
    </section>
  </body>
</html>