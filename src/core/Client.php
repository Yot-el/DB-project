<?php
class Client {
  public int $id;
  public string | null $organisationName;
  public string $email;
  public string | null $address;
  public string $contactPerson;
  public string | null $phoneNumber;

  function __construct(array $clientInfo) {
    $this->id = $clientInfo["id"];
    $this->organisationName = $clientInfo["organisation_name"];
    $this->email = $clientInfo["email"];
    $this->phoneNumber = $clientInfo["phone_number"];
    $this->contactPerson = $clientInfo["contact_person"];
    $this->address = $clientInfo["address"];
  }
}