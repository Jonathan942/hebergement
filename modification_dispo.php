<?php session_start();
include_once('connexion_sql.php');
if (isset($_POST['ajout_dispo'])){
    $ajd=date('Y-m-d');
    $date_debut=htmlspecialchars($_POST['date_debut']);
    $date_split=explode("/", $date_debut);
    $date_debut=$date_split[2]."-".$date_split[1]."-".$date_split[0];
    if (!empty($_POST['date_fin'])){
        $date_fin=htmlspecialchars($_POST['date_fin']);
        $date_split=explode("/", $date_fin);
        $date_fin=$date_split[2]."-".$date_split[1]."-".$date_split[0];
    } else {
        $date_fin=date('Y-m-d', strtotime($date_debut.' + 1 DAY'));
    }

    //vérification que la date_début est >= à ajourd'hui et que date_fin >= à date_debut   
    if ((strtotime($date_debut)-strtotime($ajd))<0){
        $_SESSION['erreur_dispo']="la date choisie est déjà passée";
    } else {
        $nb_nuits=(strtotime($date_fin)-strtotime($date_debut))/(60*60*24);
        if ($nb_nuits<1) {
            $_SESSION['erreur_dispo']="la date de fin choisie est antérieure à la date de début";  
        } else {
            $nb_places=htmlspecialchars($_POST['nb_places']);
            if (!is_numeric($nb_places)) {
                $nb_places=0;
            }
            $req_insert_dispo=$bdd->prepare('INSERT INTO jonction_profil_dispo (id_profil, date_debut, nb_nuits, nb_places) VALUES (?, ?, ?, ?)');
            $req_insert_dispo->execute(array($_SESSION['id_profil'], $date_debut, $nb_nuits, $nb_places));
        }
    }
} 

// si on arrive via le bouton "X"
if (isset($_POST['suppr_dispo'])){
    $id_jonction_pd=htmlspecialchars($_POST['suppr_dispo']);
    $req_suppr_dispo=$bdd->prepare('DELETE FROM jonction_profil_dispo WHERE id_jonction_pd = ?');
    $req_suppr_dispo->execute(array($id_jonction_pd));
}

header('Location: /www/hebergement');
exit();

?>