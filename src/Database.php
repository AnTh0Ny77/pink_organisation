<?php

namespace src;

use PDO;
use PDOException;

class Database
{

    public string  $db_Name;
    public PDO $Pdo;


    public function __construct($db_name, $db_user = 'root', $db_pass = '', $db_host = 'localhost')
    {

        $this->db_Name = $db_name;
        $this->$db_pass = $db_pass;
        $this->$db_user = $db_user;
        $this->db_host = $db_host;
    }

    public function DbConnect()
    {

        if (!isset($this->Pdo)) {
            try {
                $pdo = new PDO('mysql:dbname=pink;host=localhost', 'root', '',  array(1002 => 'SET NAMES utf8'));
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->Pdo = $pdo;
                return $this->Pdo;
            } catch (PDOException $e) {
                echo $e->getMessage() . "impossible de se connecter à la base de donnée";
            }
        }
    }

    public function get_content($pdo, $lng)
    {
        if ($lng == 'fr') 
        {
            $request = $pdo->query('SELECT  name_field , text_fr as content  FROM text  ORDER BY  name_field ASC ');
            $data = $request->fetchAll(PDO::FETCH_OBJ);
        }
        else 
        {
            $request = $pdo->query('SELECT  name_field , text_en as content  FROM text  ORDER BY  name_field ASC ');
            $data = $request->fetchAll(PDO::FETCH_OBJ);
        }

        
      
        return $data;
    }


    public function get_reference($pdo, $lng)
    {
        if ($lng == 'fr') {
            $request = $pdo->query('SELECT  ref_fr  as content  FROM reference  ORDER BY  ref_fr ASC ');
            $data = $request->fetchAll(PDO::FETCH_OBJ);
        } else {
            $request = $pdo->query('SELECT  ref_en  as content  FROM reference  ORDER BY ref_en ASC ');
            $data = $request->fetchAll(PDO::FETCH_OBJ);
        }
        return $data;
    }
}
