<?php class Connection
{
    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
  
    public function connectDB($dbname)
    {
        try {
            $pdo = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $dbname . '',
                $this->user,
                $this->password,
                [
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
                ]
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            echo 'Error connecting to the database: ' . $e->getMessage();
            exit();
        }
    }
}
