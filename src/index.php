<?php
  require_once __DIR__.'/core/ClientsPage.php';
  $queries = array();
  parse_str($_SERVER['QUERY_STRING'], $queries);
  $db = new Database();
  $page = new ClientsPage();
  $page->setDatabase($db);

  if (isset($queries["limit"])) {
    $page->setLimit($queries["limit"]);
  }
  if (isset($queries["page"])) {
    $page->setPage($queries["page"]);
  }

  if (isset($queries["search"]) && $queries["search"]) {
    $page->fetchQueryParam($queries["search"]);
  } else {
    $page->fetchItemsFromDB("client");
  }

  $page->fetchTotalPagesNumber();
?>
<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/base.css">  
    <link rel="stylesheet" href="styles/client-card.css"> 
    <link rel="stylesheet" href="styles/pagination.css"> 
    <link rel="stylesheet" href="styles/form.css"> 
    <link rel="stylesheet" href="index.css">
    <title>Аренда офисов</title>
  </head>
  <body class="main">
    <header class="header">
      <h1 class="title">Список пользователей (компаний)</h1>
    </header>
    <section class="main__clients-section container">
      <form action="" method="GET" class="form main__form">
        <input class="form-search" type="search" placeholder="Поиск по ID собственника/Названию компании" name="search">
        <button type="submit">Найти</button>
      </form>
      <div class="main__clients-list">
        <?php
          foreach($page->getItems() as $item) {
        ?>
          <div class="client-card">
            <div class="client-card__content"> 
              <span class="client-card__type">
                <?= $item["organisation_name"] ? $item["organisation_name"] : 'Собственник ID: '.$item["id"] ?>
              </span>
              <div class="client-card__header">
                <h3 class="client-card__name">
                  <?= $item["contact_person"]?>
                </h3>
                <a class="link" href="mailto:<?= $item["email"]?>">
                  <?= $item["email"]?>
                </a>
              </div>
              <?php
                if ($item["phone_number"]) { ?>
                <a class="link" href="tel:<?= $item["phone_number"]?>">
                  <?= $item["phone_number"]?>
                </a>
              <?php 
                } 
              ?>
              <address class="client-card__address">
                <?= $item["address"] ? $item["address"] : "Адрес не указан" ?>
              </address>
            </div>
            <div class="client-card__buttons">
              <a href="/pages/client.php?id=<?= $item["id"]?>" class="button client-card__button">Показать информацию</a>
            </div>
          </div>
        <?php 
          }
        ?>
      </div>
      <div class="pagination">
        <?php if ($page->getPage() > 1) { ?>
            <a href="index.php?page=<?=$page->getPage() - 1?>" class="pagination__item">
              Назад
            </a>
            <a href="index.php?page=<?=$page->getPage() - 1?>" class="pagination__item"><?= $page->getPage() - 1 ?></a>
          <?php } ?>
        <a href="#" class="pagination__item pagination__item--current">
          <?= $page->getPage() ?>
        </a>
        <?php if ($page->getPage() < $page->getTotalPagesNumber()) { ?>
          <a href="index.php?page=<?=$page->getPage() + 1?>" class="pagination__item"><?= $page->getPage() + 1 ?></a>
          <a href="index.php?page=<?=$page->getPage() + 1?>" class="pagination__item">
            Далее
          </a>
        <?php } ?>
      </div>
    </section>
  </body>
</html>