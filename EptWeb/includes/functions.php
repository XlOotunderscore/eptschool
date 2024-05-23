<?php 
    function startSession(){
        if(session_status() == PHP_SESSION_NONE)
            session_start(); 
    }

    function loadWarnings($add_class = "alert-dismissible"){
        /* La clé flash de SESSION permet d'afficher des infos temporaires à l'ouverture des pages,
        infos qui seront supprimées si l'on actualise/quitte la page. On récupère donc tous les
        éléments de la clé flash, qui sont sous la forme clé=type => valeur=message.
        Cela permet de faciliter l'affichage dans des balises de classe appropriée avec bootstrap */

        if(isset($_SESSION['flash'])):
            foreach($_SESSION['flash'] as $type => $message): ?>
            <div class = "alert alert-<?= $type; ?> <?= $add_class; ?>" role="alert">
                <?= $message; ?>
                <?php if($add_class == "alert-dismissible"): ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <?php endif; ?>
            </div>
        
            <?php 
                endforeach;
                unset($_SESSION['flash']);
                endif;

    }

    function fillSessionFlash($key, $value){
        if(isset($_SESSION['flash'][$key]))
            $_SESSION['flash'][$key] .= $value;
        else
            $_SESSION['flash'][$key] = $value;
    }

    function fillSessionFlashNewLine($key, $value, $sep = '<br>'){
        if(isset($_SESSION['flash'][$key]))
            $_SESSION['flash'][$key] .= $value . $sep;
        else
            $_SESSION['flash'][$key] = $value . $sep;
    }

    function verify_connex($redirect = true)
    {
        //Permet de vérifier si l'utilisateur est connecté
        startSession();
        if(!isset($_SESSION['auth']))
        {
            if($redirect)
            {
                $_SESSION['flash']['danger'] = "Vous n'avez pas le droit d'accéder à cette page";
                header('Location: index.php');
            }
            return false;
            
        }
        else
        {
            return true;
        }
    }
    function verify_access($allowedFunc, $redirect = true, $scope='local')
    {
        /*Permet de vérifier si l'utilisateur a le droit d'accéder à une page donnée
        * en fonction de son role
        */
        startSession();

        if(isset($_SESSION['auth']))
        {
            $func = '';
            $gfunc = '';
            if(isset($_SESSION['auth']->estAdmin)){
                $gfunc = 'Administrateur';
                //Super admin ou enseignant
                if($_SESSION['auth']->estAdmin == 1)
                    $func = "Administrateur";
                else
                    $func = "Enseignant";

            }
            else{
                //Etudiant
                $func = 'Etudiant';
                $gfunc = 'Etudiant';
            }

            if($scope == 'global'){
                if($gfunc != $allowedFunc)
                {
                    
                    if($redirect)
                    {
                        $_SESSION['flash']['danger'] = "Seul un " .$allowedFunc. " a le droit d'accéder à cette page";
                        caseRedirect();
                    }
                     
                    return false;
                    
                }
                else
                {
                    return true;
                } 

            }
            else{
                if($func != $allowedFunc)
                {
                    
                    if($redirect)
                    {
                        $_SESSION['flash']['danger'] = "Seul un " .$allowedFunc. " a le droit d'accéder à cette page";
                        caseRedirect();
                    }
                     
                    return false;
                    
                }
                else
                {
                    return true;
                } 
            }
        }
        else
        {
            //Détection transparente si redirect = false, sinon redirection
            if($redirect)
            {
                $_SESSION['flash']['danger'] = "Seul un " .$allowedFunc. " a le droit d'accéder à cette page";
                header('Location: index.php');
            }
            return false;
        }
        
    }

    function caseRedirect($connectedOnly = 'false')
    {
        //Permet de rediriger l'utilisateur selon son role

        startSession();

        if(isset($_SESSION['auth']))
        {
            if(isset($_SESSION['auth']->estAdmin)){
                //Super admin ou enseignant
                if($_SESSION['auth']->estAdmin == 1){
                    header('Location: dash_admin.php');
                }
                else{
                    header('Location: dash_ens.php');
                }
            }
            else{
                //Etudiant
                header('Location: dash.php');
            }
        }
        else
        {
            if(!$connectedOnly)
                header('Location: index.php');
        }
    }

    function disconnect()
    {
        //Permet de déconnecter et rediriger l'utilisateur sur sa page de login selon son role

        if(session_status() == PHP_SESSION_NONE)
        {
            session_start(); 
        }

        if(isset($_SESSION['auth']))
        {
            if(isset($_SESSION['auth']->estAdmin)){
                //Super admin ou enseignant
                unset($_SESSION['auth']);
                header('Location: login_admin.php');
                
            }
            else{
                //Etudiant
                unset($_SESSION['auth']);
                header('Location: login.php');
            }
        }
        else
        {
            header('Location: index.php');
        }
    }

    function getAppropriateRoleName($role){
        $aRole = '';
        switch ($role) {
            case 'Normal':
                $aRole = 'Formateur';
                break;

            case 'Super':
                $aRole = 'Super admin';
                break;
            default:
                # code...
                break;
        }

        return $aRole;
    }

    function formatDate($date,  $formatOutput, $formatBase = 'Y-m-d H:i:s'){
        $dateObj = DateTime::createFromFormat($formatBase, $date);
        return $dateObj->format($formatOutput);
    }

