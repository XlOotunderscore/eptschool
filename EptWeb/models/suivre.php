<?php 
    class Suivre{
        public $matricule;
        public $codeCours;
        public $note;
        public $date;

        function __construct(){}

        public static function createManually($matricule, $codeCours, $note, $date)
        {
            //Instanciation utilisant des paramètres pour chaque propriété
            $instance = new self();
            $instance->matricule = $matricule;
            $instance->codeCours = $codeCours;
            $instance->note = $note;
            $instance->date = $date;
            return $instance;
        }

        public static function createUsingDbRow($row)
        {
            //Instanciation utilisant une db row issue d'une reqûete
            $instance = new self();
            $instance->matricule = $row->matricule;
            $instance->codeCours = $row->code_cours;
            $instance->note = $row->note;
            $instance->date = $row->date_ob;
            return $instance;
        }

        public static $keys = [
            'matricule',
            'code_cours',
            'date_ob'
        ];

        public static $fields = [
            'note',
        ];

        public static $fkeys = [
            'matricule',
            'code_cours'
        ];

        public static function getBySubId($matricule, $codeCours, $real = false){
            global $pdo;
            if(!$real)
                $req = $pdo->prepare("SELECT * FROM suivre WHERE matricule = ? AND code_cours = ? ORDER BY date_ob ASC");
            else
                $req = $pdo->prepare("SELECT * FROM suivre WHERE matricule = ? AND code_cours = ? AND note <> -1 ORDER BY date_ob ASC");

            $req->execute([$matricule, $codeCours]);
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

        public static function getByFullId($matricule, $codeCours, $date, $real = false){
            global $pdo;
            if(!$real)
                $req = $pdo->prepare("SELECT * FROM suivre WHERE matricule = ? AND code_cours = ? AND date_ob = ? ORDER BY date_ob ASC");
            else
                $req = $pdo->prepare("SELECT * FROM suivre WHERE matricule = ? AND code_cours = ? AND date_ob = ?  AND note <> -1 ORDER BY date_ob ASC");
            
            $req->execute([$matricule, $codeCours, $date]);
            $item = $req->fetch();
            if($item)
                return self::createUsingDbRow($item);
            else
                return false;
        }



        public static function getByMatricule($matricule, $real = false){
            global $pdo;
            if(!$real)

                $req = $pdo->prepare("SELECT * FROM suivre WHERE matricule = ? ORDER BY date_ob ASC");
            else
                $req = $pdo->prepare("SELECT * FROM suivre WHERE matricule = ? AND note <> -1 ORDER BY date_ob ASC");

            $req->execute([$matricule]);
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

        public static function getByMatriculeDistinct($matricule, $real = false){
            global $pdo;
            if(!$real)
                $req = $pdo->prepare("SELECT DISTINCT code_cours, date_ob FROM suivre WHERE matricule = ? ORDER BY code_cours, date_ob ASC");
            else
                $req = $pdo->prepare("SELECT DISTINCT code_cours, date_ob FROM suivre WHERE matricule = ? AND note <> -1 ORDER BY code_cours, date_ob ASC");
        
            $req->execute([$matricule]);
            $rows = $req->fetchAll();
            if($rows){
                $result = array();
                foreach ($rows as $row) {
                    array_push($result, $row->code_cours);
                }
        
                return $result;
            }
            else
                return array();
        }
        

        public static function getByCodeCours($code, $real = false){
            global $pdo;
            if(!$real)
                $req = $pdo->prepare("SELECT * FROM suivre WHERE code_cours = ? ORDER BY date_ob ASC");
            else
                $req = $pdo->prepare("SELECT * FROM suivre WHERE code_cours = ? AND note <> - 1 ORDER BY date_ob ASC");

            $req->execute([$code]);
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

        public static function getAllRecords($real = false){
            global $pdo;
            if(!$real)
                $req = $pdo->prepare("SELECT * FROM suivre ORDER BY date_ob ASC");
            else
                $req = $pdo->prepare("SELECT * FROM suivre WHERE note <> -1 ORDER BY date_ob ASC");

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

        public static function getRecordsCustomCondition($condition){
            global $pdo;
            $req = $pdo->prepare("SELECT * FROM suivre " . $condition);
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