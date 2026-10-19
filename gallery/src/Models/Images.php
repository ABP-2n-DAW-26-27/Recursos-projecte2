<?php

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
    //    private array $images = [
    //         ["title" => "Via lactea ", "url" => "via-lactea.jpg"],
    //         ["title" => "Figueres", "url" => "figueres.jpg"],
    //         ["title" => "Calella", "url" => "calella.jpg"],
    //         ["title" => "Nova York", "url" => "ny.jpg"],
    //         ["title" => "Fraser Island", "url" => "platja.jpg"],
    //         ["title" => "Lake Moraine", "url" => "llac.jpg"],
    //     ];

    public PDO $sql;

    public function __construct($dsn, $user, $password)
    {
        try {
            $this->sql = new PDO($dsn, $user, $password);
        } catch (PDOException $e) {
            print_r($e);
            die();
        }
    }

    public function get(): array
    {
        $query = 'select id, url, title from images';
        $result = [];
        foreach ($this->sql->query($query, PDO::FETCH_ASSOC) as $link) {
            $result[] = $link;
        }
        return $result;
        //    return $this->images;
    }

    public function getById($id) : array | bool
    {
        $query = 'select id, url, title from images where id = :id';
        $stm = $this->sql->prepare($query);
        $stm->execute([':id' => $id]);
        
        $image = $stm->fetch(PDO::FETCH_ASSOC);

        return $image;
    }
}
