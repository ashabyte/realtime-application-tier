<?php
class IdolService {
    private $idol;

    public function __construct(Idol $idol) {
        $this->idol = $idol;
    }

    public function getAll() {
        return $this->idol->getAll();
    }

    public function getById($id) {
        return $this->idol->getById($id);
    }

    public function create($data) {
        // Validate required fields
        $required = ['idol_name', 'birth_date', 'country', 'kpop_group'];
        foreach ($required as $field) {
            if (!isset($data[$field]) || trim($data[$field]) === '') {
                throw new Exception("Field '{$field}' is required");
            }
        }
        
        // Validate date
        if (!$this->isValidDate($data['birth_date'])) {
            throw new Exception("Invalid date format. Use YYYY-MM-DD");
        }
        
        // Set defaults
        $data['position'] = $data['position'] ?? null;
        $data['height_cm'] = isset($data['height_cm']) ? (int)$data['height_cm'] : null;
        $data['debut_year'] = isset($data['debut_year']) ? (int)$data['debut_year'] : null;
        
        $id = $this->idol->create($data);
        return $this->getById($id);
    }

    public function update($id, $data) {
        // Check exists
        $existing = $this->idol->getById($id);
        if (!$existing) {
            throw new Exception("Idol with ID {$id} not found");
        }
        
        // Validate date if provided
        if (isset($data['birth_date']) && !$this->isValidDate($data['birth_date'])) {
            throw new Exception("Invalid date format. Use YYYY-MM-DD");
        }
        
        // Merge with existing
        $updateData = [
            'idol_name' => $data['idol_name'] ?? $existing['idol_name'],
            'birth_date' => $data['birth_date'] ?? $existing['birth_date'],
            'country' => $data['country'] ?? $existing['country'],
            'kpop_group' => $data['kpop_group'] ?? $existing['kpop_group'],
            'position' => $data['position'] ?? $existing['position'],
            'height_cm' => isset($data['height_cm']) ? (int)$data['height_cm'] : $existing['height_cm'],
            'debut_year' => isset($data['debut_year']) ? (int)$data['debut_year'] : $existing['debut_year']
        ];
        
        return $this->idol->update($id, $updateData);
    }

    public function delete($id) {
        // Check exists
        $existing = $this->idol->getById($id);
        if (!$existing) {
            throw new Exception("Idol with ID {$id} not found");
        }
        
        return $this->idol->delete($id);
    }
    
    private function isValidDate($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}