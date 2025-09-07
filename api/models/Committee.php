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
        try {
            $stmt = $this->db->prepare("SELECT * FROM committees ORDER BY committee_id DESC");
            $stmt->execute();
            $committees = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($committees as &$committee) {
                // 1. Get members
                $committee['members'] = $this->getMembers($committee['committee_id']);

                // 2. Get head info from head_id column
                if (!empty($committee['head_id'])) {
                    $stmt = $this->db->prepare("
                        SELECT id, first_name, middle_name, last_name, name_suffix
                        FROM students
                        WHERE id = :id
                    ");
                    $stmt->execute(['id' => $committee['head_id']]);
                    $head = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($head) {
                        $committee['head'] = [
                            'id' => $head['id'],
                            'full_name' => trim($head['first_name'].' '.$head['middle_name'].' '.$head['last_name'].' '.$head['name_suffix'])
                        ];
                    } else {
                        $committee['head'] = null;
                    }
                } else {
                    $committee['head'] = null;
                }
            }


            return $committees;
        } catch (PDOException $e) {
            error_log("Committee getAll error: " . $e->getMessage());
            throw new Exception("Failed to fetch committees.");
        }
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
        try {
            // Insert committee with head_id
            $stmt = $this->db->prepare("
                INSERT INTO committees (name, description, head_id) 
                VALUES (:name, :description, :head_id)
            ");
            $stmt->execute([
                'name' => $data['name'],
                'description' => $data['description'] ?? '',
                'head_id' => $data['head_id'] ?? null
            ]);

            $id = $this->db->lastInsertId();

            // Save members if provided (excluding head if already assigned)
            if (!empty($data['members'])) {
                $this->saveMembers($id, $data['members']);
            }

            return $this->findById($id);
        } catch (PDOException $e) {
            error_log("Committee create error: " . $e->getMessage());
            throw new Exception("Failed to create committee. Please check the data.");
        }
    }

    public function update($id, $data)
    {
        try {
            // Update committee info including head_id
            $stmt = $this->db->prepare("
                UPDATE committees 
                SET name = :name, description = :description, head_id = :head_id 
                WHERE committee_id = :id
            ");
            $stmt->execute([
                'name' => $data['name'],
                'description' => $data['description'] ?? '',
                'head_id' => $data['head_id'] ?? null,
                'id' => $id
            ]);

            // Update members if provided
            if (isset($data['members'])) {
                $this->deleteMembers($id);
                $this->saveMembers($id, $data['members']);
            }

            return $this->findById($id);
        } catch (PDOException $e) {
            error_log("Committee update error: " . $e->getMessage());
            throw new Exception("Failed to update committee. Please check the data.");
        }
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
            SELECT cm.id AS student_id, cm.position, 
                TRIM(CONCAT(s.first_name, ' ', s.middle_name, ' ', s.last_name, ' ', COALESCE(s.name_suffix, ''))) AS full_name
            FROM committee_members cm
            JOIN students s ON s.id = cm.id
            WHERE cm.committee_id = :committee_id
        ");
        $stmt->execute(['committee_id' => $committeeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function saveMembers($committeeId, $members)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO committee_members (id, committee_id, position, start_date) 
            VALUES (:student_id, :committee_id, :position, NOW())"
        );

        foreach ($members as $m) {
            try {
                if (empty($m['student_id'])) continue; // skip invalid entries
                $stmt->execute([
                    'student_id' => $m['student_id'],
                    'committee_id' => $committeeId,
                    'position' => $m['position'] ?? null
                ]);
            } catch (PDOException $e) {
                error_log("Failed to add member (student_id={$m['student_id']}): " . $e->getMessage());
            }
        }
    }

    private function deleteMembers($committeeId)
    {
        $stmt = $this->db->prepare("DELETE FROM committee_members WHERE committee_id = :committee_id");
        $stmt->execute(['committee_id' => $committeeId]);
    }
}
