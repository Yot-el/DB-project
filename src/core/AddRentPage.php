<?php
require_once __DIR__.'/Page.php';
require_once __DIR__.'/Rent.php';

class AddRentPage extends Page {
  private Rent | null $newRent = null;

  public function getNewRent(): Rent | null {
    return $this->newRent;
  }

  public function setNewRent(Rent $rent): void {
    $this->newRent = $rent;
  }

  private function isRentDatesOK(): bool {
    $query = "select * from rent 
    where office_id = :office_id
    and (((:interval_start, :interval_end) overlaps (start_date, end_date) 
    and termination_date is null)
    or (termination_date is not null
    and (:interval_start, :interval_end) overlaps (start_date, termination_date)))";
    $params = [
    "interval_start" => $this->newRent->start_date,
    "interval_end" => $this->newRent->end_date,
    "office_id" => $this->newRent->office_id
    ];

    try {
      $stmt = $this->db->query($query, $params);
      $rents = $stmt->fetchAll();

      if (count($rents)) {
        return false;
      };

      return true;
    } catch (PDOException $exception) {
      throw $exception;
    }
  }

  private function isOwner(int $id): bool {
    $query = "select owner_id from office where id = :office_id";
    $params = ["office_id" => $this->newRent->office_id];

    try {
      $stmt = $this->db->query($query, $params);
      $owner_id = $stmt->fetch()["owner_id"];


      if ($owner_id == $id) {
        return true;
      }

      return false;
    } catch (PDOException $exception) {
      throw $exception;
    }
  }

  public function addNewRent(): void {
    if (!$this->newRent) {
      throw new Exception("Need to create rent before add");
    }

    if (!$this->isRentDatesOK()) {
      throw new Exception("Даты аренды пересекаются с одной из уже существующих. Пожалуйста, выберите другие даты");
    }

    if ($this->isOwner($this->newRent->client_id)) {
      throw new Exception("Выбранный арендатор является владельцем данного офиса. Пожалуйста, выберите другого арендатора");
    }

    $query = "instert into rent 
    (office_id, client_id, start_date, end_date, termination_date, termination_reason_id) 
    values 
    (:office_id, :client_id, :start_date, :end_date, :termination_date, :termination_reason_id)";

    $params = [
      "office_id" => $this->newRent->office_id,
      "client_id" => $this->newRent->client_id,
      "start_date" => $this->newRent->start_date,
      "end_date" => $this->newRent->end_date,
      "termination_date" => $this->newRent->termination_date,
      "termination_reason_id" => $this->newRent->termination_reason_id
    ];

    try {
      $this->db->query($query, $params);
    }  catch (PDOException $exception) {
      throw $exception;
    }
  }
}