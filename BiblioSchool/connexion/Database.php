<?php
class Database {
    private $host = 'localhost';
    private $dbName = 'bibloschool';
    private $user = 'root';
    private $password = '';
    private $pdo;
    private static $instance = null;

    public function getConnection() {
        try {
            if ($this->pdo === null) {
                $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4";
                $this->pdo = new PDO($dsn, $this->user, $this->password);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            return $this->pdo;
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }
    public static function getInstance()
  {
    if (self::$instance == null)
    {
      self::$instance = new Database();
    }
 
    return self::$instance;
  }
}

$db = Database::getInstance();
$connection = $db->getConnection();
?>
