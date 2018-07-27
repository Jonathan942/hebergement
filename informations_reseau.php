<?php

/* Dans un 1er temps, la logique de réseau existe mais n'est pas exclusive : l'affichage des disponibilités concerne toutes les personnes inscrites sur le site, avec un tri dans l'affichage pour faire d'abord apparaître les personnes qui appartiennent aux mêmes organisations + l'affichage par zone géographique avec une carte
Dans un 2e temps, à partir d'un nombre seuil (à définir, mais important) de personnes inscrites, la logique de réseau deviendra exclusive : l'affichage des disponibilités concerne seulement les personne des mêmes groupes d'appartenance (+ toujours affichage par zone géographique), voire plutôt des personnes appartenant au même réseau que moi (réseau modulable en fonction de chaque personne, à l'aide d'une table jonction_profil_reseau déjà créée)*/


// regrouper les places dans $dispos_amis, pour un affichage par date
$dispos_amis=array('dates'=>'','id'=>'','total'=>'');
$date_dispo=date('d-m-Y');
for ($i=0; $i < 7 ; $i++) {
    // mise en forme de la date pour affichage
    $date_split=date_parse($date_dispo);
    $date_affichee=$date_split['day']."/".$date_split['month'];

    $dispos_amis['dates'][]=$date_affichee;
    $date_dispo=date('d-m-Y', strtotime($date_dispo.' + 1 DAY'));
} 

//il faut trier les résultats en mettant en premier les personnes qui appartiennent aux mêmes organisations
// on va faire 3 requêtes, d'abord les personnes qui appartiennent aux mêmes orgas, puis celles qui non, puis celles qui n'appartiennent à aucune orga ; on met les 3 tableaux-résultats à la suite l'un de l'autre
// sinon on fait 2 req : d'abord les personnes qui appartiennent aux mêmes orgas (en récupérant leur id), puis toutes les autres dans la table disponibilite (en mettant en critère d'exclusion les id récupérés précédemment)
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
// on fait attention à ne pas se compter, ni compter plusieurs fois les personnes qui appartiennent à plusieurs mêmes orgas que nous
$recup_dispos_reseau=$bdd->prepare('SELECT d.*, DATEDIFF(CURRENT_DATE(),d.date_choix) as intervalle FROM disponibilite AS d JOIN jonction_profil_organisation AS j ON d.id_profil=j.id_profil WHERE '.$critere_rech.' j.id_profil!=?');
$recup_dispos_reseau->execute($tableau_rech);
//PROBLEME A RESOUDRE : ça ne compte pas celleux qui ne sont pas dans table jonction_profil_organisation
while ($dispos_reseau=$recup_dispos_reseau->fetch()) {
    for ($j=0; $j < 7 ; $j++) {
        $num=$j+$dispos_reseau['intervalle'];
        if (isset($dispos_reseau['date_'.$num]) AND is_numeric($dispos_reseau['date_'.$num])) {
            $place_dispo=$dispos_reseau['date_'.$num];
        }else{
            $place_dispo="";
        }
        /* Si on fait un affichage des disponibilités qui est borné au jour voulu, on a besoin (pour éviter une autre req dans la même table) de stocker dès à présent le nombre de place dispo pour ce jour donné. Mais si on veut un affichage des disponibilités (en détail, après un clic sur une date), qui affiche les autres disponibilités de ce même id, alors pas besoin*/
        $dispos_amis['id'][$j][$dispos_reseau['id_profil']]=$place_dispo;
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