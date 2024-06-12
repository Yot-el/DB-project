<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . '/core/OfficesPage.php';
  $db = new Database();
  $page = new OfficesPage();
  $page->setDatabase($db);
  $org_name = null;

  $queries = array();
  parse_str($_SERVER['QUERY_STRING'], $queries);

  $page->setOwnerId($queries["owner_id"]);
  unset($queries["owner_id"]);

  try {
    $page->fetchOffices($queries);
    $page->fetchTotalPagesNumber($queries);
    $org_name = $page->getOwnerOrganisationName();
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
    <link rel="stylesheet" href="../styles/form.css">
    <link rel="stylesheet" href="../styles/offices-list.css">
    <title>Аренда офисов</title>
  </head>
  <body class="main">
    <header class="header">
      <h1 class="title">Офисы</h1>
      <a class="header__link" href="../index.php">Вернуться на главную</a>
    </header>
    <div class="sections-wrapper container">
      <section class="main__offices-section">
        <h2>
          Список офисов <?= $org_name == null ? "собственника ID " . $page->getOwnerId() : "компании " . $org_name ?> 
        </h2>
        <?php if (!$page->getItems()) { ?>
          <p>Нет офисов</p>
        <?php } else { ?>
        <form class="form" action="" method="GET">
          <input hidden name="owner_id" value="<?=$page->getOwnerId()?>"/>
          <label class="form-label">
            Цена
            <select name="monthly_rent">
              <option value="cheap" 
              <?php if (!isset($queries["monthly_rent"]) or $queries["monthly_rent"] == "cheap") { ?> selected <?php } ?>
              >
                Сначала недорогие
              </option>
              <option value="expensive"
              <?php if (isset($queries["monthly_rent"]) and $queries["monthly_rent"] == "expensive") { ?> selected <?php } ?>
              >
                Сначала дорогие
              </option>
            </select>
          </label>
          <label class="form-label" for="total_surface_limit">
            Площадь до <span class="total-surface-limit"></span>м<sup>2</sup>
          </label>
          <input id="total_surface_limit" min="0" max="1500" type="range" name="total_surface_limit"
            value="<?= isset($queries["total_surface_limit"]) ? $queries["total_surface_limit"] : 50 ?>"
          >
          <button type="submit">Отсортировать</button>
        </form>
        <ul class="office-list">
          <?php foreach ($page->getItems() as $office) { ?>
              <li class="office-list__item">
                <a class="office-list__link" href="office.php?id=<?=$office["id"]?>">
                  <h3 class="office-list__id">
                    Офис ID <?= $office["id"] ?>
                  </h3>
                </a>
                <span>
                  Цена за месяц: <?= $office["monthly_rent"] ?>
                </span>
                <span>
                  Площадь: <?= $office["total_surface"] ?> м<sup>2</sup>
                </span>
            </li>
          <?php } ?>
        </ul>
        <div class="pagination">
          <?php if ($page->getPage() > 1) { ?>
            <a href="offices.php?page=<?=$page->getPage() - 1?>&<?=$_SERVER['QUERY_STRING']?>" class="pagination__item">
              Назад
            </a>
          <?php } ?>
          <a href="#" class="pagination__item pagination__item--current">
            <?= $page->getPage() ?>
          </a>
          <?php if ($page->getPage() < $page->getTotalPagesNumber()) { ?>
            <a href="offices.php?page=<?=$page->getPage() + 1?>&<?=$_SERVER['QUERY_STRING']?>" class="pagination__item">
            Далее
          </a>
          <?php } ?>
        </div>
        <?php } ?>
      </section>
    </div>
    <script type="text/javascript" src="../js/offices.js"></script>
  </body>
</html>