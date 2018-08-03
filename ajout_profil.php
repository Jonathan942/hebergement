<?php session_start();
include_once('connexion_sql.php');

/* est-ce qu'il ne faudrait pas grouper cette page avec modification_profil.php (et elle-même avec modification_mdp.php) car les tests sont les mêmes 
En tout cas, faire des formules */

if (isset($_POST['ajout_infos'])){    
    $infos_ajout=array('nom'=>'','telephone'=>'','email'=>'', 'mdp'=>'');
    foreach ($infos_ajout as $cle=> &$element){
        if (empty($_POST[''.$cle])){
            $_SESSION['erreur_ajout']="Veuillez renseigner tous les champs pour inscrire une nouvelle personne";
        } else {
            $element=htmlspecialchars($_POST[''.$cle]);
        }
    }

    // vérification format email
    if (!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $infos_ajout['email'])){
        $_SESSION['erreur_ajout']="email invalide";
    } else {
        $infos_ajout['email']=strtolower($infos_ajout['email']);
        //vérification que l'email ne soit pas déjà enregistré dans une autre entrée de la bdd
        $req_verif=$bdd->prepare('SELECT COUNT(*) FROM profil WHERE email=?');
        $req_verif->execute(array($infos_ajout['email']));
        $verif=$req_verif->fetch();
        if ($verif['nb_email']!=0) {
            $_SESSION['erreur_ajout']="cet email correspond à une personne déjà inscrite";
        }
    }
    // vérification format téléphone
    $caractere_a_suppr=array("-", ".", " ");
    $infos_ajout['telephone']=str_replace($caractere_a_suppr, "", $infos_ajout['telephone']);
    if (!preg_match("#^0[1-8][0-9]{8}$#", $infos_ajout['telephone'])) {
        $_SESSION['erreur_ajout']="téléphone invalide";
    }

    // vérification mot de passe
    if (empty($_POST['mdp2'])){
        $_SESSION['erreur_ajout']="Veuillez confirmer le mot de passe";
    } else {
        $mdp2=htmlspecialchars($_POST['mdp2']);
        if($infos_ajout['mdp']!=$mdp2){
        $_SESSION['erreur_ajout']="Les 2 mots de passe ne sont pas identiques";
        } else {
            //hashage du mdp
            $infos_ajout['mdp'] = password_hash($infos_ajout['mdp'], PASSWORD_DEFAULT);
        }
    }
}

//insertion des infos dans la bdd si pas d'erreur
if (!isset($_SESSION['erreur_ajout'])) {
    $req_insert=$bdd->prepare('INSERT INTO profil (nom_prenom, telephone, email, mdp) VALUES (:nom,:telephone,:email,:mdp)');
    $req_insert->execute($infos_ajout);
}

header('Location: /www/hebergement');
exit();
?>