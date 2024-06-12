<?php
require_once __DIR__.'/Database.php';
require_once __DIR__.'/Client.php';

class Page {
  protected int $limit;
  protected int $pageNumber;
  protected int $totalPagesNumber = 0;
  protected array $items;
  protected Database|null $db;

  function __construct(int $pageNumber = 1, int $limit = 10, array $items = [], Database $db = null) 
  {
    $this->limit = $limit;
    $this->pageNumber = $pageNumber;
    $this->items = $items;
    $this->db = $db;
  }

  public function getDatabase(): Database {
    return $this->db;
  }

  public function setDatabase(Database $db): void {
    $this->db = $db;
  }

  public function getLimit(): int {
    return $this->limit;
  }

  public function setLimit(int $limit): void {
    $this->limit = $limit;
  }

  public function getTotalPagesNumber(): int {
    return $this->totalPagesNumber;
  }

  public function getPage(): int {
    return $this->pageNumber;
  }

  public function setPage(int $pageNumber): void {
    $this->pageNumber = $pageNumber;
  }

  public function getItems(): array {
    return $this->items;
  }

  public function setItems(array $items): void {
    $this->items = $items;
  }

  public function fetchTotalPagesNumber(): void {
  }

  public function fetchItemsFromDB(string $table): void {
    $query = "select * from $table limit :limit offset :offset";
    $params = [];

    $params["limit"] = $this->limit;
    $params["offset"] = ($this->pageNumber - 1) * $this->limit;

    try {
      $items = $this->db->getItems($query, $params);
      $this->setItems($items);
    } catch (PDOException $exception) {
      echo $exception->getMessage();
    }
  }
}