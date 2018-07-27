<?php

/* Dans un 1er temps, la logique de réseau existe mais n'est pas exclusive : l'affichage des disponibilités concerne toutes les personnes inscrites sur le site, avec un tri dans l'affichage pour faire d'abord apparaître les personnes qui appartiennent aux mêmes organisations + l'affichage par zone géographique avec une carte
Dans un 2e temps, à partir d'un nombre seuil (à définir, mais important) de personnes inscrites, la logique de réseau deviendra exclusive : l'affichage des disponibilités concerne seulement les personne des mêmes groupes d'appartenance (+ toujours affichage par zone géographique), voire plutôt des personnes appartenant au même réseau que moi (réseau modulable en fonction de chaque personne, à l'aide d'une table jonction_profil_reseau déjà créée)*/


// regrouper les places dans $dispos_amis, pour un affichage par date
$dispos_amis=array('dates'=>'','id_et_places'=>'','total'=>'');
$date_dispo=date('d-m-Y');
for ($i=0; $i < 7 ; $i++) {
    // mise en forme de la date pour affichage
    $date_split=date_parse($date_dispo);
    $date_affichee=$date_split['day']."/".$date_split['month'];

    $dispos_amis['dates'][]=$date_affichee;
    $date_dispo=date('d-m-Y', strtotime($date_dispo.' + 1 DAY'));
} 

/* On trie les résultats en mettant en premier les personnes qui appartiennent aux mêmes organisations, pour cela on fait 2 requêtes: d'abord les personnes qui appartiennent aux mêmes orgas (en récupérant leur id), puis toutes les autres dans la table disponibilite (en mettant en critère d'exclusion les id récupérés précédemment)*/

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
// on fait attention à ne pas se compter => ok
// ni compter plusieurs fois les personnes qui appartiennent à plusieurs mêmes orgas que nous => GROUP BY
$recup_dispos_orga=$bdd->prepare('SELECT d.*, DATEDIFF(CURRENT_DATE(),d.date_choix) as intervalle FROM disponibilite AS d JOIN jonction_profil_organisation AS j ON d.id_profil=j.id_profil WHERE '.$critere_rech.' j.id_profil!=? GROUP BY d.id_dispo');
$recup_dispos_orga->execute($tableau_rech);
$dispos_orga=$recup_dispos_orga->fetchAll();

// 2e requete 
$tableau_excl=array();
$critere_excl="";
//$dispos_orga est vide si pas d'ami-es de la même orga disponibles, mais n'est pas vide si on n'a pas renseigné d'orga
if (!empty($dispos_orga)) {
    foreach ($dispos_orga as $entree) {
        $tableau_excl[]=$entree['id_profil'];
        $critere_excl=$critere_excl."id_profil!=? AND ";
    }
} 
$tableau_excl[]=$_SESSION['id_profil'];

$recup_dispos_autre=$bdd->prepare('SELECT *, DATEDIFF(CURRENT_DATE(),date_choix) as intervalle FROM disponibilite WHERE '.$critere_excl.' id_profil!=?');
$recup_dispos_autre->execute($tableau_excl);
$dispos_autre=$recup_dispos_autre->fetchAll();

//on fusionne les 2 tableaux, avec en premier celui des résultats de la recherche par orga
$dispos_reseau=array_merge($dispos_orga,$dispos_autre);
foreach ($dispos_reseau as $entree) {
    for ($j=0; $j < 7 ; $j++) {
        $num=$j+$entree['intervalle'];
        if (isset($entree['date_'.$num]) AND is_numeric($entree['date_'.$num])) {
            $place_dispo=$entree['date_'.$num];
        }else{
            $place_dispo="";
        }
        //on met par id le nombre de place, pour affichage par carte peut-être
        $dispos_amis['id_et_places'][$j][$entree['id_profil']]=$place_dispo;
        if (isset($dispos_amis['total'][$j])) {
            // si d'autres personnes ont aussi des dispos à cette date, on les rajoute
            $dispos_amis['total'][$j]=$dispos_amis['total'][$j]+$place_dispo;
        } else {
            // sinon on les ajoute simplement
            $dispos_amis['total'][$j]=$place_dispo;
        }
    } 
}
?>