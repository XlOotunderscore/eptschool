<?php 

    class Enseignant{
        public $id;
        public $nom;
        public $prenom;
        public $contact;
        public $mdp;
        public $estAdmin;

        function __construct(){}

        public static function createManually($id, $nom, $prenom, $contact, $mdp, $estAdmin)
        {
            //Instanciation utilisant des paramètres pour chaque propriété
            $instance = new self();
            $instance->id = $id;
            $instance->nom = $nom;
            $instance->prenom = $prenom;
            $instance->contact = $contact;
            $instance->mdp = $mdp;
            $instance->estAdmin = $estAdmin;
            return $instance;
        }

        public static function createUsingDbRow($row)
        {
            //Instanciation utilisant une db row issue d'une reqûete
            $instance = new self();
            $instance->id = $row->code_ens;
            $instance->nom = $row->nom_ens;
            $instance->prenom = $row->prenom_ens;
            $instance->contact = $row->contact;
            $instance->mdp = $row->mdp_ens;
            $instance->estAdmin = $row->est_admin;
            return $instance;
        }

        public static $keys = [
            'code_ens'
        ];

        public static $fields = [
            'nom_ens',
            'prenom_ens',
            'contact',
            'mdp_ens',
            'est_admin',
        ];

        public static function getUsingId($matricule){
            global $pdo;
            $req = $pdo->prepare("SELECT * FROM enseignant WHERE code_ens = ?");
            $req->execute([$matricule]);
            $user = $req->fetch();
            if($user)
                return self::createUsingDbRow($user);
            else
                return false;
        }

        public static function getAllSpecificRole($role = 0){
            global $pdo;
            $req = $pdo->prepare("SELECT * FROM enseignant WHERE est_admin = ?");
            $req->execute([$role]);
            $rows = $req->fetchAll();
            if($rows){
                $admins = array();
                foreach ($rows as $admin) {
                    array_push($admins, self::createUsingDbRow($admin));
                }
                return $admins;
            }
            else
                return array();
        }

        public static function getAllRecords(){
            global $pdo;
            $req = $pdo->prepare("SELECT * FROM enseignant");
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
            $req = $pdo->prepare("SELECT * FROM enseignant " . $condition);
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