<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . '/core/ClientPage.php';
  $db = new Database();
  $page = new ClientPage();
  $page->setDatabase($db);
  $client = null;

  $queries = array();
  parse_str($_SERVER['QUERY_STRING'], $queries);

  try {
    $client = $page->fetchClient($queries["id"]);

    if (count($_POST)) {
      $client = $page->updateClient($_POST);
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
    <link rel="stylesheet" href="../styles/client-card.css"> 
    <link rel="stylesheet" href="../styles/pagination.css"> 
    <link rel="stylesheet" href="../styles/client.css">
    <link rel="stylesheet" href="../styles/form.css">
    <title>Аренда офисов</title>
  </head>
  <body class="main">
    <header class="header">
      <h1 class="title">Клиент</h1>
      <a class="header__link" href="../index.php">Вернуться на главную</a>
    </header>
    <section class="main__clients-section container">
        <?php if (isset($exception)) { ?>
          <div class="error">Произошла ошибка! <?= $exception ?></div>
        <?php } ?>
        <?php if ($client) { ?>
          <div class="client-card">
          <div class="client-card__content"> 
            <span class="client-card__type">
              <?= $client->organisationName ? $client->organisationName : 'Собственник ID: '.$client->id ?>
            </span>
            <div class="client-card__header">
              <h3 class="client-card__name">
                <?= $client->contactPerson ?>
              </h3>
              <a class="link" href="mailto:<?= $client->email?>">
                  <?= $client->email ?>
                </a>
            </div>
            <?php
                if ($client->phoneNumber) { ?>
                <a class="link" href="tel:<?= $client->phoneNumber?>">
                  <?= $client->phoneNumber ?>
                </a>
              <?php 
                } 
              ?>
            <address class="client-card__address">
              <?= $client->address ? $client->address : "Адрес не указан" ?>
            </address>
          </div>
          <div class="client-card__buttons">
            <div class="client-card__buttons-row">
              <a href="#" class="button button--delete delete-modal-open" >Удалить пользователя</a>
            </div>
            <div class="client-card__buttons-row">
              <a href="add-rent.php" class="button">Добавить аренду</a>
              <a href="#" class="button">Добавить офис</a>
            </div>
          </div>
          </div>
          <div class="other-pages-buttons">
            <a href="rents.php?client_id=<?=$client->id?>" class="button">Список аренд</a>
            <a href="offices.php?owner_id=<?=$client->id?>" class="button">Список офисов</a>
          </div>
        <?php } ?>
    </section>
    <section class="container">
      <?php if ($client) { ?>
        <h2>Изменить пользователя</h2>
      <form class="form" action="" method="post">
        <label class="form-label">
          Название организации
          <input name="organisation_name" type="text"/>
        </label>
        <label class="form-label">
          Контактное лицо
          <input name="contact_person" type="text"/>
        </label>
        <label class="form-label">
          Email
          <input name="email" type="email"/>
        </label>
        <label class="form-label">
          Телефон
          <input placeholder="+33 (431) 731-9003" name="phone_number" type="tel"/>
        </label>
        <label class="form-label">
          Адрес
          <input name="address" type="text"/>
        </label>
        <button type="submit">Обновить</button>
      </form>
      <?php } ?>
    </section>
    <dialog class="delete-modal">
      <h2>Вы точно хотите удалить этого пользователя?</h2>
      <div class="delete-modal__buttons">
        <a class="button button--delete" href="delete.php?client_id=<?=$client->id?>">Удалить пользователя</a>
        <a href="#" class="button delete-modal-close">Назад</a>
      </div>
    </dialog>
    <script type="text/javascript" src="../js/client.js"></script>
  </body>
</html>