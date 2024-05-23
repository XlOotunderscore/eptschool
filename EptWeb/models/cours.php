<?php 
    class Cours{
        public $id;
        public $titre;
        public $coef;
        public $codeEns;

        function __construct(){}

        public static function createManually($id, $titre, $coef, $codeEns)
        {
            //Instanciation utilisant des paramètres pour chaque propriété
            $instance = new self();
            $instance->id = $id;
            $instance->titre = $titre;
            $instance->coef = $coef;
            $instance->codeEns = $codeEns;
            return $instance;
        }

        public static function createUsingDbRow($row)
        {
            //Instanciation utilisant une db row issue d'une reqûete
            $instance = new self();
            $instance->id = $row->code_cours;
            $instance->titre = $row->intitule;
            $instance->coef = $row->coefficient;
            $instance->codeEns = $row->code_ens;
            return $instance;
        }

        public static $keys = [
            'code_cours'
        ];

        public static $fields = [
            'intitule',
            'coefficient',
        ];

        public static $fkeys = [
            'code_ens'
        ];

        public static function getAllRecords(){
            global $pdo;
            $req = $pdo->prepare("SELECT * FROM cours ORDER BY code_cours ASC;");
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
            $req = $pdo->prepare("SELECT * FROM cours WHERE code_cours = ?");
            $req->execute([$id]);
            $item = $req->fetch();
            if($item)
                return self::createUsingDbRow($item);
            else
                return false;
        }

        public static function getByLib($lib){
            global $pdo;
            $req = $pdo->prepare("SELECT * FROM cours WHERE intitule = ?");
            $req->execute([$lib]);
            $item = $req->fetch();
            if($item)
                return self::createUsingDbRow($item);
            else
                return false;
        }

        public static function getByCodeEns($id){
            global $pdo;
            $req = $pdo->prepare("SELECT * FROM cours WHERE code_ens = ?");
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

        public function getClasse(){
            global $pdo;
            $req = $pdo->prepare("SELECT code_cl FROM suivre, etudiant WHERE suivre.matricule = etudiant.matricule AND code_cours = ? LIMIT 1");
            $req->execute([$this->id]);
            $item = $req->fetch();
            if($item)
                return $item->code_cl;
            else
                return false;
        }    

        public static function getRecordsCustomCondition($condition){
            global $pdo;
            $req = $pdo->prepare("SELECT * FROM cours " . $condition);
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