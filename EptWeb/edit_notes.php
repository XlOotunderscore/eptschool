<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Edition des notes</title>

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
        require_once 'models/filiere.php';
        require_once 'models/classe.php';
        require_once 'models/etudiant.php';
        require_once 'models/cours.php';
        require_once 'models/suivre.php';
        require_once 'models/enseignant.php';
        startSession();
        verify_access('Enseignant');


        if(isset($_POST['id'])){
            $id = $_POST['id'];
            $notesRows = 0;
            $notesCols = 0;

            if(isset($_POST['idDel'])){
                //Suppression d'une colonne
                $reqDel = $pdo->prepare("DELETE FROM suivre WHERE code_cours = ? AND date_ob = ?");
                $reqDel->execute([$_POST['idDel'], $_POST['date']]);
                $_SESSION['flash']['success'] = "Note supprimée avec succès !";
            }

            if(isset($_POST['colNum']))
            {
                for($i = 1; $i <= $_POST['colNum']; $i++)
                {
                    $noteDate = $_POST[$i . '-date'];
    
                    for($j = 1; $j <= $_POST['rowNum']; $j++)
                    {
                        $idEtud = $_POST['d' . $j];
                        $noteValue = $_POST['r' . $j . 'n' . $i];
    
                        //Vérification unicité pour choisir entre update/insert
                        $item = Suivre::getByFullId($idEtud, $id, $noteDate, true);
    
                        if($item)
                        {
    
                            //Modification de la note
                            $valueList = [
                                $noteValue
                            ];

                            $keyValues = [
                                $idEtud,
                                $id,
                                $noteDate
                            ];

                            updateTableRecord('Suivre', ['note'], $valueList, Suivre::$keys, $keyValues);
                        }
                        else
                        {
                            //Nouvelle note
                            $valueList = [
                                $idEtud,
                                $id,
                                $noteDate,
                                $noteValue
                            ];

                            addTableRecord('Suivre', array_merge(Suivre::$keys, Suivre::$fields), $valueList);
                        }
                    }
                }
    
                $_SESSION['flash']['success'] = "Notes modifiées avec succès !";
            }

            $cours = Cours::getById($id);
            $classe = Classe::getById($cours->getClasse());
            $etudiants = Etudiant::getByCodeClasse($classe->id);
            $notesHeader = array();

            if(count($etudiants) > 0){
                $notesHeader = Suivre::getBySubId($etudiants[0]->matricule, $id, true);
            }

        }
        else{
            //Actualsisation manuelle ou accès anormal à la page, redirection
            fillSessionFlash('warning', "Veuillez d'abord choisir un cours.");
            header("Location: choix_cours.php");
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
                        <a href="dash_ens.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div>Tableau de bord</div>
                        </a>
                    </li>

                    <li class="menu-item active">
                        <a href="choix_cours.php" class="menu-link">
                            <i class="menu-icon fas fa-book"></i>
                            <div>Saisie des notes</div>
                        </a>
                    </li>
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Compte</span>
                    </li>

                    <li class="menu-item">
                        <a href="settings_ens.php" class="menu-link">
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
                        <a href="choix_cours.php">
                            <button type="button" class="btn rounded-pill btn-icon btn-primary">
                                <span class="tf-icons bx bx-left-arrow-alt"></span>
                            </button>
                        </a>
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Notes /</span> Édition des notes</h4>
                        <div class="row mb-3">
                            <div class="d-flex justify-content-end">
                                <span class="badge bg-primary"><?= $classe->lib ?> | <?= $cours->titre ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl">
                                <div class="card mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Nouvelle note</h5>
                                        <small class="text-muted float-end">Remplir le champ</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label" for="date-note">Date de la note</label>
                                            <input class="form-control" id="date-note" type="datetime-local" value="">
                                        </div>

                                        <div class="btn btn-primary" onclick="addColumn();">Ajouter une colonne</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <form action="" method="post">
                                <input type="text" name="id" class="form-control hidden"
                                                    value="<?php echo $id ?>" />
                                <div class="table-responsive text-nowrap">
                                    <table class="table" id="notes-list">
                                        <thead>
                                            <tr id="notes-title">
                                                <th>Etudiants</th>
                                                <?php foreach ($notesHeader as $noteHeader): ?>
                                                    <?php 
                                                        $notesCols++;
                                                        $fDate = formatDate($noteHeader->date, 'd/m/Y H:m');
                                                    ?>

                                                    <th id="c<?=$notesCols?>"><?= $fDate ?></th>
                                                    <input type="hidden" name="<?=$notesCols?>-date" id="<?=$notesCols?>-date" value="<?=$noteHeader->date?>">

                                                <?php endforeach; ?>

                                            </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0">
                                            <?php foreach ($etudiants as $etudiant): ?>
							                    <?php $notesRows++; ?>
                                                <tr id="r<?=$notesRows?>">
                                                    <td><?= $etudiant->nom . " " . $etudiant->prenom . " (" . $etudiant->matricule . ")" ?></td>
                                                    <input type="hidden" name="d<?=$notesRows?>" id="d<?=$notesRows?>" value="<?=$etudiant->matricule?>">
                                                    <?php 
                                                        $nIndex = 0;
                                                        //Boucler sur les notes de l'étudiant
                                                        $notes = Suivre::getBySubId($etudiant->matricule, $id, true);
                                                        foreach ($notes as $note):
                                                    ?>
                                                        <?php $nIndex++; ?>
                                                        <td><input type="number" name="r<?=$notesRows?>n<?=$nIndex?>" step="0.5" min="0" max="20"
                                                        value="<?= $note->note ?>" id="r<?=$notesRows?>n<?=$nIndex?>" required></td>
                                                    <?php endforeach; ?>
                                                </tr>
                                            <?php endforeach; ?>

                                            <tr>
                                                <td></td>
                                                <?php foreach ($notesHeader as $noteHeader): ?>
                                                    <td>
                                                        <button type="button" class="ms-4 ms-sm-3 btn btn-icon btn-danger" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" data-bs-original-title="Supprimer" 
                                                        onclick="javascript:sendForm('<?= $noteHeader->codeCours ?>', '<?= $noteHeader->date ?>')">
                                                            <span class="tf-icons bx bx-trash">
                                                        </button>
                                                    </td>
                                                <?php endforeach; ?>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div id="notes-info" style="display: none;">

                                </div>

                                <input class="btn btn-primary ms-3 mb-3" name="send" type="submit" value="Valider">

                                <input type="hidden" name="colNum" id="colNum" value="<?= $notesCols ?>">
                                <input type="hidden" name="rowNum" id="rowNum" value="<?= $notesRows ?>">
                            </form>


                            <div style="display: none;">
                                <div id="colNumber"><?= $notesCols ?></div>
                                <div id="rowNumber"><?= $notesRows ?></div>
                            </div>

                        </div>

                        <form action="" style="display: none;" method="post" id="eForm">
                            <input type="text" value="" name="idDel" id="eid">
                            <input type="text" value="" name="date" id="edate">
                            <input type="text" name="id" value="<?php echo $id ?>" />
                        </form>

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

    <script>
        function sendForm(codeCours, date) {
            document.getElementById("eid").value = codeCours;
            document.getElementById("edate").value = date;
            document.getElementById("eForm").submit();
        }

        function addColumn(){
			//lx => Note labels, dx => matricules etu, => cx => table cols, => rx table rows
			//rxnx => Note values

			var header = document.getElementById('notes-title');
			var colNumberDiv = document.getElementById('colNumber');
			var rowNumberDiv = document.getElementById('rowNumber');
			var colNumberInput = document.getElementById('colNum');
			var notesInfoDiv = document.getElementById('notes-info');
			
			var dateNote = document.getElementById('date-note').value;
			if(dateNote == "")
			{
				alert('Remplissez la date SVP !');
			}
			else
			{
                var datePart = dateNote.split('T')[0];
                var hourPart = dateNote.split('T')[1];
				var fDate = datePart.split('-')[2] + "/" + datePart.split('-')[1] + "/" + datePart.split('-')[0];

				var fDateNote = fDate + " " + hourPart;
				var tDateNote = datePart + " " + hourPart + ":00";

				var colNumber = parseInt(colNumberDiv.textContent) + 1;
				
                header.innerHTML += `<th id = "c${colNumber}">${fDateNote}</th>`;
				
				notesInfoDiv.innerHTML += `<input type="hidden" name="${colNumber}-date" id="${colNumber}-date" value="${tDateNote}">`;

				//header.innerHTML = header.innerText;

				var rowNumber = parseInt(rowNumberDiv.textContent);
				for(var i = 1; i <= rowNumber; i++)
				{
					var row = document.getElementById('r' + i);
					var rowDiv = document.getElementById('d' + i);
					var idEtud = rowDiv.innerText;

					for(var j = 1; j <= colNumber; j++)
					{
						if (!document.getElementById('r' + i + 'n' + j))
						{
							row.innerHTML += `<td><input type="number" max="20" min="0" step="0.5" name="r${i}n${j}" value="" id="r${i}n${j}" required></td>`
						}
					}

					//row.innerHTML = row.innerText;
					
				}

				colNumberDiv.innerHTML = colNumber.toString();
				colNumberInput.value = colNumber.toString();
				colNumberInput.setAttribute('value', colNumber.toString());
			}
		
		}
    </script>
</body>

</html>