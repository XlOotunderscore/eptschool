<?php 
    class classe{
        public $id;
        public $lib;
        public $effectif;
        public $codeFil;

        function __construct(){}

        public static function createManually($id, $lib, $effectif, $codeFil)
        {
            //Instanciation utilisant des paramètres pour chaque propriété
            $instance = new self();
            $instance->id = $id;
            $instance->lib = $lib;
            $instance->effectif = $effectif;
            $instance->codeFil = $codeFil;
            return $instance;
        }

        public static function createUsingDbRow($row)
        {
            //Instanciation utilisant une db row issue d'une reqûete
            $instance = new self();
            $instance->id = $row->code_cl;
            $instance->lib = $row->libelle_cl;
            $instance->effectif = $row->effectif;
            $instance->codeFil = $row->code_fil;
            return $instance;
        }

        public static $keys = [
            'code_cl'
        ];

        public static $fields = [
            'libelle_cl',
            'effectif',
        ];

        public static $fkeys = [
            'code_fil'
        ];

        public static function getAllRecords(){
            global $pdo;
            $req = $pdo->prepare("SELECT * FROM classe ORDER BY code_cl ASC;");
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
            $req = $pdo->prepare("SELECT * FROM classe WHERE code_cl = ?");
            $req->execute([$id]);
            $item = $req->fetch();
            if($item)
                return self::createUsingDbRow($item);
            else
                return false;
        }

        public static function getByLib($lib){
            global $pdo;
            $req = $pdo->prepare("SELECT * FROM classe WHERE libelle_cl = ?");
            $req->execute([$lib]);
            $item = $req->fetch();
            if($item)
                return self::createUsingDbRow($item);
            else
                return false;
        }

        public static function getByCodeFil($id){
            global $pdo;
            $req = $pdo->prepare("SELECT * FROM classe WHERE code_fil = ?");
            $req->execute([$id]);
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

        public static function getRecordsCustomCondition($condition){
            global $pdo;
            $req = $pdo->prepare("SELECT * FROM classe " . $condition);
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