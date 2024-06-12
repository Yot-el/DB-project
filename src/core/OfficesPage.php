<?php
require_once __DIR__.'/Page.php';

class OfficesPage extends Page {
  private int | null $owner_id;

  public function setOwnerId(int $id): void {
    $this->owner_id = $id;
  }

  public function getOwnerId(): int {
    return $this->owner_id;
  }

  public function getOwnerOrganisationName(): string | null {
    try {
      $name = $this->db->getOrganisationName($this->getOwnerId());
      return $name;
    } catch (PDOException $exception) {
      throw $exception;
    }
  }

  private function buildQueryWithParams(string $queryStart, array $filters = []): array {
    $query = $queryStart;
    $params = [];

    if (isset($filters["total_surface_limit"])) {
      $query = $query . " " . "and total_surface <= :total_surface_limit";
      $params["total_surface_limit"] = $filters["total_surface_limit"];
    }

    if (isset($filters["monthly_rent"])) {
      $query = $query . " " . "order by monthly_rent";

      if ($filters["monthly_rent"] === "expensive") {
        $query = $query . " " . "desc";
      }

      if ($filters["monthly_rent"] === "cheap") {
        $query = $query . " " . "asc";
      }
    }

    $params["limit"] = $this->limit;
    $params["offset"] = ($this->pageNumber - 1) * $this->limit;

    $query = $query . " " . "limit :limit offset :offset";

    return ["query" => $query, "params" => $params];
  }

  public function fetchOffices(array $filters = []): void {
    if (!isset($this->owner_id) or is_null($this->owner_id)) {
      throw new Exception("Need owner id to fetch offices");
    }
  
    $table = "office";
    $query = "select id, total_surface, monthly_rent from $table where owner_id = :owner_id";

    $a = $this->buildQueryWithParams($query, $filters);
    $params = $a["params"];
    $params["owner_id"] = $this->owner_id;
    $query = $a["query"];

    try {
      $items = $this->db->getItems($query, $params);
      $this->setItems($items);
    } catch (PDOException $exception) {
      throw $exception;
    }
  }

  public function fetchTotalPagesNumber(array $filters = []): void {
    $filtersArrayObject = new ArrayObject($filters);
    $filtersCopy = $filtersArrayObject->getArrayCopy();


    if (!isset($this->owner_id) or is_null($this->owner_id)) {
      throw new Exception("Need owner id to fetch offices");
    }

    unset($filtersCopy["monthly_rent"]);

    $table = "office";
    $query = "select count(*) from $table where owner_id = :owner_id";

    $a = $this->buildQueryWithParams($query, $filtersCopy);
    $params = $a["params"];
    $params["owner_id"] = $this->owner_id;
    $query = $a["query"];

    try {
      $stmt = $this->db->query($query, $params);
      $count = ($stmt->fetch())["count"];

      $this->totalPagesNumber = $count < $this->limit ? 1 : $count / $this->limit;
    } catch (PDOException $exception) {
      throw $exception;
    }
  }
}