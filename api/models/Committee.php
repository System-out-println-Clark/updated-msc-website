<?php
/**
 * Committee Model
 * Handles all committee-related database operations
 */

require_once __DIR__ . '/../config/database.php';

class Committee
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM committees ORDER BY committee_id DESC");
        $stmt->execute();
        $committees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($committees as &$committee) {
            $committee['members'] = $this->getMembers($committee['committee_id']);
        }

        return $committees;
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM committees WHERE committee_id = :id");
        $stmt->execute(['id' => $id]);
        $committee = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($committee) {
            $committee['members'] = $this->getMembers($id);
        }

        return $committee;
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO committees (name, description) VALUES (:name, :description)");
        $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description'] ?? ''
        ]);

        $id = $this->db->lastInsertId();

        if (!empty($data['members'])) {
            $this->saveMembers($id, $data['members']);
        }

        return $this->findById($id);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE committees SET name = :name, description = :description WHERE committee_id = :id");
        $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description'] ?? '',
            'id' => $id
        ]);

        if (isset($data['members'])) {
            $this->deleteMembers($id);
            $this->saveMembers($id, $data['members']);
        }

        return $this->findById($id);
    }

    public function delete($id)
    {
        $this->deleteMembers($id);

        $stmt = $this->db->prepare("DELETE FROM committees WHERE committee_id = :id");
        return $stmt->execute(['id' => $id]);
    }

    private function getMembers($committeeId)
    {
        $stmt = $this->db->prepare("
            SELECT cm.id, cm.student_id, cm.position, s.full_name 
            FROM committee_members cm
            JOIN students s ON s.id = cm.student_id
            WHERE cm.committee_id = :committee_id
        ");
        $stmt->execute(['committee_id' => $committeeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function saveMembers($committeeId, $members)
    {
        $stmt = $this->db->prepare("INSERT INTO committee_members (committee_id, student_id, position) VALUES (:committee_id, :student_id, :position)");

        foreach ($members as $m) {
            $stmt->execute([
                'committee_id' => $committeeId,
                'student_id' => $m['student_id'],
                'position' => $m['position'] ?? null
            ]);
        }
    }

    private function deleteMembers($committeeId)
    {
        $stmt = $this->db->prepare("DELETE FROM committee_members WHERE committee_id = :committee_id");
        $stmt->execute(['committee_id' => $committeeId]);
    }
}
