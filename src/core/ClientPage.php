<?php
require_once __DIR__.'/Page.php';
class ClientPage extends Page {
  private Client $client;

  public function fetchClient(int $id): Client {
    $table = "client";
    $query = "select * from $table where id = :id";
    $params = ["id" => $id];
    
    try {
      $stmt = $this->db->query($query, $params);
      $clientInfo = $stmt->fetch();

      if (!$clientInfo) {
        throw new Exception("No client with the specified ID");
      }

      $this->client = new Client($clientInfo);
      return $this->client;
    } catch (PDOException $exception) {
      throw $exception;
    }
  }

  public function updateClient($newInfo): Client {
    if (is_null($this->client)) {
      throw new Exception("Need to fetch client info before update");
    }

    $table = "client";

    $query = "update $table
    set organisation_name = :organisation_name,
    email = :email,
    phone_number = :phone_number,
    contact_person = :contact_person,
    address = :address
    where id = :id";

    $params = [];
    $params["organisation_name"] = $newInfo["organisation_name"] ? $newInfo["organisation_name"] : $this->client->organisationName;
    $params["email"] = $newInfo["email"] ? $newInfo["email"] : $this->client->email;
    $params["phone_number"] = $newInfo["phone_number"] ? $newInfo["phone_number"] : $this->client->phoneNumber;
    $params["contact_person"] = $newInfo["contact_person"] ? $newInfo["contact_person"] : $this->client->contactPerson;
    $params["address"] = $newInfo["address"] ? $newInfo["address"] : $this->client->address;
    $params["id"] = $this->client->id;

    try {
      $client = $this->fetchClient($this->client->id);

      return $client;
    } catch (PDOException $exception) {
      throw $exception;
    }
  }

  public function getClient(): Client {
    return $this->client;
  }
}