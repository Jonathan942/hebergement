<?php
//lancer un nettoyage (voir quand et où le faire)
$req_nettoyage=$bdd->query('DELETE FROM dispos_hebergement WHERE DATEDIFF(CURRENT_DATE(),date_debut)>=nb_nuits');

/* Dans un 1er temps, la logique de réseau existe mais n'est pas exclusive : l'affichage des disponibilités concerne toutes les personnes inscrites sur le site, avec un tri dans l'affichage pour faire d'abord apparaître les personnes qui appartiennent aux mêmes organisations + l'affichage par zone géographique avec une carte
Dans un 2e temps, à partir d'un nombre seuil (à définir, mais important) de personnes inscrites, la logique de réseau deviendra exclusive : l'affichage des disponibilités concerne seulement les personne des mêmes groupes d'appartenance (+ tounuits affichage par zone géographique), voire plutôt des personnes appartenant au même réseau que moi (réseau modulable en fonction de chaque personne, à l'aide d'une table jonction_profil_reseau déjà créée)*/

// si on arrive via le bouton "X"
if (isset($_POST['suppr_dispo'])){
    $id_dispos=htmlspecialchars($_POST['suppr_dispo']);
    $req_suppr_dispo=$bdd->prepare('DELETE FROM dispos_hebergement WHERE id_dispos = ?');
    $req_suppr_dispo->execute(array($id_dispos));
}

if (isset($_GET['date_choisie']) AND !empty($_GET['date_choisie'])) {
    $date_choisie=htmlspecialchars($_GET['date_choisie']);
    $date_split=explode("/", $date_choisie);
    $date_choisie=$date_split[2]."-".$date_split[1]."-".$date_split[0];
    /* On trie les résultats en mettant en premier les personnes qui appartiennent aux mêmes organisations, pour cela on fait 2 requêtes: d'abord les personnes qui appartiennent aux mêmes orgas (en récupérant leur id), puis toutes les autres dans la table dispos (en mettant en critère d'exclusion les id récupérés précédemment)*/
    // 1ere requête
    $critere_rech="";
    $tableau_rech=array();
    if (isset($_SESSION['orgas_appartenance'])){
        // mes orgas d'appartenance sont en clef de $_SESSION['orgas_appartenance'] 
        $tableau_rech=array_keys($_SESSION['orgas_appartenance']);
        $critere_rech="(";
        for ($i=0; $i < count($_SESSION['orgas_appartenance']) ; $i++) { 
            $critere_rech = $critere_rech."j.id_orga=? OR ";
        }
        $critere_rech=substr($critere_rech, 0, -3);
        $critere_rech=$critere_rech.") AND";
    }
    $tableau_rech[]=$_SESSION['id_profil'];
    $tableau_rech[]=$date_choisie;

    // on fait attention à ne pas se compter => ok
    // ni compter plusieurs fois les personnes qui appartiennent à plusieurs mêmes orgas que nous => GROUP BY
    // on va compter plusieurs fois les personnes appartenant aux mêmes orgas que nous, et après on fera le tri via id_dispos
    // pas besoin de refaire un join dans la table organisation (pour chercher le nom des orgas) car toutes les infos sont dans $_SESSION['all_orgas']
    // on sélectionne que les dispos qui commencent à la date choisie ou dont l'intervalle écoulé entre date_debut et date_choisie est inférieur à nb_nuits 

    $recup_dispos_orga=$bdd->prepare('SELECT d.*, j.id_orga, p.nom_prenom, p.telephone, p.email, i.description, i.preference FROM dispos_hebergement AS d JOIN jonction_profil_organisation AS j ON d.id_profil=j.id_profil JOIN profil as p ON d.id_profil=p.id_profil JOIN infos_hebergement as i ON d.id_profil=i.id_profil WHERE ('.$critere_rech.' j.id_profil!=?) AND (DATEDIFF(?,d.date_debut) BETWEEN 0 AND d.nb_nuits-1)');
    $recup_dispos_orga->execute($tableau_rech);    
    $reponse_dispos_orga=$recup_dispos_orga->fetchAll();
    // 2e requete 
    $tableau_excl=array();
    $critere_excl="";
    //$dispos_orga est vide si pas d'ami-es de la même orga disponibles, mais n'est pas vide si on n'a pas renseigné d'orga
    if (!empty($reponse_dispos_orga)) {
        foreach ($reponse_dispos_orga as $entree) {
            $tableau_excl[]=$entree['id_profil'];
            $critere_excl=$critere_excl."d.id_profil!=? AND ";
        }
    } 
    $tableau_excl[]=$_SESSION['id_profil'];
    $tableau_excl[]=$date_choisie;

    $recup_dispos_autre=$bdd->prepare('SELECT d.*, j.id_orga, p.nom_prenom, p.telephone, p.email, i.description, i.preference FROM dispos_hebergement as d LEFT JOIN jonction_profil_organisation AS j ON d.id_profil=j.id_profil JOIN profil as p ON d.id_profil=p.id_profil JOIN infos_hebergement as i ON d.id_profil=i.id_profil WHERE '.$critere_excl.' d.id_profil!=? AND (DATEDIFF(?,d.date_debut) BETWEEN 0 AND d.nb_nuits-1)');
    $recup_dispos_autre->execute($tableau_excl);
    $reponse_dispos_autre=$recup_dispos_autre->fetchAll();

    //on fusionne les 2 tableaux, avec en premier celui des résultats de la recherche par orga
    $reponse_dispos_reseau=array_merge($reponse_dispos_orga,$reponse_dispos_autre);
    $dispos_reseau=array();
    foreach ($reponse_dispos_reseau as $entree) {
        // pour chaque résultat (chaque dispo), on met dans un tableau: id_profil, date_debut, date_fin, nb_nuits, nb_places, id_profil, nom_prenom, telephone, email, description et preference
        $date_debut=date('d/m', strtotime($entree['date_debut']));
        $date_fin=date('d/m', strtotime($entree['date_debut'].'+'.$entree['nb_nuits'].' DAY'));
        if (!empty($entree['id_orga'])) {
            $nom_orga=$_SESSION['all_orgas'][$entree['id_orga']];
        } else {
            $nom_orga="sans organisation";
        }
        if (!isset($dispos_reseau[$entree['id_dispos']])) {
            $dispos_reseau[$entree['id_dispos']]=array('date_debut'=>$date_debut,'date_fin'=>$date_fin, 'nb_nuits'=>$entree['nb_nuits'], 'nb_places'=>$entree['nb_places'],'id_profil'=>$entree['id_profil'], 'nom_orga'=>$nom_orga, 'nom_prenom'=>$entree['nom_prenom'], 'telephone'=>$entree['telephone'], 'email'=>$entree['email'], 'description'=>$entree['description'], 'preference'=>$entree['preference']);
        } else {
            $dispos_reseau[$entree['id_dispos']]['nom_orga']= $dispos_reseau[$entree['id_dispos']]['nom_orga']." et ".$nom_orga;
        }
    }
}
?>