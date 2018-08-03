<?php session_start();
include_once('connexion_sql.php');

/* s'il s'agit de modifier les infos de contact */

// si on arrive via le bouton "mettre à jour mon profil" 
if (isset($_POST['modif_profil'])){    
    $infos_modif=array('nom'=>'','telephone'=>'','email'=>'');
    foreach ($infos_modif as $cle=> &$element){
        if (!empty($_POST[''.$cle])){
            $element=htmlspecialchars($_POST[''.$cle]);
        } else {
            $_SESSION['erreur_modif']="il manque des informations";
        }
    }
    $infos_modif['id_profil']=$_SESSION['id_profil'];
    
    // vérification format email
    if (!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $infos_modif['email'])){
        $_SESSION['erreur_modif']="email invalide";
    } else {
        $infos_modif['email']=strtolower($infos_modif['email']);
        //vérification que l'email ne soit pas déjà enregistré dans une autre entrée de la bdd
        $req_verif=$bdd->prepare('SELECT COUNT(*) as nb_email FROM profil WHERE email=? AND id_profil!=?');
        $req_verif->execute(array($infos_modif['email'],$_SESSION['id_profil']));
        $verif=$req_verif->fetch();
        if ($verif['nb_email']!=0) {
            $_SESSION['erreur_modif']="cet email correspond à une personne déjà inscrite";
        }
    }
    // vérification format téléphone
    $caractere_a_suppr=array("-", ".", " ");
    $infos_modif['telephone']=str_replace($caractere_a_suppr, "", $infos_modif['telephone']);
    if (!preg_match("#^0[1-8][0-9]{8}$#", $infos_modif['telephone'])) {
        $_SESSION['erreur_modif']="téléphone invalide";
    }

    //modification des infos dans la bdd si pas d'erreur
    if (!isset($_SESSION['erreur_modif'])) {
        $req_modif=$bdd->prepare('UPDATE profil SET nom_prenom=:nom, telephone=:telephone, email=:email WHERE id_profil=:id_profil');
        $req_modif->execute($infos_modif);
    }
}

/* s'il s'agit de modifier les organisations d'appartenance */

// si on arrive via le bouton "ajouter" ou celui "mette à jour mon profil"
if (isset($_POST['ajout']) OR isset($_POST['modif_profil'])){
    if (isset($_POST['nouvelle_orga']) AND preg_match("#\\S#", $_POST['nouvelle_orga'])) {
        $nom_nouv_orga = htmlspecialchars($_POST['nouvelle_orga']);
        // vérification que l'organisation ne soit pas déjà associée à ce profil
        if (!isset($_SESSION['orgas_appartenance']) OR !in_array($nom_nouv_orga, $_SESSION['orgas_appartenance'])) {
            // est-ce que l'organisation renseignée est déjà connue de la bdd ?
            if (!in_array($nom_nouv_orga, $_SESSION['all_orgas'])) {      
                //si non connue : ajout à la table organisation
                $req_ajout_nouv_orga=$bdd->prepare('INSERT INTO organisation (nom_orga) VALUES (?)');
                $req_ajout_nouv_orga->execute(array($nom_nouv_orga));
            }
            // ajout dans la table jonction_profil_organisation pour lié cette nouvelle orga au profil
            $req_ajout_orga=$bdd->prepare('INSERT INTO jonction_profil_organisation (id_profil, id_orga) VALUES (?, (SELECT id_orga FROM organisation WHERE nom_orga = ?))');
            $req_ajout_orga->execute(array($_SESSION['id_profil'],$nom_nouv_orga));
        }
    }
}
// si on arrive via le bouton "X"
if (isset($_POST['suppr'])){
    $req_suppr_orga=$bdd->prepare('DELETE FROM jonction_profil_organisation WHERE id_orga = ?');
    $req_suppr_orga->execute(array($_POST['suppr']));
}

/* s'il s'agit de modifier le mot de passe */
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