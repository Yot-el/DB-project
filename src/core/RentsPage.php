<?php
require_once __DIR__.'/Page.php';
require_once __DIR__.'/Rent.php';

class RentsPage extends Page {
  private int | null $client_id = null;
  private int | null $office_id = null;

  public function setClientId(int $id): void {
    $this->client_id = $id;
  }

  public function getClientId(): int | null {
    return $this->client_id;
  }

  public function setOfficeId(int $id): void {
    $this->office_id = $id;
  }

  public function getOfficeId(): int | null {
    return $this->office_id;
  }

  private function buildQueryWithParams(string $queryStart, array $filters = []): array {
    $query = $queryStart;
    $params = [];

    if (!is_null($this->client_id)) {
      $params["client_id"] = $this->client_id;
    }

    if (!is_null($this->office_id)) {
      $params["office_id"] = $this->office_id;
    }

    if (isset($filters["rent_type"])) {
      if ($filters["rent_type"] == "active") {
        $query = $query . " " . "and termination_date is null";
      } else if ($filters["rent_type"] == "inactive") {
        $query = $query . " " . "and termination_date is not null";
      }
    }

    $params["limit"] = $this->limit;
    $params["offset"] = ($this->pageNumber - 1) * $this->limit;

    $query = $query . " " . "limit :limit offset :offset";

    return ["query" => $query, "params" => $params];
  }

  public function fetchRents(array $filters = []): void {
    if (is_null($this->client_id) and is_null($this->office_id)) {
      throw new Exception("Need client or office id to fetch rents");
    }

    $table = "rent";
    $query = "select * from $table where";

    if (!is_null($this->client_id)) {
      $query = $query . " " . "client_id = :client_id";
    }

    if (!is_null($this->office_id)) {
      $query = $query . " " . "office_id = :office_id";
    }

    $a = $this->buildQueryWithParams($query, $filters);
    $params = $a["params"];
    $query = $a["query"];

    try {
      $items = $this->db->getItems($query, $params);

      $rents = array_map(fn($item) => new Rent($item, $this->db), $items);

      $this->setItems($rents);
    } catch (PDOException $exception) {
      throw $exception;
    }
  }

  public function fetchTotalPagesNumber(array $filters = []): void {
    if (is_null($this->client_id) and is_null($this->office_id)) {
      throw new Exception("Need client or office id to fetch rents");
    }

    $table = "rent";
    $query = "select count(*) from $table where";

    if (!is_null($this->client_id)) {
      $query = $query . " " . "client_id = :client_id";
    }

    if (!is_null($this->office_id)) {
      $query = $query . " " . "office_id = :office_id";
    }

    $a = $this->buildQueryWithParams($query, $filters);
    $params = $a["params"];
    $query = $a["query"];


    try {
      $stmt = $this->db->query($query, $params);
      $count = ($stmt->fetch())["count"];

      $this->totalPagesNumber = $count < $this->limit ? 1 : $count / $this->limit;
    } catch (PDOException $exception) {
      throw $exception;
    }
  }

  public function updateRent(array $newInfo): void {
    $table = "rent";
    $query = "update $table set";
    $params = [];

    foreach ($newInfo as $key => $value) {
      if ($value and $key != "id") {
        $query = $query . " " . "$key = :$key,";
        $params["$key"] = $value;
      }
    }

    $query = rtrim($query, ",");
    $query = $query . " " . "where id = :id";
    $params["id"] = $newInfo["id"];

    try {
      $this->db->query($query, $params);
    } catch (PDOException $exception) {
      throw $exception;
    }
  }
}