<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bulletin étudiant</title>
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
        require_once 'models/suivre.php';
        require_once 'models/cours.php';
        require_once 'models/classe.php';
        require_once 'models/filiere.php';


        startSession();
        verify_access('Administrateur');

        if(isset($_POST['id'])){
            $etu = Etudiant::getUsingMatricule($_POST['id']);

            $classe = Classe::getById($etu->codeClasse);
            $matieres = array();
            $coefs = array();
            $moyennes = array();
            $moyennesPond = array();

            $totalCoefs = 0;
            $totalMoyennesPond = 0;

            $suivres = Suivre::getByMatriculeDistinct($etu->matricule, true);
            foreach ($suivres as $suivre) {
                array_push($matieres, $suivre);
            }

            foreach ($matieres as $matiere) {
                $coefs[$matiere] = Cours::getById($matiere)->coef;
                $totalCoefs += Cours::getById($matiere)->coef;
                
                $suivres = Suivre::getBySubId($etu->matricule, $matiere, true);
                $moy = 0;
                foreach ($suivres as $suivre) {
                    $moy += $suivre->note;
                }

                $moy /= count($suivres);
                $moyennes[$matiere] = $moy;
                $moyennesPond[$matiere] = $moy * Cours::getById($matiere)->coef;
                $totalMoyennesPond += $moy * Cours::getById($matiere)->coef;

            }
        }
        else{
            //Actualsisation manuelle ou accès anormal à la page, redirection
            fillSessionFlash('warning', "Veuillez d'abord choisir un étudiant.");
            header("Location: etudiants.php");
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

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
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

            <li class="menu-item active">
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
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <h4 class="fw-bold py-3 mb-4">
                            Aperçu du bulletin</h4>

                        <div class="row">
                            <div class="d-flex justify-content-end">
                                <div id="download" onclick="print('<?= $etu->matricule ?>')" class="btn btn-primary mb-4">Télécharger<i
                                        class="ms-2 fas fa-download"></i>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="bulletin">
                            <div class="col-md-12">
                                <div class="card mb-4">
                                    <h5 class="card-header text-center">BULLETIN DE NOTES</h5>
                                    <div class="card-body">
                                        <div class="container-fluid">
                                            <div class="row w-100">
                                                <div class="col">
                                                    <p class="fw-bold">Matricule : <span
                                                            class="fw-lighter"><?= $etu->matricule ?></span>
                                                    </p>
                                                    <p class="fw-bold">Nom : <span
                                                            class="fw-lighter"><?= $etu->nom . ' ' . $etu->prenom ?></span>
                                                    </p>
                                                    <p class="fw-bold">Filiere : <span
                                                            class="fw-lighter"><?= Filiere::getById($classe->codeFil)->lib ?></span>
                                                    </p>
                                                    <p class="fw-bold">Classe : <span
                                                            class="fw-lighter"><?= $classe->lib ?></span></p>
                                                </div>
                                            </div>

                                            <div class="row w-100 mt-4">
                                                <div class="col">
                                                    <div class="table-responsive text-nowrap">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Matière</th>
                                                                    <th>Moyenne</th>
                                                                    <th>Coefficient</th>
                                                                    <th>Moyenne pondérée</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="table-border-bottom-0">
                                                                <?php
                                                                    if(count($matieres) > 0){
                                                                        foreach($matieres as $matiere)
                                                                        {
                                                                            $contenu = '<tr>';
                                                                            $contenu .= '<td><strong>'. Cours::getById($matiere)->titre . '</strong></td>';
                                                                            $contenu .= '<td>'. round($moyennes[$matiere], 2) . '</td>';
                                                                            $contenu .= '<td>'. $coefs[$matiere] . '</td>';
                                                                            $contenu .= '<td>'. round($moyennesPond[$matiere], 2) . '</td>';
                                                                            $contenu .= '</tr>';

                                                                            echo $contenu;

                                                                        }
                                                                    }
                                                                    else{
                                                                        //Ligne vide, meilleur affichage
                                                                        $contenu = '<tr><td></td><td></td><td></td><td></td></tr>';
                                                                        echo $contenu;
                                                                    }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row w-100 mt-5">
                                                <div class="col">
                                                    <?php if($totalCoefs != 0): ?>
                                                    <p class="fw-bold fs-4 text-center">Moyenne générale : <span
                                                            class="fw-lighter"><?= round($totalMoyennesPond / $totalCoefs, 2) ?></span>
                                                    </p>
                                                    <?php endif; ?>

                                                </div>
                                            </div>
                                        </div>
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
    <script src="js/html2canvas.js"></script>
    <script src="js/jspdf.min.js"></script>

    <script>
        function print(id) {
            var clientWidth = document.getElementById('bulletin').clientWidth;
            var clientHeight = document.getElementById('bulletin').clientHeight;
            html2canvas(document.querySelector('#bulletin')).then((canvas) => {
            let base64image = canvas.toDataURL('image/png');
            console.log(base64image);
            let pdf = new jsPDF({orientation: 'p', unit: 'px', format: [2000, 1200], hotfixes: ["px_scaling"]});
            pdf.addImage(base64image, 'PNG', 0, 0, clientWidth, clientHeight);
            pdf.save(id + '.pdf');
          });
        }
    </script>

</body>

</html>