<?php session_start();

// if nécessaire? (les champs email et mdp sont en "required")
if (isset($_POST['email']) AND (isset($_POST['mdp']))){
    $email=htmlspecialchars($_POST['email']);
    $email=strtolower($email);
    $mdp=htmlspecialchars($_POST['mdp']);
    if (!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#",$email)) {
        $_SESSION['erreur_identif']="l'adresse email est invalide";
    } else {
        include_once('connexion_sql.php');
        //on vérifie que le mdp correspond au mdp hashé stocké:
        $recup_mdp=$bdd->prepare('SELECT id_profil, mdp FROM profil WHERE email=? '); 
        $recup_mdp->execute(array($email)); 
        $identification=$recup_mdp->fetch();

        if (empty($identification)){
            $_SESSION['erreur_identif']="erreur d'email";
        } else {
            $passVerif = password_verify($mdp, $identification['mdp']);
            if (!$passVerif)
            {
                $_SESSION['erreur_identif']="erreur de mot de passe";
            } else {
                $_SESSION['id_profil']=$identification['id_profil'];
            }
        } 
    }
}
header('Location: /www/hebergement');
exit();
?>