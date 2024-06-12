<?php
require_once __DIR__.'/Page.php';

class Database {
  private string $user = 'postgres';
  private string $pass = 'postgres';
  private string $host = 'pgsql';
  private int $port = 5432;
  private string $dbname = 'rent_db';
  private PDO $pdo;

  function __construct() {
    $dsn = "pgsql:host=$this->host;port=$this->port;dbname=$this->dbname";

    try {
      $this->pdo = new PDO($dsn, $this->user, $this->pass);
    } catch (PDOException $exception) {
      throw $exception;
    }
  }

  public function getPDO(): PDO {
    return $this->pdo;
  }

  public function query(string $sql, array $params = []): PDOStatement {
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    return $stmt;
  }

  public function getItems(string $sql, array $params = []): array | PDOException {
    $stmt = $this->query($sql, $params);

    try {
      $items = $stmt->fetchAll();
      return $items;
    } catch (PDOException $exception) {
      return $exception;
    }
  }

  public function getOrganisationName(int $id): string | null {
    $query = "select organisation_name from client where id = :id";
    $params = ["id" => $id];

    try {
      $stmt = $this->query($query, $params);
      $org_name = $stmt->fetch()["organisation_name"];

      return $org_name;
    } catch (PDOException $exception) {
      throw $exception;
    }
  }
}


