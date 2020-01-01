<?php
namespace Common {
    class AbstractRepository
    {
        private static $instance;
        /**
         * Экземпляр класса PDO
         *
         * @var \PDO
         */
        private $database;
        /**
         * Инициализация объекта одиночки
         *
         * @return __CLASS__
         */
        private static function getInstance()
        {
            if (self::$instance == null) {
                $className = __CLASS__;
                self::$instance = new $className;
            }
            return self::$instance;
        }
        public function __construct()
        {
        }
        /**
         * Получение экземпляра класса PDO
         *
         * @return \PDO
         */
        public function getDatabase()
        {
            try {
                $db = $this->connect();
                return $db->database;
            } catch (Exception $ex) {
                echo "I was unable to open a connection to the database. " . $ex->getMessage();
                return null;
            }
        }// function getDatabase
        /**
         * Инициализация экземпляра класса PDO
         *
         * @return DBconn
         */
        public function connect()
        {
            $db = self::getInstance();
            if (is_null($db->database)) {
                $db->database = new \PDO(DB_STRING, DB_USER, DB_PASS);
            }
            return $db;
        } // function connect
        public function query($query, array $params = [])
        {
            $statement = $this->getDatabase()->prepare($query);
            foreach ($params as $parameter => $value) {
                $statement->bindValue($parameter, $value);
            }
            $statement->execute();
            return $statement;
        }
    }
    class Repository extends AbstractRepository
    {
        /**
         * Запрос к БД на выгрузку набора данных
         *
         * @param string $query Запрос к базе данных
         * @param array $params Параметры запроса
         * @return array Результирующее множество
         */
        public function getMany($query, array $params = [])
        {
            $res = $this->query($query, $params);
            return $res->fetchAll(\PDO::FETCH_ASSOC);
        }  // function getMany
        /**
         * Запрос к БД на выгрузку записи с данными
         *
         * @param string $query Запрос к базе данных
         * @param array $params Параметры запроса
         * @return array Результирующая запись
         */
        public function getOne($query, array $params = [])
        {
            $res = $this->query($query, $params);
            return $res->fetch(\PDO::FETCH_ASSOC);
        } // function getOne
    }
}