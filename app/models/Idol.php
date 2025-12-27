<?php
class Idol extends Model {
    public function __construct($db) {
        parent::__construct($db);
        $this->table = "idols";
    }

    public function getAll() {
        $query = "SELECT * FROM {$this->table} ORDER BY idol_id ASC";
        $stmt = $this->executeQuery($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE idol_id = :id LIMIT 1";
        $stmt = $this->executeQuery($query, [':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $query = "INSERT INTO {$this->table} 
                  (idol_name, birth_date, country, kpop_group, position, height_cm, debut_year) 
                  VALUES (:idol_name, :birth_date, :country, :kpop_group, :position, :height_cm, :debut_year)";

        $params = [
            ':idol_name' => $data['idol_name'] ?? '',
            ':birth_date' => $data['birth_date'] ?? null,
            ':country' => $data['country'] ?? '',
            ':kpop_group' => $data['kpop_group'] ?? '',
            ':position' => $data['position'] ?? null,
            ':height_cm' => isset($data['height_cm']) ? (int)$data['height_cm'] : null,
            ':debut_year' => isset($data['debut_year']) ? (int)$data['debut_year'] : null
        ];

        $stmt = $this->executeQuery($query, $params);
        return $this->conn->lastInsertId();
    }

    public function update($id, $data) {
        $query = "UPDATE {$this->table} 
                  SET idol_name = :idol_name, 
                      birth_date = :birth_date, 
                      country = :country,
                      kpop_group = :kpop_group,
                      position = :position,
                      height_cm = :height_cm,
                      debut_year = :debut_year,
                      updated_at = CURRENT_TIMESTAMP
                  WHERE idol_id = :id";

        $params = [
            ':id' => $id,
            ':idol_name' => $data['idol_name'] ?? '',
            ':birth_date' => $data['birth_date'] ?? null,
            ':country' => $data['country'] ?? '',
            ':kpop_group' => $data['kpop_group'] ?? '',
            ':position' => $data['position'] ?? null,
            ':height_cm' => isset($data['height_cm']) ? (int)$data['height_cm'] : null,
            ':debut_year' => isset($data['debut_year']) ? (int)$data['debut_year'] : null
        ];

        $stmt = $this->executeQuery($query, $params);
        return $stmt->rowCount() > 0;
    }

    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE idol_id = :id";
        $stmt = $this->executeQuery($query, [':id' => $id]);
        return $stmt->rowCount() > 0;
    }
}