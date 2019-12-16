<?php
/**
 * Class DB наследуем PDO
 * указание свойств подключения к БД
 * $host хост
 * $dbname название БД
 * $user логин
 * $pass пароль
 * В конструкторе try/catch подключение или ошибка
 */
class DB extends PDO {
    protected $host = 'localhost';
    protected $dbname = 'itemdb';
    protected $user = 'root';
    protected $pass = '';
    public function __construct() {
        try {
            parent::__construct("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->pass);
        } catch (PDOException $e) {
            throw new Error_Exception("Ошибка: " . $e->getMessage());
        }
    }
}
$DBH = new DB();
?>