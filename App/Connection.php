<?php
    namespace App;


    use PDO;
    use PDOException; 

    class Connection {

        public static function getDb() {
           
            try {
                $conn = new \PDO( //A barra invertida indica a classe 'PDO' está no namespace raiz e não no namespace 'App'
                    "mysql:host=localhost;dbname=hamburgueria;charset=utf8",
                    "root",
                    ""
                );

                return $conn;
            } catch(\PDOException $e) {
                echo $e;
            }


        }

    }
?>