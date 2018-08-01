<?php
//lancer un nettoyage (voir quand et où le faire)
$req_nettoyage=$bdd->query('DELETE FROM jonction_profil_dispo WHERE DATEDIFF(CURRENT_DATE(),date_debut)>=nb_jours');

/* Dans un 1er temps, la logique de réseau existe mais n'est pas exclusive : l'affichage des disponibilités concerne toutes les personnes inscrites sur le site, avec un tri dans l'affichage pour faire d'abord apparaître les personnes qui appartiennent aux mêmes organisations + l'affichage par zone géographique avec une carte
Dans un 2e temps, à partir d'un nombre seuil (à définir, mais important) de personnes inscrites, la logique de réseau deviendra exclusive : l'affichage des disponibilités concerne seulement les personne des mêmes groupes d'appartenance (+ toujours affichage par zone géographique), voire plutôt des personnes appartenant au même réseau que moi (réseau modulable en fonction de chaque personne, à l'aide d'une table jonction_profil_reseau déjà créée)*/
$dispos_reseau=array();

if (isset($_GET['date_choisie']) AND !empty($_GET['date_choisie'])) {
    $date_choisie=htmlspecialchars($_GET['date_choisie']);
    $date_split=explode("/", $date_choisie);
    $date_choisie=$date_split[2]."-".$date_split[1]."-".$date_split[0];
    /* On trie les résultats en mettant en premier les personnes qui appartiennent aux mêmes organisations, pour cela on fait 2 requêtes: d'abord les personnes qui appartiennent aux mêmes orgas (en récupérant leur id), puis toutes les autres dans la table jonction_profil_dispo (en mettant en critère d'exclusion les id récupérés précédemment)*/
    // 1ere requête
    $critere_rech="";
    $tableau_rech=array();
    if (isset($_SESSION['orgas_appartenance'])){
        // mes orgas d'appartenance sont en clef de $_SESSION['orgas_appartenance'] 
        $tableau_rech=array_keys($_SESSION['orgas_appartenance']);
        $critere_rech="(";
        for ($i=0; $i < count($_SESSION['orgas_appartenance']) ; $i++) { 
            $critere_rech = $critere_rech."o.id_orga=? OR ";
        }
        $critere_rech=substr($critere_rech, 0, -3);
        $critere_rech=$critere_rech.") AND";
    }
    $tableau_rech[]=$_SESSION['id_profil'];
    $tableau_rech[]=$date_choisie;

    // on fait attention à ne pas se compter => ok
    // ni compter plusieurs fois les personnes qui appartiennent à plusieurs mêmes orgas que nous => GROUP BY
    // on sélectionne que les dispos qui commencent à la date choisie ou dont l'intervalle écoulé entre date_debut et date_choisie est inférieur à nb_jours 

    $recup_dispos_orga=$bdd->prepare('SELECT d.* FROM jonction_profil_dispo AS d JOIN jonction_profil_organisation AS o ON d.id_profil=o.id_profil WHERE ('.$critere_rech.' o.id_profil!=?) AND (DATEDIFF(?,d.date_debut) BETWEEN 0 AND d.nb_jours-1) GROUP BY d.id_jonction_pd');
    $recup_dispos_orga->execute($tableau_rech);    
    $reponse_dispos_orga=$recup_dispos_orga->fetchAll();

    // 2e requete 
    $tableau_excl=array();
    $critere_excl="";
    //$dispos_orga est vide si pas d'ami-es de la même orga disponibles, mais n'est pas vide si on n'a pas renseigné d'orga
    if (!empty($reponse_dispos_orga)) {
        foreach ($reponse_dispos_orga as $entree) {
            $tableau_excl[]=$entree['id_profil'];
            $critere_excl=$critere_excl."id_profil!=? AND ";
        }
    } 
    $tableau_excl[]=$_SESSION['id_profil'];
    $tableau_excl[]=$date_choisie;

    $recup_dispos_autre=$bdd->prepare('SELECT * FROM jonction_profil_dispo WHERE '.$critere_excl.' id_profil!=? AND (DATEDIFF(?,date_debut) BETWEEN 0 AND nb_jours-1)');
    $recup_dispos_autre->execute($tableau_excl);
    $reponse_dispos_autre=$recup_dispos_autre->fetchAll();

    //on fusionne les 2 tableaux, avec en premier celui des résultats de la recherche par orga
    $reponse_dispos_reseau=array_merge($reponse_dispos_orga,$reponse_dispos_autre);
    foreach ($reponse_dispos_reseau as $entree) {
        // pour chaque résultat (chaque dispo), on met dans un tableau: id_profil, date_debut, date_fin, nb_jours, nb_places
        $date_debut=date('d/m', strtotime($entree['date_debut']));
        $date_fin=date('d/m', strtotime($entree['date_debut'].'+'.$entree['nb_jours'].' DAY'));
        $dispos_reseau[$entree['id_jonction_pd']]=array('date_debut'=>$date_debut,'date_fin'=>$date_fin, 'nb_jours'=>$entree['nb_jours'], 'nb_places'=>$entree['nb_places'],'id_profil'=>$entree['id_profil']);
    }
}

// si on arrive via le bouton "X"
if (isset($_GET['suppr_dispo'])){
    $id_jonction_pd=htmlspecialchars($_GET['suppr_dispo']);
    $req_suppr_dispo=$bdd->prepare('DELETE FROM jonction_profil_dispo WHERE id_jonction_pd = ?');
    $req_suppr_dispo->execute(array($id_jonction_pd));
}
?>