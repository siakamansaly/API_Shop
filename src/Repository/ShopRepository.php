<?php

namespace WshopApi\Repository;

use WshopApi\Database;

/**
 * Class Shop Repository
 * @package WshopApi\Repository
 */
class shopRepository
{
    /**
     * @var \PDO
     */
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Get all shop
     * @return array
     */
    public function getAll(?string $order='id ASC', ?int $limit = 50, ?string $name="", ?string $description=""): array
    {
        $query = $this->db->getPdo()->prepare("SELECT * FROM shop WHERE name LIKE CONCAT('%', :name, '%') AND description like CONCAT('%', :description, '%') ORDER BY $order LIMIT :limit "); 
        $query->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $query->bindParam(':name', $name, \PDO::PARAM_STR);
        $query->bindParam(':description', $description, \PDO::PARAM_STR);

        $query->execute();    
        
        if ($query->rowCount() > 0) {
            return $query->fetchAll();
        }
        return ["error" => "No shop found"];
    }

    /**
     * Get shop by id
     * @param int $id
     * @return array
     */
    public function getById(int $id): array
    {
        $query = $this->db->getPdo()->prepare("SELECT * FROM shop WHERE id = :id");
        $query->bindParam(':id', $id, \PDO::PARAM_INT);
        $query->execute();
        if ($query->rowCount() > 0) {
            return $query->fetch();
        }
        return ["error" => "No shop found"];
    }

    /**
     * Create shop
     * @param array $params
     * @return array|bool
     */
    public function create(array $params): array
    {
        if ($params['name'] == null) {
            return false;
        }

        $query = $this->db->getPdo()->prepare("INSERT INTO shop (name, description) VALUES (:name, :description)");
        $query->bindParam(':name', $params['name'], \PDO::PARAM_STR);
        $query->bindParam(':description', $params['description'], \PDO::PARAM_STR);
        $query->execute();

        $id = $this->db->getPdo()->lastInsertId();
        $shop = $this->getById($id);
        return $shop;
    }

    /**
     * Update shop
     * @param int $id
     * @param array $params
     * @return array | bool
     */
    public function update(int $id, array $params): array
    {
        if ($params['name'] == null) {
            return false;
        }

        $query = $this->db->getPdo()->prepare("UPDATE shop SET name = :name, description = :description WHERE id = :id");
        $query->bindParam(':id', $id, \PDO::PARAM_INT);
        $query->bindParam(':name', $params['name'], \PDO::PARAM_STR);
        $query->bindParam(':description', $params['description'], \PDO::PARAM_STR);
        $query->execute();

        return $this->getById($id);
    }

    /**
     * Delete shop
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $query = $this->db->getPdo()->prepare("DELETE FROM shop WHERE id = :id");
        $query->bindParam(':id', $id, \PDO::PARAM_INT);
        return $query->execute();
    }
}
