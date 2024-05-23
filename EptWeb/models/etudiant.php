<?php 
    class Etudiant{
        public $matricule;
        public $nom;
        public $prenom;
        public $genre;
        public $dateNaiss;
        public $mdp;
        public $codeClasse;

        function __construct(){}

        public static function createManually($matricule, $nom, $prenom, $genre, $dateNaiss, $mdp, $codeClasse)
        {
            //Instanciation utilisant des paramètres pour chaque propriété
            $instance = new self();
            $instance->matricule = $matricule;
            $instance->nom = $nom;
            $instance->prenom = $prenom;
            $instance->genre = $genre;
            $instance->dateNaiss = $dateNaiss;
            $instance->mdp = $mdp;
            $instance->codeClasse = $codeClasse;
            return $instance;
        }

        public static function createUsingDbRow($row)
        {
            //Instanciation utilisant une db row issue d'une reqûete
            $instance = new self();
            $instance->matricule = $row->matricule;
            $instance->nom = $row->nom_etu;
            $instance->prenom = $row->prenom_etu;
            $instance->mdp = $row->mdp_etu;
            $instance->genre = $row->sexe;
            $instance->dateNaiss = $row->date_naiss;
            $instance->codeClasse = $row->code_cl;
            return $instance;
        }

        public static $keys = [
            'matricule'
        ];

        public static $fields = [
            'nom_etu',
            'prenom_etu',
            'sexe',
            'date_naiss',
            'mdp_etu',
        ];

        public static $fkeys = [
            'code_cl'
        ];


        public static function getUsingMatricule($matricule){
            global $pdo;
            $req = $pdo->prepare("SELECT * FROM etudiant WHERE matricule = ?");
            $req->execute([$matricule]);
            $user = $req->fetch();
            if($user)
                return self::createUsingDbRow($user);
            else
                return false;
        }

        public static function getAllRecords(){
            global $pdo;
            $req = $pdo->prepare("SELECT * FROM etudiant");
            $req->execute();
            $rows = $req->fetchAll();
            if($rows){
                $etuds = array();
                foreach ($rows as $etu) {
                    array_push($etuds, self::createUsingDbRow($etu));
                }

                return $etuds;
            }
            else
                return array();
        }

        public static function getByCodeClasse($id){
            global $pdo;
            $req = $pdo->prepare("SELECT * FROM etudiant WHERE code_cl = ? ORDER BY nom_etu, prenom_etu ASC");
            $req->execute([$id]);
            $rows = $req->fetchAll();
            if($rows){
                $etuds = array();
                foreach ($rows as $etu) {
                    array_push($etuds, self::createUsingDbRow($etu));
                }

                return $etuds;
            }
            else
                return array();
        }

        public static function getByCodeFiliere($id){
            global $pdo;
            $req = $pdo->prepare("SELECT * FROM etudiant, classe WHERE etudiant.code_cl = classe.code_cl AND code_fil = ? ORDER BY nom_etu, prenom_etu ASC");
            $req->execute([$id]);
            $rows = $req->fetchAll();
            if($rows){
                $etuds = array();
                foreach ($rows as $etu) {
                    array_push($etuds, self::createUsingDbRow($etu));
                }

                return $etuds;
            }
            else
                return array();
        }

        public static function getRecordsCustomCondition($condition){
            global $pdo;
            $req = $pdo->prepare("SELECT * FROM etudiant " . $condition);
            $req->execute();
            $rows = $req->fetchAll();
            if($rows){
                $etuds = array();
                foreach ($rows as $etu) {
                    array_push($etuds, self::createUsingDbRow($etu));
                }

                return $etuds;
            }
            else
                return array();
        }


    }