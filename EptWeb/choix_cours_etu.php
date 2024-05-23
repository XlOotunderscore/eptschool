<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Choisir un cours</title>

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
        
        $cours = Suivre::getByMatriculeDistinct($_SESSION['auth']->matricule);

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
                            </span>                    </a>

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
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Notes / Choix du cours</span>
                        </h4>

                        <div class="card">
                            <div class="table-responsive text-nowrap">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Intitulé</th>
                                            <th>Coefficient</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        <?php
                                    if(count($cours) > 0){
                                        foreach($cours as $code)
                                        {
                                            $item = Cours::getById($code);
                                            $contenu = '<tr>';
                                            $contenu .= '<td><strong>'. $item->id . '</strong></td>';
                                            $contenu .= '<td>'. $item->titre . '</td>';
                                            $contenu .= '<td>'. $item->coef . '</td>';

                                            $contenu .= '<td>';

                                            $contenu .= '<button type="button" class="btn btn-icon btn-primary" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" data-bs-original-title="Voir mes notes" 
                                            onclick="javascript:sendForm(\''. $item->id . '\')">';
                                            $contenu .= '<span class="tf-icons fas fa-angles-right">';
                                            $contenu .= '</button>';

                                            $contenu .= '</td>';
                                            $contenu .= '</tr>';

                                            echo $contenu;

                                        }
                                    }
                                    else{
                                        //Ligne vide, meilleur affichage
                                        $contenu = '<tr><td></td><td></td><td></td></tr>';
                                        echo $contenu;
                                    }
                                ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <form action="check_notes.php" style="display: none;" method="post" id="eForm">
                            <input type="text" value="" name="id" id="eid">
                        </form>
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