<?php

/* il s'agit de récupérer toutes les dispos de mon réseau (somme des dispos des personnes qui me sont liées) pour les prochains jours 
À MODIFIER : DANS UN 1ER TEMPS, LA LOGIQUE DE RESEAU DOIT S'EFFACER POUR AFFICHER LES DISPOS DE TOUTES LES PERSONNES INSCRITES SUR LE SITE*/

// ordre la table disponibilite : id_dispo, id_profil, date_choix, date_0, date_1 ... date_13
// ordre de la table jonction_profil_reseau : id_jonction_pr, id_profil_inf, id_profil_sup
$recup_dispos_reseau=$bdd->prepare('SELECT d.*, DATEDIFF(CURRENT_DATE(),d.date_choix) as intervalle FROM disponibilite as d JOIN jonction_profil_reseau as j ON j.id_profil_inf=d.id_profil WHERE j.id_profil_sup=?');
$recup_dispos_reseau->execute(array($_SESSION['id_profil']));
$dispos_reseau=$recup_dispos_reseau->fetchAll();
// il faut faire 2 requêtes que l'on met dans même tableau, puis il faudra additionner les dispos par date (et non par clef)
$recup_dispos_reseauBIS=$bdd->prepare('SELECT d.*, DATEDIFF(CURRENT_DATE(),d.date_choix) as intervalle FROM disponibilite as d JOIN jonction_profil_reseau as j ON j.id_profil_sup=d.id_profil WHERE j.id_profil_inf=?');
$recup_dispos_reseauBIS->execute(array($_SESSION['id_profil']));
while($dispos_reseauBIS=$recup_dispos_reseauBIS->fetch()){
    if (isset($dispos_reseau)) {
        array_push($dispos_reseau, $dispos_reseauBIS);
    } else {
        $dispos_reseau[]=$dispos_reseauBIS;
    }
}

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

foreach ($dispos_reseau as $dispos_personne) {
    // chaque entrée de $dispos_reseau est un tableau correspondant à une personne du réseau
    for ($j=0; $j < 7 ; $j++) {
            // (il faut regrouper par date les id dispos + leurs dispos + le total de leurs dispos

        $num=$j+$dispos_personne['intervalle'];
        if (isset($dispos_personne['date_'.$num]) AND is_numeric($dispos_personne['date_'.$num])) {
            $place_dispo=$dispos_personne['date_'.$num];
        }else{
            $place_dispo="";
        }
        $dispos_amis['id'][$j][$dispos_personne['id_profil']]=$place_dispo;
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