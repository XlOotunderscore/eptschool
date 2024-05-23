<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Ajouter un enseignant</title>

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

    startSession();
    verify_access('Administrateur');

    //Lorsque formulaire envoyé, vérification des champs
    if(isset($_POST["send"])){
      $code = $_POST["code"];
      $nom = $_POST["nom"];
      $prenom = $_POST["prenom"];
      $contact = $_POST["contact"];
      $password = $_POST["password"];

        if(!empty($code)) {
            //En prévision d'éventuel test préliminaire...
            if(strlen($code) > 7){
                $erreurs["code"] = "Le code doit contenir au plus 7 caractères";
                fillSessionFlashNewLine('danger', $erreurs["code"]);
            }
        }   
        else{
            $erreurs["code"] = "Veuillez renseigner le code de la filière!";
            fillSessionFlashNewLine('danger', $erreurs["code"]);
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
        $erreurs["nom"] = "Veuillez saisir votre nom !";
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
        $erreurs["prenom"] = "Veuillez saisir votre prénom !";
        fillSessionFlashNewLine('danger', $erreurs["prenom"]);
      }
   
      if(!empty($contact)) {
        //....
      }
      else{
         $erreurs["contact"] = "Veuillez saisir votre contact !";
         fillSessionFlashNewLine('danger', $erreurs["contact"]);


      }
   

      if(!isset($erreurs)){
        //Recherche dans la BD d'un enseignant ayant le même code
        $users = Enseignant::getUsingId($code);
        if($users)
        {
            $erreurs['code'] = "Ce code est déja utilisé par un autre enseignant !";
            fillSessionFlashNewLine('danger', $erreurs['code']);
        }

        if(!isset($erreurs)){
          //Préparation à l'ajout dans la BD

          //Chiffrage du mot de passe
          $hashed_pass = password_hash($password, PASSWORD_BCRYPT);

          $valueList = [
            $code,
            $nom,
            $prenom,
            $contact,
            $hashed_pass,
            0
          ];

          //Ajout
          if(addTableRecord('enseignant', array_merge(Enseignant::$keys, Enseignant::$fields), $valueList)){
            fillSessionFlash('success', "Ajouté avec succès");
          }
          else{
            $erreurs['sql'] = "Une erreur est survenue, veuillez réessayer";
            fillSessionFlash('danger', $erreurs['sql']);
          }
                      
        }
      }
    }

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

                    <li class="menu-item active">
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

                    <li class="menu-item">
                        <a href="etudiants.php" class="menu-link">
                            <i class="menu-icon fas fa-user-graduate"></i>
                            <div>Etudiants</div>
                        </a>
                    </li>            <li class="menu-item">
              <a
                href="stats.php"
                class="menu-link"
              >
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
                        <a href="enseignants.php">
                            <button type="button" class="btn rounded-pill btn-icon btn-primary">
                                <span class="tf-icons bx bx-left-arrow-alt"></span>
                            </button>
                        </a>
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Enseignants /</span> Ajouter un
                            enseignant</h4>
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
                                                <label class="form-label" for="code">Code</label>
                                                <input type="text" value="<?php if(isset($erreurs)) echo $code; ?>" name="code" class="form-control" id="code"
                                                    placeholder="Saisir le code" />
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="nom">Nom</label>
                                                <input type="text" value="<?php if(isset($erreurs)) echo $nom; ?>" name="nom" class="form-control" id="nom"
                                                    placeholder="Saisir le nom" />
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="basic-default-prenom">Prénom</label>
                                                <input type="text" value="<?php if(isset($erreurs)) echo $prenom; ?>" name="prenom" class="form-control" id="prenom"
                                                    placeholder="Saisir le prénom" />
                                            </div>
                                            <div class="mb-3">
                                                <label for="contact" class="form-label">Contact</label>
                                                <input type="text" value="<?php if(isset($erreurs)) echo $contact; ?>" class="form-control" id="contact" name="contact"
                                                    placeholder="Entrez votre contact" />
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