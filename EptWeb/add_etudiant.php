<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Ajouter un étudiant</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="fonts/boxicons.css" />
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="css/styles-dashboard.css" />

</head>

<?php 
    require_once 'includes/functions.php';
    require_once 'includes/db.php';
    require_once 'models/enseignant.php';
    require_once 'models/etudiant.php';
    require_once 'models/classe.php';
    require_once 'models/suivre.php';



    startSession();
    verify_access('Administrateur');

    //Lorsque formulaire envoyé, vérification des champs
    if(isset($_POST["send"])){
      $matricule = $_POST["matricule"];
      $nom = $_POST["nom"];
      $prenom = $_POST["prenom"];
      $genre = $_POST["genre"];
      $password = $_POST["password"];
      $date = $_POST["date"];
      $classe = $_POST["classe"];


        if(!empty($matricule)) {
            //En prévision d'éventuel test préliminaire...
            if(strlen($matricule) > 10){
                $erreurs["matricule"] = "Le matricule doit contenir au plus 10 caractères";
                fillSessionFlashNewLine('danger', $erreurs["matricule"]);
            }
        }   
        else{
            $erreurs["matricule"] = "Veuillez renseigner le matricule!";
            fillSessionFlashNewLine('danger', $erreurs["matricule"]);
        }

      //Nom, vérification à l'aide de regex
        if(!empty($nom)) {
            $nom_pattern = "#^[a-zéèêâô][-.'a-zéèêâô ]*$#iu";
            if(!preg_match($nom_pattern, $nom)){
                $erreurs["nom"] = "Votre nom ne peut contenir que des caractères alphabétiques ainsi que le point (.), l'apostrophe ('), le tiret (-) et des espaces";
                fillSessionFlashNewLine('danger', $erreurs["nom"]);

            }
        }
      else{
        $erreurs["nom"] = "Veuillez saisir le nom !";
        fillSessionFlashNewLine('danger', $erreurs["nom"]);
      }
   
      //Prénom, vérification à l'aide de regex
      if(!empty($prenom)) {
         $nom_pattern = "#^[a-zéèêâô][-.'a-zéèêâô ]*$#iu";
         if(!preg_match($nom_pattern, $prenom)){
            $erreurs["prenom"] = "Votre prénom ne peut contenir que des caractères alphabétiques ainsi que le point (.), l'apostrophe ('), le tiret (-) et des espaces";
            fillSessionFlashNewLine('danger', $erreurs["prenom"]);
         }
      }
      else{
        $erreurs["prenom"] = "Veuillez saisir le prénom !";
        fillSessionFlashNewLine('danger', $erreurs["prenom"]);
      }
   
      if(!empty($genre)) {
        //....
      }
      else{
         $erreurs["sexe"] = "Veuillez choisir le sexe !";
         fillSessionFlashNewLine('danger', $erreurs["sexe"]);


      }

      if(!empty($date)) {
        //....
      }
      else{
         $erreurs["date"] = "Veuillez saisir la date de naissance !";
         fillSessionFlashNewLine('danger', $erreurs["date"]);


      }

      if(!empty($password)) {
        //....
      }
      else{
         $erreurs["password"] = "Veuillez saisir le mot de passe !";
         fillSessionFlashNewLine('danger', $erreurs["password"]);

      }

      if(!empty($classe)) {
        //....
      }
      else{
         $erreurs["classe"] = "Veuillez choisir la classe !";
         fillSessionFlashNewLine('danger', $erreurs["classe"]);

      }
   

      if(!isset($erreurs)){
        //Recherche dans la BD d'un etudiant ayant le même matricule
        $users = Etudiant::getUsingMatricule($matricule);
        if($users)
        {
            $erreurs['matricule'] = "Ce matricule est déja utilisé par un autre étudiant !";
            fillSessionFlashNewLine('danger', $erreurs['matricule']);
        }

        if(!isset($erreurs)){
          //Préparation à l'ajout dans la BD

          //Chiffrage du mot de passe
          $hashed_pass = password_hash($password, PASSWORD_BCRYPT);

          $valueList = [
            $matricule,
            $nom,
            $prenom,
            $genre,
            $date,
            $hashed_pass,
            $classe
          ];

          //Ajout
          if(addTableRecord('etudiant', array_merge(Etudiant::$keys, Etudiant::$fields, Etudiant::$fkeys), $valueList)){
            //Faire les enregistrements de la table Suivre nécessaires
            
            //1. Chercher un autre étudiant de la classe
            //2. Récupérer ses tests et effectuer les enregistrements

            $condition = "WHERE matricule <> '" . $matricule . "' AND code_cl = '" . $classe . "' LIMIT 1;";
            $autreEtu = Etudiant::getRecordsCustomCondition($condition);
            if(count($autreEtu) > 0){
                $suivres = Suivre::getByMatricule($autreEtu[0]->matricule);
                foreach ($suivres as $suivre) {
                    if($suivre->note != -1){
                        $valueList = [
                            $matricule,
                            $suivre->codeCours,
                            $suivre->date,
                            0

                        ];
                    }
                    else{
                        $valueList = [
                            $matricule,
                            $suivre->codeCours,
                            $suivre->date,
                            $suivre->note

                        ];
                    }

                    addTableRecord('suivre', array_merge(Suivre::$keys, Suivre::$fields), $valueList);
                }
            }

            fillSessionFlash('success', "Ajouté avec succès");
          }
          else{
            $erreurs['sql'] = "Une erreur est survenue, veuillez réessayer";
            fillSessionFlash('danger', $erreurs['sql']);
          }
                      
        }
      }
    }

    $classes = Classe::getAllRecords();

    loadWarnings();
  ?>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="dash_admin.php" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <span>
                                <img src="images/logoinit.png" style="width:50px; height:50px" />
                            </span>
                        </span>
                        <span class="app-brand-text demo menu-text fw-bolder ms-2">WebEPT</span>
                    </a>

                    <a href="javascript:void(0);"
                        class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <!-- Dashboard -->
                    <li class="menu-item">
                        <a href="dash_admin.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div>Tableau de bord</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="enseignants.php" class="menu-link">
                            <i class="menu-icon fas fa-user-tie"></i>
                            <div>Enseignants</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="filieres.php" class="menu-link">
                            <i class="menu-icon fas fa-sheet-plastic"></i>
                            <div>Filières</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="classes.php" class="menu-link">
                            <i class="menu-icon fas fa-school fa-icon"></i>
                            <div>Classes</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="cours.php" class="menu-link">
                            <i class="menu-icon fas fa-book"></i>
                            <div>Cours</div>
                        </a>
                    </li>

                    <li class="menu-item active">
                        <a href="etudiants.php" class="menu-link">
                            <i class="menu-icon fas fa-user-graduate"></i>
                            <div>Etudiants</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="stats.php" class="menu-link">
                            <i class="menu-icon fas fa-chart-pie"></i>
                            <div>Statistiques</div>
                        </a>
                    </li>

                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Compte</span>
                    </li>

                    <li class="menu-item">
                        <a href="settings_admin.php" class="menu-link">
                            <i class="menu-icon fas fa-gear"></i>
                            <div>Paramètres</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="disconnect.php" class="menu-link">
                            <i class="menu-icon fas fa-right-from-bracket"></i>
                            <div>Déconnexion</div>
                        </a>
                    </li>
                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">
                        <a href="etudiants.php">
                            <button type="button" class="btn rounded-pill btn-icon btn-primary">
                                <span class="tf-icons bx bx-left-arrow-alt"></span>
                            </button>
                        </a>
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Étudiants /</span> Ajouter un
                            étudiant</h4>
                        <div class="row">
                            <div class="col-xl">
                                <div class="card mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Informations</h5>
                                        <small class="text-muted float-end">Remplir tous les champs</small>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="post">
                                            <div class="mb-3">
                                                <label class="form-label" for="matricule">Matricule</label>
                                                <input type="text" name="matricule"
                                                    value="<?php if(isset($erreurs)) echo $matricule; ?>"
                                                    class="form-control" id="matricule"
                                                    placeholder="Saisir le matricule" />
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="nom">Nom</label>
                                                <input type="text" value="<?php if(isset($erreurs)) echo $nom; ?>"
                                                    name="nom" class="form-control" id="nom"
                                                    placeholder="Saisir le nom" />
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="prenom">Prénom</label>
                                                <input type="text" value="<?php if(isset($erreurs)) echo $prenom; ?>"
                                                    name="prenom" class="form-control" id="prenom"
                                                    placeholder="Saisir le prénom" />
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Genre</label>
                                                <div class="mb-3 d-flex justify-content-between">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="genre"
                                                            id="genreOption1" value="M"
                                                            <?php if((isset($erreurs) && $genre == 'M') || !isset($erreurs)) echo 'checked'; ?> />
                                                        <label class="form-check-label"
                                                            for="inlineRadio1">Masculin</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="genre"
                                                            id="genreOption2" value="F"
                                                            <?php if(isset($erreurs) && $genre == 'F') echo 'checked'; ?> />

                                                        <label class="form-check-label"
                                                            for="inlineRadio2">Féminin</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label" for="date">Date de naissance</label>
                                                <input class="form-control" name="date" type="date"
                                                    value="<?php if(isset($erreurs)) echo $date; ?>" id="date">
                                            </div>

                                            <div class="mb-3 form-password-toggle">
                                                <label class="form-label" for="password">Mot de passe</label>
                                                <div class="input-group input-group-merge">
                                                    <input type="password" id="password" class="form-control"
                                                        name="password"
                                                        value="<?php if(isset($erreurs)) echo $password; ?>"
                                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                        aria-describedby="password" />
                                                    <span class="input-group-text cursor-pointer"><i
                                                            class="bx bx-hide"></i></span>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label" for="classe">Classe</label>
                                                <select class="form-select" name="classe" id="classe">
                                                    <?php foreach($classes as $cl): ?>
                                                    <option
                                                        <?php if(isset($erreurs) && $classe == $cl->id) echo 'selected' ?>
                                                        value="<?= $cl->id ?>"><?= $cl->lib ?></option>
                                                    <?php endforeach; ?>

                                                </select>
                                            </div>

                                            <input class="btn btn-primary" type="submit" name="send" value="Ajouter" />
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- / Content -->

                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>