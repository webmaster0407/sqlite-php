<?php 
namespace App;

class SQLiteConnection {
    
    private $pdo;

    public function connect() {
        if ( $this->pdo == null ) {
            try {
                $this->pdo = new \PDO("sqlite:".Config::PATH_TO_SQLITE_FILE);
            } catch (\PDOException $e) {
                echo 'Can not access to database, please confirm the database name.';
            }
        }
        return $this->pdo;
    }
}

?>