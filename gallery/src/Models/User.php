<?php

namespace Models;


class Users {

    private \PDO $db;

    public function __construct(Db $db)
    {
        $this->db = $db->getDb();
    }

    public function getUsers() : array
    {
        $users = [];
        $query = "select * from users order by id desc;";
        foreach ($this->db->query($query, \PDO::FETCH_ASSOC) as $user) {
            $users[] = $user;
        }
        return $users;
    }

    public function getByUser(string $user) : array  | bool
    {
        $query = "select * from users where user = :user;";
        $stm = $this->db->prepare($query);
        $stm->execute([
            ':user' => $user,
        ]);
        $dadesUser = $stm->fetch(\PDO::FETCH_ASSOC);

        return $dadesUser;
    }

}

