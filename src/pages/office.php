<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . '/core/OfficePage.php';

  $db = new Database();
  $page = new OfficePage();
  $page->setDatabase($db);

  $queries = array();
  parse_str($_SERVER['QUERY_STRING'], $queries);

  try {
    $office = $page->fetchOffice($queries["id"]);
    $current_rent = $office->getCurrentRent();

    if (count($_POST)) {
      $office = $page->updateOffice($_POST);
    }

    $building_types = $db->getItems("select * from building_type");
    $building_conditions = $db->getItems("select * from building_condition");
    $clients = $db->getItems("select organisation_name, id from client");
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
    <link rel="stylesheet" href="../styles/office.css"> 
    <link rel="stylesheet" href="../styles/form.css">
    <title>Аренда офисов</title>
  </head>
  <body class="main">
    <header class="header">
      <h1 class="title">Офис</h1>
      <a class="header__link" href="../index.php">Вернуться на главную</a>
    </header>
    <section class="office container">
      <div class="info">
        <p>
          На данный момент офис
          <?php if (is_null($current_rent)) { ?>
            свободен
          <?php } else { ?>
            арендуется <a class="link client-link" href="/pages/client.php?id=<?= $current_rent["client_id"]?>">ID <?= $current_rent["client_id"] ?></a>
          <?php } ?>
        </p>
        <h2>Основная информация</h2>
        <dl class="info-list">
            <dt class="info-name">
              Владелец
            </dt>
            <dd class="info-description">
              <a href="/pages/client.php?id=<?= $office->owner_id?>" class="link client-link">
                <?= $office->getOwnerOrganisationName() ? $office->getOwnerOrganisationName() : 'Собственник ID: '.$office->owner_id ?>
              </a>
            </dd>

            <?php
              $type = $office->getBuildingType();
              if (!is_null($type)) {
            ?>
              <dt class="info-name">
                Тип помещения
              </dt>
              <dd class="info-description">
                <?= $type ?>
              </dd>
            <?php } ?>
            
            <?php
              $condition = $office->getBuildingCondition();
              if (!is_null($condition)) { 
            ?>
              <dt class="info-name">
                Состояние помещения
              </dt>
              <dd class="info-description">
                <?= $condition ?>
              </dd>
            <?php } ?>

            <dt class="info-name">
              Адрес
            </dt>
            <dd class="info-description">
              <?= $office->address ?>
            </dd>

            <?php if (!is_null($office->floor)) { ?>
              <dt class="info-name">
                Этаж
              </dt>
              <dd class="info-description">
                <?= $office->floor ?>
              </dd>
            <?php } ?>

            <dt class="info-name">
              Площадь
            </dt>
            <dd class="info-description">
              <?= $office->total_surface ?> м<sup>2</sup>
            </dd>
            <dt class="info-name">
              Плата за месяц
            </dt>
            <dd class="info-description">
              <?= $office->monthly_rent ?>
            </dd>
        </dl>
      </div>
      <div class="info">
        <h2>Характеристики</h2>
        <dl class="info-list">
          <?php foreach ($office->properties as $property) { 
            ?>
            <dt class="info-name">
              <?=$property[0]?>
            </dt>
            <dd class="info-description">
              <?=$property[1] ? "Да" : "Нет"?>
            </dd>
          <?php } ?>
      </dl>
      </div>
      <div>
        <a href="rents.php?office_id=<?=$office->id?>" class="button">Список аренд</a>
        <a href="#" class="button button--delete" >Удалить офис</a>
      </div>
    </section>
    <section class="container">
      <h2>Изменить офис</h2>
      <form class="form" action="" method="post">
        <label class="form-label">
          Владелец
          <select name="owner_id">
            <?php foreach ($clients as $client) { ?>
              <option value="<?=$client["id"]?>"
              <?php if ($office->owner_id == $client["id"]) { ?> selected <?php } ?>
              >
                <?= $client["organisation_name"] ? $client["organisation_name"] : "Собственник ID " . $client["id"] ?>
              </option>
            <?php } ?>
          </select>
        </label>
        <label class="form-label">
            Тип помещения
            <select name="building_type_id">
              <?php foreach ($building_types as $b_type) { ?>
                <option value="<?=$b_type["id"]?>"
                <?php if (!is_null($type) and $b_type["name"] == $type) { ?> selected <?php } ?>
                >
                  <?= $b_type["name"] ?>
                </option>
              <?php } ?>
            </select>
          </label>
        <label class="form-label">
          Состояние помещения
          <select name="building_condition_id">
            <?php foreach ($building_conditions as $b_condition) { ?>
              <option value="<?=$b_condition["id"]?>"
              <?php if (!is_null($condition) and $b_condition["name"] == $condition) { ?> selected <?php } ?>
              >
                <?= $b_condition["name"] ?>
              </option>
            <?php } ?>
          </select>
        </label>
        <label class="form-label">
          Адрес
          <input name="address" type="text"/>
        </label>
        <label class="form-label">
          Этаж
          <input name="floor" type="number"/>
        </label>
        <label class="form-label">
          Площадь
          <input name="total_surface" type="number"/>
        </label>
        <label class="form-label">
          Плата за месяц
          <input name="monthly_rent" type="number"/>
        </label>
        <fieldset>
          <legend>Характеристики</legend>
          <?php foreach ($office->properties as $p_key => $p_value) { 
            ?>
            <label class="form-label">
              <?=$p_value[0]?>
              <input name="<?=$p_key?>" type="checkbox"
              <?php if ($p_value[1]) { ?> checked <?php } ?>
              />
            </label>
          <?php } ?>
        </fieldset>
        <div>
          <button type="submit">Обновить</button>
          <button type="reset">Сбросить поля формы</button>
        </div>
      </form>
    </section>
  </body>
</html>
