<?php

class Office {
  private Database $db;
  public int $id;
  public int $owner_id;
  public int | null $building_type_id;
  public int | null $building_condition_id;
  public int | null $floor;
  public string $address;
  public int $total_surface;
  public int $monthly_rent;

  public array $properties;

  function __construct(array $officeInfo, Database $db) {
    $this->db = $db;
    $this->id = $officeInfo["id"];
    $this->owner_id = $officeInfo["owner_id"];
    $this->building_type_id = $officeInfo["building_type_id"]; 
    $this->building_condition_id = $officeInfo["building_condition_id"];
    $this->floor = $officeInfo["floor"];
    $this->address = $officeInfo["address"];
    $this->total_surface = $officeInfo["total_surface"];
    $this->monthly_rent = $officeInfo["monthly_rent"];

    $this->properties["has_parking_lot"] = ["Парковочные места", $officeInfo["has_parking_lot"]];
    $this->properties["has_air_conditioning"] = ["Кондиционер", $officeInfo["has_air_conditioning"]];
    $this->properties["has_coffee_machine"] = ["Кофемашина", $officeInfo["has_coffee_machine"]];
    $this->properties["has_wc"] = ["Собственный санузел", $officeInfo["has_wc"]];
    $this->properties["has_security"] = ["Охрана", $officeInfo["has_security"]];
  }

  public function getBuildingType(): string | null {
    $table = "building_type";
    $query = "select * from $table where id = :id";
    $params = ["id" => $this->building_type_id];

    try {
      $stmt = $this->db->query($query, $params);
      $type = $stmt->fetch();

      return $type["name"];
    } catch (PDOException $exception) {
      throw $exception;
    }
  }

  public function getBuildingCondition(): string | null {
    $table = "building_condition";
    $query = "select * from $table where id = :id";
    $params = ["id" => $this->building_condition_id];

    try {
      $stmt = $this->db->query($query, $params);
      $type = $stmt->fetch();

      return $type["name"];
    } catch (PDOException $exception) {
      throw $exception;
    }
  }

  public function getOwnerOrganisationName(): string | null {
    try {
      $name = $this->db->getOrganisationName( $this->owner_id);
      return $name;
    } catch (PDOException $exception) {
      throw $exception;
    }
  }

  public function getCurrentRent() {
    $query = "select 
    r.client_id,
    r.start_date,
    r.end_date,
    r.termination_date
    from office o
    inner join rent r
    on o.id = r.office_id
    where r.start_date in 
      (select max(r.start_date) from rent r group by r.office_id) and o.id = :id;
    ";

    $params = ["id" => $this->id];

    try {
      $stmt = $this->db->query($query, $params);
      $res = $stmt->fetch();

      if (!$res) {
        return null;
      }

      if (is_null($res["termination_date"])) {
        return $res;
      } else {
        return null;
      }
    } catch (PDOException $exception) {
      throw $exception;
    }
  }
}