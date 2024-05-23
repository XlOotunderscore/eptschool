<?php 
    class Filiere{
        public $id;
        public $lib;

        function __construct(){}

        public static function createManually($id, $lib)
        {
            //Instanciation utilisant des paramètres pour chaque propriété
            $instance = new self();
            $instance->id = $id;
            $instance->lib = $lib;
            return $instance;
        }

        public static function createUsingDbRow($row)
        {
            //Instanciation utilisant une db row issue d'une reqûete
            $instance = new self();
            $instance->id = $row->code_fil;
            $instance->lib = $row->libelle_fil;
            return $instance;
        }

        public static $keys = [
            'code_fil'
        ];

        public static $fields = [
            'libelle_fil',
        ];


        public static function getAllRecords(){
            global $pdo;
            $req = $pdo->prepare("SELECT * FROM filiere ORDER BY code_fil ASC;");
            $req->execute();
            $rows = $req->fetchAll();
            if($rows){
                $result = array();
                foreach ($rows as $row) {
                    array_push($result, self::createUsingDbRow($row));
                }

                return $result;
            }
            else
                return array();
        }

        public static function getById($id){
            global $pdo;
            $req = $pdo->prepare("SELECT * FROM filiere WHERE code_fil = ?");
            $req->execute([$id]);
            $item = $req->fetch();
            if($item)
                return self::createUsingDbRow($item);
            else
                return false;
        }

        public static function getByLib($lib){
            global $pdo;
            $req = $pdo->prepare("SELECT * FROM filiere WHERE libelle_fil = ?");
            $req->execute([$lib]);
            $item = $req->fetch();
            if($item)
                return self::createUsingDbRow($item);
            else
                return false;
        }

        public static function getRecordsCustomCondition($condition){
            global $pdo;
            $req = $pdo->prepare("SELECT * FROM filiere " . $condition);
            $req->execute();
            $rows = $req->fetchAll();
            if($rows){
                $result = array();
                foreach ($rows as $row) {
                    array_push($result, self::createUsingDbRow($row));
                }

                return $result;
            }
            else
                return array();
        }

    }