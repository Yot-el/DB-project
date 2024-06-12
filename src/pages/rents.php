<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . '/core/RentsPage.php';

  $db = new Database();
  $page = new RentsPage();
  $page->setDatabase($db);

  if (count($_POST)) {
    $page->updateRent($_POST);
  }

  $queries = array();
  parse_str($_SERVER['QUERY_STRING'], $queries);

  if (isset($queries["client_id"])) {
    $page->setClientId($queries["client_id"]);
  }

  if (isset($queries["office_id"])) {
    $page->setOfficeId($queries["office_id"]);
  }

  try {
    $page->fetchRents($queries);
    $termination_reasons = $db->getItems("select * from termination_reason");
  } catch (Exception $exception) {
    echo $exception->getMessage();
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
    <link rel="stylesheet" href="../styles/pagination.css"> 
    <link rel="stylesheet" href="../styles/rent.css">
    <link rel="stylesheet" href="../styles/form.css">
    <title>Аренда офисов</title>
  </head>
  <body class="main">
    <header class="header">
      <h1 class="title">Аренды</h1>
      <a class="header__link" href="../index.php">Вернуться на главную</a>
    </header>
    <div class="sections-wrapper container">
      <section class="main__rents-section">
        <form class="form" action="" method="GET">
            <?php if ($page->getClientId()) { ?>
              <input hidden name="client_id" value="<?=$page->getClientId()?>"/>
            <?php } ?>
            <?php if ($page->getOfficeId()) { ?>
              <input hidden name="office_id" value="<?=$page->getOfficeId()?>"/>
            <?php } ?>
            <div>
              <select name="rent_type">
                <option value="all" selected>
                  Все
                </option>
                <option value="active">
                  Незавершенные
                </option>
                <option value="inactive">
                  Завершенные
                </option>
              </select>
              <button type="submit">Отсортировать</button>
            </div>
        </form>
        <?php if (!$page->getItems()) { ?>
          <p>Нет аренд</p>
        <?php } else { ?>
        <ul class="rent-list">
          <?php foreach ($page->getItems() as $rent) { ?>
            <li class="rent-list__item">
              <?php if ($rent->isActive()) { ?>
                <span class="rent-list__type rent-list__type--active">Активная</span>
              <?php } else { ?>
                <span class="rent-list__type rent-list__type--inactive">Неактивная</span>
              <?php } ?>
              <h3 class="rent-list__id">
                Аренда ID <?= $rent->id ?>
              </h3>
              <div class="rent-list__dates">
                <?= $rent->start_date ?> - <?= $rent->end_date ?>
              </div>
              <div>
                <a class="rent-list__link" href="client.php?id=<?= $rent->client_id?>">
                  Арендатор ID <?= $rent->client_id ?>
                </a>
              </div>
              <div>
                <a class="rent-list__link" href="office.php?id=<?=$rent->office_id?>">
                  Офис ID <?= $rent->office_id ?>
                </a>
              </div>
              <?php if (!$rent->isActive()) { ?>
              <div class="rent-list__row">
                Дата фактического расторжения договора:
                <?= $rent->termination_date ?>
              </div>
              <div class="rent-list__row">
                Причина: <?= $rent->getTerminationReason() ?>
              </div>
              <?php } else { ?>
                <button class="button button--delete rent__button" data-id="<?=$rent->id?>" data-start-date="<?=$rent->start_date?>" data-end-date="<?=$rent->end_date?>">
                  Завершить аренду
                </button>
              <?php } ?>
            </li>
          <?php } ?>
        </ul>
        <div class="pagination">
          <?php if ($page->getPage() > 1) { ?>
            <a href="rents.php?page=<?=$page->getPage() - 1?>&<?=$_SERVER['QUERY_STRING']?>" class="pagination__item">
              Назад
            </a>
          <?php } ?>
          <a href="#" class="pagination__item pagination__item--current">
            <?= $page->getPage() ?>
          </a>
          <?php if ($page->getPage() < $page->getTotalPagesNumber()) { ?>
            <a href="rents.php?page=<?=$page->getPage() + 1?>&<?=$_SERVER['QUERY_STRING']?>" class="pagination__item">
            Далее
          </a>
          <?php } ?>
        </div>
        <?php } ?>
      </section>
    </div>
    <dialog class="delete-modal">
      <h2>Завершение аренды ID <span class="delete-rent-id"></span></h2>
      <button class="delete-modal-close">
        <svg class="delete-modal-icon" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M6.4 19L5 17.6l5.6-5.6L5 6.4L6.4 5l5.6 5.6L17.6 5L19 6.4L13.4 12l5.6 5.6l-1.4 1.4l-5.6-5.6z"/></svg>
      </button>
      <form class="form" action="" method="post">
        <input hidden name="id" value=""/>
        <label class="form-label">
          Дата расторжения договора
          <input name="termination_date" type="date" required/>
        </label>
        <label class="form-label" for="termination_reason">
          Причина расторжения договора
        </label>
        <select name="termination_reason_id" id="termination_reason">
            <?php foreach ($termination_reasons as $reason) { ?>
              <option value="<?=$reason["id"]?>">
                <?= $reason["name"] ?>
              </option>
            <?php } ?>
          </select>
        <button type="submit">Обновить</button>
      </form>
    </dialog>
    <script type="text/javascript" src="../js/rent.js"></script>
  </body>
</html>