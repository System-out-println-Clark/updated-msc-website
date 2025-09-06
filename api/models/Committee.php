<?php
/**
 * Committee Model
 * Handle all database operations for committees
 */

require_once __DIR__ . '/../config/database.php';

class Committee
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get all committees
     */
    public function getAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM committees ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find committee by ID
     */
    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM committees WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create new committee
     */
    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO committees (name, description) VALUES (?, ?)");
        $stmt->execute([
            $data['name'],
            $data['description'] ?? null
        ]);
        return $this->findById($this->db->lastInsertId());
    }

    /**
     * Update existing committee
     */
    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE committees SET name = ?, description = ? WHERE id = ?");
        return $stmt->execute([
            $data['name'],
            $data['description'] ?? null,
            $id
        ]);
    }

    /**
     * Delete committee
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM committees WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
