<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Mettre à jour un étudiant</title>

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


    startSession();
    verify_access('Administrateur');

    if(isset($_POST['id'])){
        $id = $_POST["id"];
        $etu = Etudiant::getUsingMatricule($id);

        $nom = $etu->nom;
        $prenom = $etu->prenom;
        $genre = $etu->genre;
        $date = $etu->dateNaiss;

        //Lorsque formulaire envoyé, vérification des champs
        if(isset($_POST["send"])){
            $classe = $_POST["classe"];

            if(!empty($classe)) {
            //....
            }
            else{
                $erreurs["classe"] = "Veuillez choisir la classe !";
                fillSessionFlashNewLine('danger', $erreurs["classe"]);

            }
        

            if(!isset($erreurs)){

                $valueList = [
                    $classe
                ];

                //Ajout
                if(addTableRecord('etudiant', 'code_cl', $valueList)){
                    fillSessionFlash('success', "Modifié avec succès");
                }

                else{
                    $erreurs['sql'] = "Une erreur est survenue, veuillez réessayer";
                    fillSessionFlash('danger', $erreurs['sql']);
                }
                            
            }
        }
        else{
            //Premier accès à la page (depuis page-liste)
            $classe = $etu->codeClasse;

        }
    }
    else{
        //Actualsisation manuelle ou accès anormal à la page, redirection
        fillSessionFlash('warning', "Veuillez d'abord choisir un étudiant à modifier.");
        header("Location: etudiants.php");
        exit();
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
                        <a href="enseignants.php">
                            <button type="button" class="btn rounded-pill btn-icon btn-primary">
                                <span class="tf-icons bx bx-left-arrow-alt"></span>
                            </button>
                        </a>
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Étudiants /</span> Mettre à jour
                            un
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
                                                <input type="text" name="matricule" disabled value="<?php echo $id; ?>"
                                                    class="form-control" id="matricule"
                                                    placeholder="Saisir le matricule" />
                                                <input type="text" name="id" class="form-control hidden"
                                                    value="<?php echo $id ?>" />
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="nom">Nom</label>
                                                <input type="text" disabled value="<?php echo $nom; ?>" name="nom"
                                                    class="form-control" id="nom" placeholder="Saisir le nom" />
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="prenom">Prénom</label>
                                                <input type="text" disabled value="<?php echo $prenom; ?>" name="prenom"
                                                    class="form-control" id="prenom" placeholder="Saisir le prénom" />
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Genre</label>
                                                <div class="mb-3 d-flex justify-content-between">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" disabled type="radio"
                                                            name="genre" id="genreOption1" value="M"
                                                            <?php if(($genre == 'M') || !isset($erreurs)) echo 'checked'; ?> />
                                                        <label class="form-check-label"
                                                            for="inlineRadio1">Masculin</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" disabled type="radio"
                                                            name="genre" id="genreOption2" value="F"
                                                            <?php if($genre == 'F') echo 'checked'; ?> />

                                                        <label class="form-check-label"
                                                            for="inlineRadio2">Féminin</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label" for="date">Date de naissance</label>
                                                <input class="form-control" disabled name="date" type="date"
                                                    value="<?php echo $date; ?>" id="date">
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label" for="classe">Classe</label>
                                                <select class="form-select" name="classe" id="classe">
                                                    <?php foreach($classes as $cl): ?>
                                                    <option <?php if($classe == $cl->id) echo 'selected' ?>
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