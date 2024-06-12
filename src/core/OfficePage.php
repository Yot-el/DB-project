<?php
require_once __DIR__.'/Page.php';
require_once __DIR__.'/Office.php';

class OfficePage extends Page {
  private Office $office;

  public function fetchOffice(int $id): Office {
    $table = "office";
    $query = "select * from $table where id = :id";
    $params = ["id" => $id];

    try {
      $stmt = $this->db->query($query, $params);
      $officeInfo = $stmt->fetch();
      $this->office = new Office($officeInfo, $this->db);

      return $this->office;
    } catch (PDOException $exception) {
      throw $exception;
    }
  }

  public function getOffice(): Office {
    return $this->office;
  }

  public function updateOffice($newInfo): Office {
    $table = "office";
    $query = "update $table set";
    $params = [];

    // Характеристики
    foreach (array_keys($this->office->properties) as $property_key) {

      $query = $query . " " . "$property_key = :$property_key, ";

      if (array_key_exists($property_key, $newInfo)) {
        $params["$property_key"] = "true";
        unset($newInfo["$property_key"]);
      } else {
        $params["$property_key"] = "false";
      }
    }

    // Остальные данные
    foreach ($newInfo as $key => $value) {
      if ($value) {
        $query = $query . " " . "$key = :$key,";
        $params["$key"] = $value;
      }
    }

    $query = rtrim($query, ",");
    $query = $query . " " . "where id = :id";
    $params["id"] = $this->office->id;

    try {
      $this->db->query($query, $params);
      $office = $this->fetchOffice($this->office->id);

      return $office;
    } catch (PDOException $exception) {
      throw $exception;
    }
  }
}