<?php

class Rent {
  private Database $db;
  public int | null $id = null;
  public int $office_id;
  public int $client_id;
  public string $start_date;
  public string $end_date;
  public string | null $termination_date = null;
  public int | null $termination_reason_id = null;

  function __construct(array $rentInfo, Database $db) {
    $this->db = $db;

    if (isset($rentInfo["id"])) {
      $this->id = $rentInfo["id"];
    }

    $this->office_id = $rentInfo["office_id"];
    $this->client_id = $rentInfo["client_id"];
    $this->start_date = $rentInfo["start_date"];
    $this->end_date = $rentInfo["end_date"];

    if (isset($rentInfo["termination_date"]) and $rentInfo["termination_date"]) {
      $this->termination_date = $rentInfo["termination_date"];
    }

    if (isset($rentInfo["termination_reason_id"]) and $rentInfo["termination_reason_id"]) {
      $this->termination_reason_id = $rentInfo["termination_reason_id"];
    }
  }

  public function getTerminationReason(): string | null {
    $table = "termination_reason";
    $query = "select name from $table where id = :id";
    $params = ["id" => $this->termination_reason_id];

    try {
      $stmt = $this->db->query($query, $params);
      $type = $stmt->fetch();

      return $type["name"];
    } catch (PDOException $exception) {
      throw $exception;
    }
  }

  public function isActive(): bool {
    return !boolval($this->termination_date);
  }
}