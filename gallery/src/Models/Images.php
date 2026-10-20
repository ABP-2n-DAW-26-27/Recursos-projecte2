<?php

namespace Models;

/**
 * Exemple per a M613.
 * @author: Dani Prados dprados@cendrassos.net
 *
 * Model les imatges.
 *
 **/

/**
 * Imatges
 */
class Images
{
    public \PDO $sql;

    public function __construct(Db $db)
    {
        $this->sql = $db->getDb();
    }

    public function get(): array
    {
        $query = 'select id, url, title from images';
        $result = [];
        foreach ($this->sql->query($query, \PDO::FETCH_ASSOC) as $link) {
            $result[] = $link;
        }
        return $result;
        //    return $this->images;
    }

    public function getById(int $id) : array | bool
    {
        $query = 'select id, url, title from images where id = :id';
        $stm = $this->sql->prepare($query);
        $stm->execute([':id' => $id]);
        
        $image = $stm->fetch(\PDO::FETCH_ASSOC);

        return $image;
    }

    public function add(string $url, string $title, string $description = ''): bool
    {
        $stm = $this->sql->prepare('insert into images (url, title, description) values (:url, :title, :description);');
        $result = false;
        try {
            $result = $stm->execute([
                ':url'   => $url,
                ':title'  => $title,
                ':description' => $description,
            ]);
        } catch (\PDOException $e) {
            // Aquí podem gestionar els errors
        }
        return $result;
    }

    public function update(int $id, string $url, string $title, string $description = ''): bool
    {
        $stm = $this->sql->prepare('update images set url = :url, title = :title, description = :description where id = :id;');
        $result = false;
        try {
            $result = $stm->execute([
                ':id'    => $id,
                ':url'   => $url,
                ':title'  => $title,
                ':description' => $description,
            ]);
        } catch (\PDOException $e) {
            // Aquí podem gestionar els errors
        }
        return $result;
    }
}
