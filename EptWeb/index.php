<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,300,400,500,700,900" rel="stylesheet">

    <title>SwiftPHP - Accueil</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">

    <!-- Page -->
    <link rel="stylesheet" href="css/styles-landing.css">
    
</head>
<?php 
    require_once 'includes/functions.php';
    startSession();
?>
    
<body>
    
    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <a href="#" class="logo ">
                           
                        <span>
                            <img src="images/logoinit.png" style="width:200px; height:200px; margin-top: -70px;margin-left: -75px;" />
                        </span>

                           
                          
                        </a>
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <a href="#welcome" ><img width="48" height="48" src="https://img.icons8.com/doodle/48/person-at-home.png" alt="person-at-home"/>
                                Accueil</a>
                            <a href="#Processus"><img width="48" height="48" src="https://img.icons8.com/doodle/48/process.png" alt="process"/>
                            Processus</a>
                            <li>
                            <a href="login.php">
                                <img width="48" height="48" src="https://img.icons8.com/doodle/48/user-male-circle.png" alt="user-male-circle"/>
                                Se connecter
                            </a>

                        </ul>

						<a class='menu-trigger'>
                            <span>Menu</span>
                        </a>

                        <!-- ***** Menu End ***** -->
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- ***** Header Area End ***** -->

    <!-- ***** Welcome Area Start ***** -->
    <div class="welcome-area" id="welcome">

        <!-- ***** Header Text Start ***** -->
        <div class="header-text">
            <div class="container">
                <div class="row">
                    <div class="offset-xl-3 col-xl-6 offset-lg-2 col-lg-8 col-md-12 col-sm-12">
                         <?php loadWarnings(""); ?>
                        <h1>Plate-forme de gestion <strong>des activités</strong><br>de l' <strong>Etablissement EPT</strong></h1>
                        <p>Nous proposons une gestion plus facile et économique des activités de l'etablissement </p>
                        <a href="#Processus" class="main-button-slider">Plus d'infos</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- ***** Header Text End ***** -->
    </div>
    <!-- ***** Welcome Area End ***** -->

    <!-- ***** Features Small Start ***** -->
    <section class="section home-feature">
        <div class="container m-5">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row" id="login">
                        <!-- ***** Features Small Item Start ***** -->
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12" data-scroll-reveal="enter bottom move 50px over 0.6s after 0.2s">
                            <a href="login.php">
                                <div class="features-small-item">

                                    <div class="icon">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                    <h5 class="features-title">Étudiant</h5>
                                    <p>Consulter vos notes et imprimez vos bulletins de note</p>
                                </div>
                            </a>
                        </div>
                        <!-- ***** Features Small Item End ***** -->

                        <!-- ***** Features Small Item Start ***** -->
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12" data-scroll-reveal="enter bottom move 50px over 0.6s after 0.4s">
                            <a href="login_admin.php">
                                <div class="features-small-item">
                                <div class="icon">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <h5 class="features-title">Enseignants</h5>
                                <p>Saisir les notes des etudiants</p>
                            </div>
                            </a>
                        </div>
                        <!-- ***** Features Small Item End ***** -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Features Small End ***** -->

    <!-- ***** Features Big Item Start ***** -->
    <section class="section padding-bottom-100 margin-top-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 align-self-center mobile-bottom-fix">
                    <div class="left-heading">
                        <h2 class="section-title">Accedez facilement à vos resultats scolaires sans fournir trop d'effort.</h2>
                    </div>
                    <div class="left-text">
                        <p>Grâce aux services offerts par la plateforme, Vous pouvez accedez àà vos notes ainsi que vos bulletins facilement et partout ou vous êtes.</p>
                    </div>
                </div>
                <div class="col-lg-1"></div>
                <div class="col-lg-5 col-md-12 col-sm-12 align-self-center mobile-bottom-fix-big" data-scroll-reveal="enter right move 30px over 0.6s after 0.4s">
                    <img src="images/note.jpeg" class="rounded img-fluid d-block mx-auto" alt="App">
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Features Big Item End ***** -->

    <!-- ***** Home Parallax Start ***** -->
    <section class="mini" id="work-process">
        <div class="mini-content">
            <div class="container">
                <div class="row">
                    <div class="offset-lg-3 col-lg-6">
                        <div class="info" id="Processus">
                            <h1>Processus</h1>
                            <p>Toutes les étapes pour avoir accès à vos notes et bulletins.</p>
                        </div>
                    </div>
                </div>

                
                    </div><!-- ***** Mini Box Start ***** -->
                <div class="row">
                    <div class="col md-4 d-flex justify-content-center">
                    <div class="col-lg-4 col-md-3 col-sm-6 col-6">
                        <a href="login.php" class="mini-box">
                            <i><img src="images/work-process-item-01.png" alt=""></i>
                            <strong>Je me connecte</strong>
                            <span>Connectez-vous en utilisant votre matricule etudiant et votre mots de passe.</span>
                            
                        </a>
                    </div>
                    </div>
                    <div class="col md-4 d-flex justify-content-center">
                    <div class="col-lg-4 col-md-3 col-sm-6 col-6">
                        <a href="#" class="mini-box">
                            <i class="fas fa-rocket"></i>
                            <strong>Je suis consulte mes notes </strong>
                            <span>Consultez les differents resultats de vos evaluations dans chaque cours.</span>
                            
                        </a>
                    </div>
                    </div>
                    <div class="col md-4 d-flex justify-content-center">
                    <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                        <a href="#" class="mini-box">
                            <i><img src="images/work-process-item-01.png" alt=""></i>
                            <strong>J' imprime mon bulletins de notes</strong>
                            <span>imprimez vos resultats scolaire pour le semestre .</span>
                        </a>
                    </div>
                    </div>
                <!-- ***** Mini Box End ***** -->
            </div>
        </div>
    </section>
   
   <!-- Bootstrap -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Page -->
    <script src="js/script-landing.js"></script>
</body>
</html>