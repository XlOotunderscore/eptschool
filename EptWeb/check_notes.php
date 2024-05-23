<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Apercu des notes</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="images/favicon/favicon.ico" />

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
        require_once 'models/cours.php';
        require_once 'models/suivre.php';


        startSession();
        verify_access('Etudiant');
        
        if(isset($_POST['id'])){
            $id = $_POST['id'];
            $notes = Suivre::getBySubId($_SESSION['auth']->matricule, $id, true);

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
                    <span>
                                <img src="images/logoinit.png" style="width:120px; height:120px" />
                            </span>
                        
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
                        <a href="dash.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div>Tableau de bord</div>
                        </a>
                    </li>

                    <li class="menu-item active">
                        <a href="choix_cours_etu.php" class="menu-link">
                            <i class="menu-icon fas fa-note-sticky"></i>
                            <div>Consulter mes notes</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="bulletin.php" class="menu-link">
                            <i class="menu-icon fas fa-clipboard"></i>
                            <div>Bulletin</div>
                        </a>
                    </li>

                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Compte</span>
                    </li>

                    <li class="menu-item">
                        <a href="settings.php" class="menu-link">
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

                    <div class="container-xxl flex-grow-1 container-p-y mt-5">
                        <a href="choix_cours_etu.php">
                            <button type="button" class="btn rounded-pill btn-icon btn-primary">
                                <span class="tf-icons bx bx-left-arrow-alt"></span>
                            </button>
                        </a>

                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Notes / Aperçu des notes</span>
                        </h4>

                        <div class="row mb-3">
                            <div class="d-flex justify-content-end">
                                <span class="badge bg-primary">Cours : <?= Cours::getById($id)->titre ?></span>
                            </div>
                        </div>

                        <div class="row">
                            <?php foreach($notes as $note): ?>
                            <div class="col-md-6 col-xl-4">
                                <div class="card bg-primary text-white mb-3">
                                    <div class="card-body">
                                        <h3 class="text-white text-center fs-5">Note du <?= formatDate($note->date, 'd/m/Y H:i') ?></h3>
                                        <p class="card-text text-center fs-4"> <?= $note->note ?>/20</p>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>

                        </div>



                    </div>

                    <!-- / Content -->

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
    <script>
    function sendForm(id) {
        document.getElementById("eid").value = id;
        document.getElementById("eForm").submit();
    }
    </script>

</body>

</html>