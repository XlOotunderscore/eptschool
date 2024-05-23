<!DOCTYPE html>

<!-- beautify ignore:start -->
<html>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Tableau de bord</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="images/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons -->
    <link rel="stylesheet" href="fonts/boxicons.css" />
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css"/>

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

        //Récupération des catégories
        $etudiants = Etudiant::getAllRecords();
        $enseignants = Enseignant::getRecordsCustomCondition('WHERE est_admin = 0');

        
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

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
              <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">
            <!-- Dashboard -->
            <li class="menu-item active">
              <a href="dash_admin.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div>Tableau de bord</div>
              </a>
            </li>

            <li class="menu-item">
              <a
                href="enseignants.php"
                class="menu-link"
              >
                <i class="menu-icon fas fa-user-tie"></i>
                <div>Enseignants</div>
              </a>
            </li>

            <li class="menu-item">
              <a
                href="filieres.php"
                class="menu-link"
              >
                <i class="menu-icon fas fa-sheet-plastic"></i>
                <div>Filières</div>
              </a>
            </li>

            <li class="menu-item">
              <a
                href="classes.php"
                class="menu-link"
              >
                <i class="menu-icon fas fa-school fa-icon"></i>
                <div>Classes</div>
              </a>
            </li>

            <li class="menu-item">
              <a
                href="cours.php"
                class="menu-link"
              >
                <i class="menu-icon fas fa-book"></i>
                <div>Cours</div>
              </a>
            </li>

            <li class="menu-item">
              <a
                href="etudiants.php"
                class="menu-link"
              >
                <i class="menu-icon fas fa-user-graduate"></i>
                <div>Etudiants</div>
              </a>
            </li>

            <li class="menu-item">
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
              <a
                href="settings_admin.php"
                class="menu-link"
              >
                <i class="menu-icon fas fa-gear"></i>
                <div>Paramètres</div>
              </a>
            </li>

            <li class="menu-item">
              <a
                href="disconnect.php"
                class="menu-link"
              >
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
                <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tableau de bord</span></h4>
                <h4 class="fw-bold py-3 mt-2"><span class="text-muted fw-light">Bienvenue, </span> <?= $_SESSION['auth']->nom . ' ' . $_SESSION['auth']->prenom?> !</h4>
                <div class="row">
                  <div class="col-md-6 col-xl-4">
                    <div class="card bg-primary text-white mb-3">
                      <div class="card-body">
                        <h3 class="card-title text-white"><?= count($etudiants); ?></h3>
                        <p class="card-text">Etudiant(s) inscrits(s)</p>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6 col-xl-4">
                    <div class="card bg-primary text-white mb-3">
                      <div class="card-body">
                        <h3 class="card-title text-white"><?= count($enseignants); ?></h3>
                        <p class="card-text">Enseignant(s) inscrits(s)</p>
                      </div>
                    </div>
                  </div>
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

  </body>
</html>