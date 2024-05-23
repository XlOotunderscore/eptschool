<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Paramètres</title>
    <link rel="icon" type="image/x-icon" href="images/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/styles-dashboard.css" />

    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css" />

</head>

<?php
        require_once 'includes/functions.php';
        require_once 'includes/db.php';
        require_once 'models/enseignant.php';
        require_once 'models/etudiant.php';

        startSession();
        verify_access('Etudiant');

        if(isset($_POST['sendMdp'])){
          $omdp = $_POST['omdp'];
          $nmdp = $_POST['nmdp'];

          if(!empty($omdp)){
            if(!empty($nmdp)){
              //Vérifier l'ancien mdp
              if(password_verify($omdp, $_SESSION['auth']->mdp))
              {
                //mot de passe OK
                //Chiffrement du mot de passe
                $hashed_pass = password_hash($nmdp, PASSWORD_BCRYPT);

                //Modification dans la bd

                $valueList = [
                  $hashed_pass
                ];

                updateTableRecord('etudiant', ['mdp_etu'], $valueList, Etudiant::$keys, [$_SESSION['auth']->matricule]);
                fillSessionFlash('success', "Mot de passe modifié avec succès");
                $_SESSION['auth'] = Etudiant::getUsingMatricule($_SESSION['auth']->matricule);

              }
              else{
                $erreurs['mdp'] = "Ancien mot de passe incorrect !";
                fillSessionFlash('danger', $erreurs['mdp']);
              }
            }
            else{
              $erreurs['mdp'] = "Saisir le nouveau mot de passe !";
              fillSessionFlash('danger', $erreurs['mdp']);
            }
          }
          else{
            $erreurs['mdp'] = "Saisir l'ancien mot de passe !";
            fillSessionFlash('danger', $erreurs['mdp']);
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

                    <li class="menu-item">
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

                    <li class="menu-item active">
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
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <h4 class="fw-bold py-3 mb-4">
                            Paramètres du compte</h4>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-4">
                                    <h5 class="card-header">Modifier le mot de passe</h5>
                                    <div class="card-body">
                                        <form id="formMdp" method="POST">
                                            <div class="row">
                                                <div class="mb-3 col-md-6 form-password-toggle">
                                                    <label class="form-label" for="opassword">Mot de passe</label>
                                                    <div class="input-group input-group-merge">
                                                        <input type="password" id="opassword" class="form-control"
                                                            name="omdp"
                                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                            value="<?php if(isset($erreurs['mdp'])) echo $omdp; ?>"
                                                            aria-describedby="password" />
                                                        <span class="input-group-text cursor-pointer"><i
                                                                class="bx bx-hide"></i></span>
                                                    </div>
                                                </div>


                                                <div class="mb-3 col-md-6 form-password-toggle">
                                                    <label class="form-label" for="npassword">Mot de passe</label>
                                                    <div class="input-group input-group-merge">
                                                        <input type="password" id="npassword" class="form-control"
                                                            name="nmdp"
                                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                            value="<?php if(isset($erreurs['mdp'])) echo $nmdp; ?>"
                                                            aria-describedby="password" />
                                                        <span class="input-group-text cursor-pointer"><i
                                                                class="bx bx-hide"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <input type="submit" class="btn btn-primary me-2" name="sendMdp"
                                                    value="Modifier mon mot de passe" />

                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                            <!-- /Account -->
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