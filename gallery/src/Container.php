<?php

class Container extends \Emeset\Container {

    private \Models\Db $db;

    public function __construct(array $config)
    {
        parent::__construct($config);
        //echo "Creant connexió a la base de dades amb: " . $this->config["db"]["user"] . "@" . $this->config["db"]["host"] . ":" . $this->config["db"]["port"] . "/" . $this->config["db"]["dbname"] . "\n";
        $this->db = $this->Db();
    }

    public function Images() : \Models\Images
    {
        return new \Models\Images($this->db);
    }

    public function Users() : \Models\Users
    {
        return new \Models\Users($this->db);
    }

    public function Db() : \Models\Db
    {
        return new \Models\Db(
            $this->config["db"]["user"], 
            $this->config["db"]["password"],
            $this->config["db"]["dbname"],
            $this->config["db"]["host"],
            $this->config["db"]["port"]
        );
    }
    
}