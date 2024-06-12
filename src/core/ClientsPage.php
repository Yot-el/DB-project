<?php
require_once __DIR__.'/Page.php';
class ClientsPage extends Page {
  public function fetchQueryParam(int|string $q) {
    $table = "client";
    $query = "select * from $table";
    $params = [];
    // ID
    if ($q === (string)(int)$q) {
      $query = $query." where id = :id limit :limit offset :offset";

      $params["id"] = $q;
    } else {
    // Organisation Name
      $orgNameParam = "%$q%";
      $query = $query." where organisation_name ilike :organisation_name limit :limit offset :offset";

      $params["organisation_name"] = $orgNameParam;
    }

    $params["limit"] = $this->limit;
    $params["offset"] = ($this->pageNumber - 1) * $this->limit;

    try {
      $items = $this->db->getItems($query, $params);
      $this->setItems($items);
    } catch (PDOException $exception) {
      echo $exception->getMessage();
    }
  }

  public function fetchTotalPagesNumber(): void {
    $table = "client";
    $query = "select count(*) from $table";
    $params = [];

    try {
      $stmt = $this->db->query($query, $params);
      $count = ($stmt->fetch())["count"];
      $this->totalPagesNumber = round($count / $this->limit);
    } catch (PDOException $exception) {
      echo $exception->getMessage();
    }
  }
}