<?php
class IdolController extends Controller {
    private $service;

    public function __construct() {
        $db = (new Database())->getConnection();
        $idolModel = new Idol($db);
        $this->service = new IdolService($idolModel);
    }

    // GET /idol
    // Di method index(), ganti jadi:
    public function index() {
    try {
        $data = $this->service->getAll();
        $this->success($data, 'Data berhasil diambil');
        } catch (Exception $e) {
        $this->error('Database error', 500); // Jangan tampilkan detail error
        }
    }

    // GET /idol/{id}
    public function show($id) {
        try {
            if (!is_numeric($id)) {
                $this->error('ID harus berupa angka', 400);
                return;
            }
            
            $data = $this->service->getById($id);
            if ($data) {
                $this->success($data, 'Data idol ditemukan');
            } else {
                $this->error('Data idol tidak ditemukan', 404);
            }
        } catch (Exception $e) {
            $this->error('Gagal mengambil data: ' . $e->getMessage(), 500);
        }
    }

    // POST /idol
    public function create() {
        try {
            $input = $this->getJsonInput();
            if (!$input) {
                $this->error('Data JSON tidak valid', 400);
                return;
            }
            
            $result = $this->service->create($input);
            $this->success($result, 'Idol berhasil ditambahkan', 201);
        } catch (Exception $e) {
            $this->error('Error: ' . $e->getMessage(), 500);
        }
    }

    // PUT /idol/{id}
    public function update($id) {
        try {
            if (!is_numeric($id)) {
                $this->error('ID harus berupa angka', 400);
                return;
            }
            
            $input = $this->getJsonInput();
            if (!$input) {
                $this->error('Data JSON tidak valid', 400);
                return;
            }
            
            // Add ID to data for validation
            $input['id'] = $id;
            
            $this->service->update($id, $input);
            $this->success(null, 'Data idol berhasil diperbarui');
            
        } catch (Exception $e) {
            $this->error('Error: ' . $e->getMessage(), 500);
        }
    }

    // DELETE /idol/{id}
    public function delete($id) {
        try {
            if (!is_numeric($id)) {
                $this->error('ID harus berupa angka', 400);
                return;
            }
            
            $this->service->delete($id);
            $this->success(null, 'Data idol berhasil dihapus');
            
        } catch (Exception $e) {
            $this->error('Error: ' . $e->getMessage(), 500);
        }
    }
}