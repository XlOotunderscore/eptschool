<!DOCTYPE html>

<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>SwiftPHP - Connexion - Enseignant</title>

  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

  <!-- Icons -->
  <link rel="stylesheet" href="fonts/boxicons.css" />

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css" />

  <!-- Page CSS -->
  <link rel="stylesheet" href="css/styles-dashboard.css" />
  <link rel="stylesheet" href="css/auth.css" />

</head>

<?php 
    require_once 'includes/functions.php';
    require_once 'includes/db.php';
    require_once 'models/enseignant.php';
    require_once 'models/etudiant.php';

    startSession();
    caseRedirect(true);

    if(isset($_POST['send'])){
      $code = $_POST['code'];
      $password = $_POST['password'];

      if(!empty($code) && !empty($password))
      {
        //Si l'utilisateur a saisi des infos, connexion à la bd

  
        //Recherche de l'utilisateur dans la bd
        $user = Enseignant::getUsingId($code);
  
        //Si la recherche a bien renvoyé un résultat
        if($user)
        {
          //Vérification du mot de passe avec celui chiffré dans la BD
          if(password_verify($password, $user->mdp))
          {
            //Compte trouvé, mot de passe OK
            $_SESSION['auth'] = $user;
            if($user->estAdmin == 1)
              header("location: dash_admin.php");
            else
              header("location: dash_ens.php");
            exit();
          }

          else
          {
              //Utilisateur trouvé mais mot de passe incorect
              $errors['mdp'] = "Identifiant ou mot de passe incorrect";
              $_SESSION['flash']['danger'] = $errors['mdp'];
          }
        }    
        else
        {
            //Utilisateur non trouvé
            $errors['user'] = "Identifiant ou mot de passe incorrect";
            $_SESSION['flash']['danger'] = $errors['user'];
        }
      }
      else{
        $errors['fields'] = "Veuillez remplir tous les champs";
        $_SESSION['flash']['danger'] = $errors['fields'];
      }
    }

    loadWarnings();


?>

<body>
  <!-- Content -->
  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">
        <!-- Register -->
        <div class="card">
          <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center">
              <a href="index.php" class="app-brand-link gap-2">

                <span>
                    <img src="images/logoinit.png" style="width:150px; height:150px" />
                </span>
              </a>
            </div>
            <!-- /Logo -->
            <h4 class="mb-2">Bienvenue sur votre espace EPT !</h4>
            <h2 class="mb-3">Enseignant </h2>
            <p class="mb-4">Veuillez vous connecter avant de poursuivre</p>

            <form id="formAuthentication" class="mb-3" action="" method="POST">
              <div class="mb-3">
                <label for="code" class="form-label">Code</label>
                <input type="text" class="form-control" value="<?php if(isset($errors)) echo $code; ?>" id="code" name="code" placeholder="Entrez votre code" autofocus />
              </div>
              <div class="mb-3 form-password-toggle">
                <div class="d-flex justify-content-between">
                  <label class="form-label" for="password">Mot de passe</label>
                  
                </div>
                <div class="input-group input-group-merge">
                  <input type="password" id="password" class="form-control" name="password" value="<?php if(isset($errors)) echo $password; ?>" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                </div>
              </div>
              <div class="mb-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="remember-me" />
                  <label class="form-check-label" for="remember-me"> Rester connecté </label>
                </div>
              </div>
              <div class="mb-3">
                <input class="btn btn-primary d-grid w-100" type="submit" name="send" value="Se connecter"/>
              </div>
            </form>

            <p class="text-center">
                <span>Retourner à </span>
                <a href="index.php">
                  <span>l'accueil</span>
                </a>
            </p>

          </div>
        </div>
        <!-- /Register -->
      </div>
    </div>
  </div>

  <!-- / Content -->
  <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>