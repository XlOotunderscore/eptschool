<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Mettre à jour une filière</title>

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
        require_once 'includes/db.php';
        require_once 'includes/functions.php';
        require_once 'models/etudiant.php';
        require_once 'models/enseignant.php';
        require_once 'models/filiere.php';

        startSession();
        verify_access('Administrateur');


        if(isset($_POST["id"])){

            $code = $_POST["id"];

            if(!isset($_POST["send"])){
                //Premier chargement, données envoyées depuis la page liste
                $cat = Filiere::getById($code);
                $lib = $cat->lib;

            }
            else{
                //Lorsque formulaire envoyé, vérification des champs
                $lib = $_POST["lib"];

                if(!empty($lib)) {
                    //En prévision d'éventuel test préliminaire...
                    if(strlen($lib) > 30){
                        $erreurs["lib"] = "Le libellé doit contenir au plus 30 caractères";
                        fillSessionFlashNewLine('danger', $erreurs["lib"]);
                    }
                }
                else{
                    $erreurs["lib"] = "Veuillez saisir le libellé de la filière!";
                    fillSessionFlashNewLine('danger', $erreurs["lib"]);
                }

                if(!isset($erreurs)){
                    //Recherche dans la BD d'une filière de même libellé
                    $item = Filiere::getByLib($lib);
                    if($item)
                    {
                        if($item->id == $code){
                            if ($item->lib == $lib) {
                                //Aucune modif
                                $erreurs['lib'] = "Vous n'avez effectué aucune modification.";
                                fillSessionFlashNewLine('primary', $erreurs['lib']);
                            }
                        }
                        else{
                            $erreurs['lib'] = "Ce libellé est déja utilisé par une autre filière !";
                            fillSessionFlashNewLine('danger', $erreurs['lib']);
                        }
                    }

                    if(!isset($erreurs)){
                        //Préparation à la modification

                        $valueList = [
                            $lib
                        ];

                        $keyValues = [
                            $code
                        ];

                        if(updateTableRecord('filiere', Filiere::$fields, $valueList, Filiere::$keys, $keyValues)){
                            fillSessionFlash('success', "Mise à jour effectuée");
                            header("Location: filieres.php");
                            exit();
                        }
                        else{
                            $erreurs['sql'] = "Une erreur est survenue, veuillez réessayer";
                            fillSessionFlash('danger', $erreurs['sql']);
                        }
                                
                    }
                }                
            }
        }
        else{
            //Actualsisation manuelle ou accès anormal à la page, redirection
            fillSessionFlash('warning', "Veuillez d'abord choisir une filière à modifier.");
            header("Location: filieres.php");
            exit();
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

                    <li class="menu-item">
                        <a href="enseignants.php" class="menu-link">
                            <i class="menu-icon fas fa-user-tie"></i>
                            <div>Enseignants</div>
                        </a>
                    </li>

                    <li class="menu-item active">
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
                        <a href="filieres.php">
                            <button type="button" class="btn rounded-pill btn-icon btn-primary">
                                <span class="tf-icons bx bx-left-arrow-alt"></span>
                            </button>
                        </a>
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Filières /</span> Mise à jour de
                            filière</h4>
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
                                                <label class="form-label" for="nom">Libellé</label>
                                                <input type="text" name="lib" class="form-control" id="lib"
                                                    placeholder="Saisir le libellé" value="<?php echo $lib; ?>" />
                                                <input type="text" name="id" class="hidden" id="id"
                                                    value="<?php echo $code; ?>" />
                                            </div>

                                            <input class="btn btn-primary" type="submit" name="send"
                                                value="Mettre à jour" />
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