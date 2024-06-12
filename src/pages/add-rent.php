<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . '/core/AddRentPage.php';

  $db = new Database();
  $page = new AddRentPage();
  $page->setDatabase($db);
  $exceptionMessage = "";

  try {
    if (count($_POST)) {
      $arrayObject = new ArrayObject($_POST);
      $postCopy = $arrayObject->getArrayCopy();
      $postCopy["id"] = null;
      $page->setNewRent(new Rent($postCopy, $db));
    }

    $termination_reasons = $db->getItems("select * from termination_reason");
    $clients = $db->getItems("select organisation_name, id from client");
    $offices = $db->getItems("select id, address from office");
    $rent = $page->getNewRent();
  } catch (Exception $exception) {
    $exceptionMessage = $exception->getMessage();
  }

  try {
    if (count($_POST)) {
      $page->addNewRent();
    }
  } catch (Exception $exception) {
    $exceptionMessage = $exception->getMessage();
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
    <link rel="stylesheet" href="../styles/form.css">
    <title>Аренда офисов</title>
  </head>
  <body class="main">
    <header class="header">
      <h1 class="title">Добавить аренду</h1>
      <a class="header__link" href="../index.php">Вернуться на главную</a>
    </header>
    <section class="container">
      <?php if ($exceptionMessage) {?>
        <p class="error">
          <?=$exceptionMessage?>
        </p>
      <?php } ?>
      <form class="form" action="" method="post">
        <label class="form-label">
          Арендатор*
          <select name="client_id">
            <?php foreach ($clients as $client) { ?>
              <option value="<?=$client["id"]?>"
              <?php if ($rent and $client["id"] == $rent->client_id) { ?>
                selected
              <?php } ?>
              >
                <?= $client["organisation_name"] ? $client["organisation_name"] : "Собственник ID " . $client["id"] ?>
              </option>
            <?php } ?>
          </select>
        </label>
        <label class="form-label">
          Офис*
          <select name="office_id">
            <?php foreach ($offices as $office) { ?>
              <option value="<?=$office["id"]?>"
              <?php if ($rent and $office["id"] == $rent->office_id) { ?>
                selected
              <?php } ?>
              >
                <?= "Офис ID " . $office["id"] . "; ". $office["address"] ?>
              </option>
            <?php } ?>
            </select>
        </label>
        <label class="form-label">
          Дата начала аренды*
          <input name="start_date" type="date" required
          <?php if ($rent) { ?>
            value="<?=$rent->start_date?>"
          <?php } ?>
          />
        </label>
        <label class="form-label">
          Дата окончания аренды*
          <input name="end_date" type="date" required
          <?php if ($rent) { ?>
            value="<?=$rent->end_date?>"
          <?php } ?>
          />
        </label>
        <label class="form-label">
          Дата расторжения договора
          <input name="termination_date" type="date" 
          <?php if ($rent) { ?>
            value="<?=$rent->termination_date?>"
          <?php } ?>
          />
        </label>
        <label class="form-label" for="termination_reason">
          Причина расторжения договора
        </label>
        <select name="termination_reason_id" id="termination_reason">
              <option value="" 
                <?php if (($rent and !$rent->termination_reason_id) or !$rent) { ?>
                  selected
                <?php } ?>
              >
                --Выберите причину--
              </option>
            <?php foreach ($termination_reasons as $reason) { ?>
              <option value="<?=$reason["id"]?>"
              <?php if ($rent and $reason["id"] == $rent->termination_reason_id) { ?>
                selected
              <?php } ?>
              >
                <?= $reason["name"] ?>
              </option>
            <?php } ?>
          </select>
        <button type="submit">Добавить аренду</button>
      </form>
    </section>
    <script type="text/javascript" src="../js/add-rent.js"></script>
  </body>
</html>