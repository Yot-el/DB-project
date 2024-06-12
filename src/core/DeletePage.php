<?php
require_once __DIR__.'/Page.php';

class DeletePage extends Page {
  public string $message = "";
  public function delete(array $info) {
    if (isset($info["client_id"]) and !is_null($info["client_id"])) {
      $table = "client";
      $query = "delete from $table where id = :id";
      $params = ["id" => $info["client_id"]];

      try {
        $this->db->query($query, $params);
      } catch (PDOException $exception) {
        $this->message = "Произошла ошибка при удалении клиента";
        throw $exception;
      }

      $this->message = "Клиент успешно удалён";
    }
  }
}
