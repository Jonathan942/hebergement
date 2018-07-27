<?php session_start(); if (isset($_POST['deconnection'])) unset($_SESSION['id_profil']); ?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Site permettant la mise en relation de personnes concernées par l'hébergement solidaire sur Marseille et ses environs"/>
	<!-- les metas suivantes sont-elles utiles? ou trop violente (pas d'indexation du tout?)-->
	<META NAME="Robots" CONTENT="none">
	<META NAME="GOOGLEBOT" CONTENT="NOARCHIVE">
	<META http-equiv="Pragma" CONTENT="no-cache">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">    
    <!-- pour affichage calendrier pour disponibilités -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">


    <title>Hébergement solidaire 13</title>
  </head>
  <body>

    <!-- si pas ou mal identifié·e ... -->
    <?php if(!isset($_SESSION['id_profil'])) {
        // ... affichage de l'erreur (à changer pour intégrer dans le html) ... 
        if (isset($_SESSION['erreur_identif'])){
            echo $_SESSION['erreur_identif']; 
            unset($_SESSION['erreur_identif']);
        }?>
        <!-- ... et du formulaire d'identification -->
        <p>Veuillez entrer votre adresse mail et prénom pour accéder à la base des personnes hébergeantes :</p>
        <div class="form-group">
            <form action="identification.php" method="post" class>
                <input type="email" name="email" id="email" placeholder="adresse@email.fr" class="form-control" size="30" maxlength="30" required/>
                <input type="password" name="mdp" id="mdp" class="form-control" placeholder="Mot de passe (par défaut votre prénom)" size="30" maxlength="45" required="" />
                <button type="submit" name="valider" class="btn">Valider</button>
                <span class="help-block">Vous avez oublié votre mot de passe ? Contactez-nous à l'adresse : a d m i n @ . . .</span>
            </form>
        </div>

        <p>Pour des raisons de confidentialité, l'inscription se fait par cooptation assurant ainsi un lien de confiance entre les membres de cette base de contacts. Si vous connaissez une personne participante, demandez-lui de vous inscrire. Autrement, rapprochez-vous d'une organisation impliquée dans l'hébergement solidaire.</p>
        
    <!-- si bien identifié·e : affichage de la page avec le profil concerné -->
    <?php } else {
        include_once('informations_profil.php'); 
        include_once('informations_dispo.php');
        include_once('informations_reseau.php'); ?>            
        
        <h1>Bienvenu·e <?php echo $infos['nom_prenom'];?></h1>
        
        <!-- bouton déconnection à mettre en haut à droite de la page -->
        <div class="">
            <form action="" method="post">
                <input type="submit" class="btn" value="Déconnection" name="deconnection" />
            </form>
            <p>Consulter la <a href="">charte</a> de Réseau Hospitalité</p>
        </div>

        <h2>Vos informations personnelles : </h2>
        <button class="btn" data-toggle="collapse" data-target="#modif_infos">Modifier mes données de contact</button>
        <form method="post" action="modification_profil.php" class="collapse" id="modif_infos">
            <fieldset>
                <div class="form-group">
                    <label for="nom" class="">Votre nom ou pseudo :</label>
                    <input type="text" name="nom" id="nom" class="form-control" placeholder="" value="<?php echo $infos['nom_prenom']; ?>" maxlength="45" required=""/>
                </div>
                <div class="form-group">
                    <label for="email" class="">Votre email :</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="" value="<?php echo $infos['email']; ?>" maxlength="45" required=""/>
                </div>
                <div class="form-group">
                    <label for="telephone" class="">Votre numéro français de téléphone :</label>
                    <input type="tel" name="telephone" id="telephone" class="form-control" placeholder="" value="<?php echo $infos['telephone']; ?>" maxlength="45" required=""/>
                </div>
                <button type="submit" name="modif_profil" class="btn">Mettre à jour mon profil</button>
            </fieldset>
        </form>
        <button class="btn" data-toggle="collapse" data-target="#modif_orga">Modifier mes organisations</button>
        <form method="post" action="modification_profil.php" class="collapse" id="modif_orga">
            <div class="form-group">
                <label for="nouvelle_orga" class="">Vos organisations :</label>
                <div class="input-group">
                    <input list="orgas" type="text" id="nouvelle_orga" name="nouvelle_orga" class="form-control" placeholder="Ajouter une nouvelle organisation" maxlength="45">
                    <datalist id="orgas">
                    <!-- installer safari pour voir comment apparaît le select car safari ne prend pas en charge datalist / puis voir si différence dans le traitement modification ? -->
                    <select name="choix_orga" id="choix_orga">
                    <?php foreach ($_SESSION['all_orgas'] as $nom_orga) {?>
                        <option value="<?php echo $nom_orga;?>"></option>
                    <?php } ?>
                    </select>
                    </datalist>
                    <div class="input-group-btn">
                        <button type="submit" class="btn" name="ajout">Ajouter</button>
                    </div>
                </div>

                <?php if (isset($_SESSION['orgas_appartenance'])) {
                foreach ($_SESSION['orgas_appartenance'] as $id_orga_appartenance => $nom_orga_appartenance) { ?>
                <div class="input-group">
                    <p class="form-control"><?php echo $nom_orga_appartenance;?></p>
                    <div class="input-group-btn">
                        <button type="submit" name="suppr" class="btn" value="<?php echo $id_orga_appartenance; ?>">&times;</button>
                    </div>
                </div>                                
                <?php }
                } ?>
            </div>
            <?php
            // affichage de l'éventuelle erreur lors du traitement de la modification (à intégrer dans le html)
            if (isset($_SESSION['erreur_modif'])){
                echo $_SESSION['erreur_modif'];
                unset($_SESSION['erreur_modif']);
            }  ?>
        </form>
        <button class="btn" data-toggle="collapse" data-target="#modif_mdp">Modifier mon mot de passe</button>
        <form method="post" action="modification_profil.php" class="collapse" id="modif_mdp">
            <div class="form-group">
                <label for="mdp" class="">Votre mot de passe :</label>
                <input type="password" name="mdp" id="mdp" class="form-control" placeholder="Nouveau mot de passe" maxlength="45"/>
                <input type="password" name="mdp2" id="confirm_mdp" class="form-control" placeholder="Confirmer nouveau mot de passe" maxlength="45" onblur="verifMdp(this, 'mdp')"/>
                <?php
                // affichage de l'éventuelle erreur lors du traitement de la modification (à intégrer dans le html)
                if (isset($_SESSION['erreur_mdp'])){
                    echo $_SESSION['erreur_mdp'];
                    unset($_SESSION['erreur_mdp']);
                }  ?>
                <button type="submit" name="modif_mdp" class="btn">Changer mon mot de passe</button>
            </div>
        </form>
        <button class="btn btn-primary" data-toggle="collapse" data-target="#mes_dispos">Disponibilités pour héberger</button>
        <form action="modification_dispo.php" method="post" class="collapse" id="mes_dispos">
            <fieldset>

                <?php 
                if (isset($mes_dispos)) {
                    $i=0;
                    foreach ($mes_dispos as $date_dispo => $place_dispo) { ?>
                        <div class="input-group">
                            <label for="mes_places"><?php echo $date_dispo." :"; ?></label>
                            <input class="form-control" type="number" id="mes_places" min="0" max="10" step="1" name="date_<?php echo $i; ?>" value="<?php echo $place_dispo; ?>">
                        </div>
                    <?php 
                    $i++; }
                }?>
                <button class="btn" type="submit" name="modif_dispos">Mettre à jour mes disponibilités</button>
            </fieldset>
        </form>        

        <h2>Les disponibilités dans mon réseau :</h2>
        <form action="modification_reseau.php" method="post"> 
            <fieldset>
                <div class="col-sm-3">
                    <div class="form-group">
                        <div class="input-group date" data-provide="datepicker">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                            <input type="tex"' class="form-control" placeholder="Date">
                            <div class="input-group-btn">
                                <button class="btn btn-default" type="submit">Voir</button>
                            </div>
                        </div>
                    </div>
                </div>
                    <?php 
                // le tableau $dispo_amis regroupe toutes les informations concernant les disponibilités (totales ou par id) par date, dans mon réseau
                $j=0;
                foreach ($dispos_amis['dates'] as $date_dispo) { ?>
                    <div class="input-group">
                        <!-- lien qui permet d'ouvrir une fenêtre avec le détail -->
                        <a href="" data-toggle="modal" data-target="#detail_<?php echo $j;?>"><?php echo $date_dispo." : ";?></a>
                        <input class="form-control" type="text" value="<?php echo $dispos_amis['total'][$j];?>" disabled>
                        
                        <!-- fenêtre qui s'ouvre avec le détail des dispos pour le jour cliqué.
                        Soit on laisse cette modal ainsi, dans la boucle foreach, et on en crée autant qu'il y a de dates à cliquer ; soit on en fait une qui va chercher les détails via une autre page avec un GET -->
                        <div class="modal fade" id="detail_<?php echo $j;?>" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h4 class="modal-title">Détail des disponibilités pour le <?php echo $date_dispo;?></h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                  </div>
                                  <div class="modal-body">
                                    <p>
                                        <!-- à compléter -->
                                    </p>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php 
                $j++; } ?>
            </fieldset>
        </form>

        <h2>Pour inscrire une nouvelle personne : </h2>
        <button class="btn" data-toggle="collapse" data-target="#ajout">Parrainer une nouvelle personne</button>
        <form method="post" action="ajout_profil.php" class="collapse" id="ajout">
            <fieldset>
                <div class="form-group">
                    <label for="nouv_nom" class="">Son nom ou pseudo :</label>
                    <input type="text" name="nom" id="nouv_nom" class="form-control" placeholder="" maxlength="45" required=""/>
                </div>
                <div class="form-group">
                    <label for="nouv_email" class="">Son email :</label>
                    <input type="email" name="email" id="nouv_email" class="form-control" placeholder="" maxlength="45" required=""/>
                </div>
                <div class="form-group">
                    <label for="nouv_telephone" class="">Son numéro français de téléphone :</label>
                    <input type="tel" name="telephone" id="nouv_telephone" class="form-control" placeholder="" maxlength="45" required=""/>
                </div>
                
                <div class="form-group">
                    <label for="nouv_mdp" class="">Définir son mot de passe (modifiable par la suite):</label>
                    <input type="password" name="mdp" id="nouv_mdp" class="form-control" placeholder="mot de passe" maxlength="45" required/>
                    <input type="password" name="mdp2" id="confirm_nouv_mdp" class="form-control" placeholder="Confirmer mot de passe" maxlength="45" onblur="verifMdp(this, 'nouv_mdp')" required/>
                </div>
                <?php 
                // affichage de l'éventuelle erreur lors du traitement de la modification (à intégrer dans le html)
                if (isset($_SESSION['erreur_ajout'])){
                    echo $_SESSION['erreur_ajout']; 
                    unset($_SESSION['erreur_ajout']);
                } ?>
                <button type="submit" name="ajout_infos" class="btn">Inscrire cette personne</button>
            </fieldset>           
        </form>
      
    <?php } ?>
    
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <!-- pour affichage calendrier pour disponibilités -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/locales/bootstrap-datepicker.fr.min.js"></script>

    <script type="text/javascript">
        function surligne(champ, erreur) {
            if(erreur)
                champ.style.backgroundColor = "#FFD9CF";
            else
                champ.style.backgroundColor = "";
        }
         
        function verifMdp(champ, nom) {
            if(champ.value != document.getElementById(nom).value) {
                surligne(champ, true);
                return false;
            } else {
                surligne(champ, false);
                return true;
            }
        }

        $(function () {
            $.fn.datepicker.defaults.format = "dd/mm/yyyy";
            $.fn.datepicker.defaults.language = "fr";
            $.fn.datepicker.defaults.todayHighlight = "true";
        });
    </script>
  </body>
</html>