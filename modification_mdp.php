<?php session_start();
include_once('connexion_sql.php');
// if nécessaire ?
if(isset($_POST['modif_mdp'])){
    if(empty($_POST['mdp']) OR empty($_POST['mdp2'])){
        $_SESSION['erreur_mdp']="Veuillez remplir 2 fois le mot de passe";
    } else {
        $mdp=htmlspecialchars($_POST['mdp']);
        $mdp2=htmlspecialchars($_POST['mdp2']);
        if($mdp!=$mdp2){
            $_SESSION['erreur_mdp']="Les 2 mots de passe ne sont pas identiques";
        } else {
            //hashage du mdp
            $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);
            $req_modif_mdp=$bdd->prepare('UPDATE profil SET mdp=:mdp_hash WHERE id_profil=:id_profil');
            $req_modif_mdp->execute(array('mdp_hash'=>$mdp_hash,'id_profil'=>$_SESSION['id_profil']));
        }
    }
}

header('Location: /www/hebergement');
exit();
?>