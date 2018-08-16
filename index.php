<?php session_start(); if (isset($_POST['deconnection'])) unset($_SESSION['id_profil']); 
if(isset($_GET)){
    foreach ($_GET as $cle => $valeur) {
        unset($_GET[$cle]);
        $cle_safe=htmlspecialchars($cle);
        $valeur_safe=htmlspecialchars($valeur);
        $_GET[$cle_safe]=$valeur_safe;
    }
}
?>

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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">    
    <!-- pour affichage calendrier pour disponibilités -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">


    <title>Hébergement solidaire 13</title>
  </head>
  <body>
    <div class="container">
    <!-- si pas ou mal identifié·e ... -->
    <?php if(!isset($_SESSION['id_profil'])) { ?>
        <!-- ... et du formulaire d'identification -->
        <p>Veuillez entrer votre adresse mail et prénom pour accéder à la base des personnes hébergeantes :</p>

        <form action="identification.php" method="post" class="form-horizontal">
            <div class="form-group <?php if (isset($_GET['identification']) AND preg_match("#email#",$_GET['identification'])) echo 'has-error';?>">
                <label class="control-label col-sm-2" for="email"><?php if (isset($_GET['identification']) AND preg_match("#email#",$_GET['identification'])) echo $_GET['identification']; else echo 'Email :';?></label>
                <div class="col-sm-10">
                    <input type="email" name="email" id="email" placeholder="adresse@email.fr" class="form-control" size="30" maxlength="30" required/>
                </div>
            </div>
            <div class="form-group <?php if (isset($_GET['identification']) AND preg_match("#passe#",$_GET['identification'])) echo 'has-error';?>">
                <label class="control-label col-sm-2" for="password"><?php if (isset($_GET['identification']) AND preg_match("#passe#",$_GET['identification'])) echo $_GET['identification']; else echo 'Mot de passe :';?></label>
                <div class="col-sm-10">
                    <input type="password" name="mdp" id="mdp" class="form-control" placeholder="Par défaut votre prénom" size="30" maxlength="45" required="" />
                </div>
            </div>
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" name="valider" class="btn right">Valider</button>
                <span class="help-block">Vous avez oublié votre mot de passe ? Contactez-nous à l'adresse : a d m i n @ . . .</span>
            </div>
        </form>


        <p>Pour des raisons de confidentialité, l'inscription se fait par cooptation assurant ainsi un lien de confiance entre les membres de cette base de contacts. Si vous connaissez une personne participante, demandez-lui de vous inscrire. Autrement, rapprochez-vous d'une organisation impliquée dans l'hébergement solidaire.</p>
        
    <!-- si bien identifié·e : affichage de la page avec le profil concerné -->
    <?php } else {
        include_once('informations_profil.php'); 
        include_once('informations_dispo.php');
        include('informations_reseau.php');           
        
        if(isset($_GET['succes'])) {?>
        <div class="modal fade" id="fenetre">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <p><?php echo $_GET['succes'];?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php }?>
        <h1>Bienvenu·e <?php echo $infos['nom_prenom'];?></h1>
        
        <!-- bouton déconnection à mettre en haut à droite de la page -->
        <div class="">
            <form action="" method="post">
                <input type="submit" class="btn" value="Déconnection" name="deconnection" />
            </form>
            <p>Consulter la <a href="">charte</a> de Réseau Hospitalité</p>
        </div>

        <h2>Vos informations : </h2>
        <button class="btn btn-primary" data-toggle="collapse" data-target="#modif_infos">Données de contact</button>
        <form method="post" action="modification_profil.php" class="collapse <?php if (isset($_GET['modification'])) echo 'in';?> form-horizontal" id="modif_infos">
            <fieldset>
                <div class="form-group <?php if (isset($_GET['modification']) AND preg_match("#nom#",$_GET['modification'])) echo 'has-error';?>">
                    <label for="nom" class="control-label col-sm-2"><?php if (isset($_GET['modification']) AND preg_match("#nom#",$_GET['modification'])) echo $_GET['modification']; else echo 'Votre nom ou pseudo : ';?></label>
                    <div class="col-sm-10">
                        <input type="text" name="nom" id="nom" class="form-control" placeholder="" value="<?php echo $infos['nom_prenom']; ?>" maxlength="45"/>
                    </div>
                </div>
                <div class="form-group <?php if (isset($_GET['modification']) AND preg_match("#email#",$_GET['modification'])) echo 'has-error';?>">
                    <label for="email" class="control-label col-sm-2"><?php if (isset($_GET['modification']) AND preg_match("#email#",$_GET['modification'])) echo $_GET['modification']; else echo 'Votre email : ';?></label>
                    <div class="col-sm-10">
                        <input type="email" name="email" id="email" class="form-control" placeholder="" value="<?php echo $infos['email']; ?>" maxlength="45"/>
                    </div>
                </div>
                <div class="form-group <?php if (isset($_GET['modification']) AND preg_match("#téléphone#",$_GET['modification'])) echo 'has-error';?>">
                    <label for="telephone" class="control-label col-sm-2"><?php if (isset($_GET['modification']) AND preg_match("#téléphone#",$_GET['modification'])) echo $_GET['modification']; else echo 'Votre téléphone (numéro français) : ';?></label>
                    <div class="col-sm-10">
                        <input type="tel" name="telephone" id="telephone" class="form-control" placeholder="" value="<?php echo $infos['telephone']; ?>" maxlength="45"/>
                    </div>
                </div>

                <div class="form-group">
                    <label for="nouvelle_orga" class="control-label col-sm-2">Vos organisations :</label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <input list="orgas" type="text" id="nouvelle_orga" name="nouvelle_orga" class="form-control" placeholder="Ajouter une nouvelle organisation" maxlength="45">
                            <datalist id="orgas">
                            <!-- installer safari pour voir comment apparaît le select car safari ne prend pas en charge datalist / puis voir si différence dans le traitement modification ? -->
                            <select>
                            <?php foreach ($_SESSION['all_orgas'] as $nom_orga) {?>
                                <option value="<?php echo $nom_orga;?>"></option>
                            <?php } ?>
                            </select>
                            </datalist>
                            <div class="input-group-btn">
                                <button type="submit" class="btn" name="ajout_orga">Ajouter</button>
                            </div>
                        </div>
                    </div>
                    <?php if (isset($_SESSION['orgas_appartenance'])) {
                    foreach ($_SESSION['orgas_appartenance'] as $id_orga_appartenance => $nom_orga_appartenance) { ?>
                    <div class="col-sm-10 col-sm-offset-2">
                        <div class="input-group">
                            <p class="form-control"><?php echo $nom_orga_appartenance;?></p>
                            <div class="input-group-btn">
                                <button type="submit" name="suppr_orga" class="btn" value="<?php echo $id_orga_appartenance; ?>">&times;</button>
                            </div>
                        </div>   
                    </div>                             
                    <?php }
                    } ?>
                </div>
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" name="modif_profil" value="<?php echo $_SESSION['id_profil'];?>" class="btn"><?php if (isset($_GET['modification']) AND preg_match("#manque#",$_GET['modification'])) echo $_GET['modification']; else echo 'Mettre à jour mon profil';?></button>
                </div>
                
            </fieldset>
           
            <fieldset>
            <div class="form-group <?php if (isset($_GET['modification']) AND preg_match("#passe#",$_GET['modification'])) echo 'has-error';?>">
                <label for="mdp" class="control-label col-sm-2"><?php if (isset($_GET['modification']) AND preg_match("#passe#",$_GET['modification'])) echo $_GET['modification']; else echo 'Redéfinir votre mot de passe :';?></label>
                <div class="col-sm-10">
                    <input type="password" name="mdp" id="mdp" class="form-control" placeholder="Nouveau mot de passe" maxlength="45"/>
                    <input type="password" name="mdp2" id="confirm_mdp" class="form-control" placeholder="Confirmer nouveau mot de passe" maxlength="45" onblur="verifMdp(this, 'mdp')"/>
                </div>
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" name="modif_mdp" class="btn">Changer mon mot de passe</button>
                </div>
            </div>
            </fieldset>
        </form>
        
        <button class="btn btn-primary" data-toggle="collapse" data-target="#mes_dispos">Disponibilités pour héberger</button>
        <div class="collapse <?php if (isset($_GET['disponibilité'])) echo 'in';?>" id="mes_dispos">
            <form action="modification_dispo.php" method="post">
            <!-- le tableau $dispos regroupe toutes mes disponibilites -->
            <?php foreach ($dispos_hebergement as $cle => $tableau) { ?>
                <div class="btn-group">
                    <button type="button" class="btn"><?php echo 'Du '.$tableau['date_debut'].' au '.$tableau['date_fin'].' ('.$tableau['nb_nuits'].' nuits) : '.$tableau['nb_places'].' places';?></button>
                    <button type="submit" name="suppr_dispo" class="btn" value="<?php echo $cle;?>"">&times;</button>
                </div>
            <?php } ?>
            </form> 
            <form action="modification_dispo.php" method="post" class="form-horizontal">
                <fieldset>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Nouvelle disponibilité :</label>
                        <div class="col-sm-10">
                            <div class="form-inline">
                                <div class="form-group <?php if (isset($_GET['disponibilité'])) echo 'has-error';?>">
                                    <label for="date_debut">du</label>
                                    <div class="input-group date" data-provide="datepicker">
                                        <input type="text" class="form-control" id="date_debut" name="date_debut" placeholder="<?php if (isset($_GET['disponibilité']) AND preg_match("#début#",$_GET['disponibilité'])) echo $_GET['disponibilité']; else echo 'jj/mm/aaaa';?>" required/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                    <label for="date_fin">au</label>
                                    <div class="input-group date" data-provide="datepicker">
                                    <!-- probleme : comment faire pour que la date se mette par défaut le lendemain de la date_debut-->
                                        <input type="text" class="form-control" id="date_fin" placeholder="<?php if (isset($_GET['disponibilité']) AND preg_match("#fin#",$_GET['disponibilité'])) echo $_GET['disponibilité']; else echo 'jj/mm/aaaa';?>" name="date_fin" required/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                    <label for="nb_places">places :</label>
                                    <input class="form-control" type="number" id="nb_places" name="nb_places" min="0" max="10" step="1" placeholder="nombre de place" required>
                                </div>
                                <button class="btn btn-default" type="submit" name="ajout_dispo">Ajouter</button>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>    
            <form action="modification_dispo.php" method="post" class="form-horizontal">
                <fieldset>
                    <div class="form-group">
                        <label for="description" class="control-label col-sm-2">Description (facultatif) :</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <input type="text" name="description" id="description" class="form-control" placeholder="description des modalités de l'hébergement" value="<?php if (isset($infos_hebergement['description'])) echo $infos_hebergement['description']; ?>"/>
                                <div class="input-group-btn">
                                    <button type="submit" class="btn" name="modif_description" value="<?php if (!empty($infos_hebergement)) echo true; else echo false; ?>">Modifier</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="preference" class="control-label col-sm-2">Préférence (facultatif) :</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <input type="text" name="preference" id="preference" class="form-control" placeholder="veuillez préciser s'il s'agit d'une préférence exclusive" value="<?php if (isset($infos_hebergement['preference'])) echo $infos_hebergement['preference']; ?>"/>
                                <div class="input-group-btn">
                                    <button type="submit" class="btn" name="modif_preference" value="<?php if (!empty($infos_hebergement)) echo true; else echo false; ?>">Modifier</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>    
        </div>
        <h2>Les disponibilités dans mon réseau :</h2>
        <form method="get" action="">
            <div class="form-group">
                <div class="input-group date" data-provide="datepicker">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    <input type="text" class="form-control" placeholder="Choisir une date" name="date_choisie" required=""/>
                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-default">Voir</button>
                    </div>
                </div>
            </div>
        </form>

        <form method="post" action="">
        <!-- le tableau $dispos_reseau regroupe toutes les disponibilites de mon réseau -->
        <?php 
        if (isset($dispos_reseau)) {
            if (empty($dispos_reseau)) {
                echo "Pas de résultat";
            } else {
                foreach ($dispos_reseau as $cle => $tableau) { ?>
                <div class="btn-group">
                    <div class="btn-group">
                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><?php echo 'Du '.$tableau['date_debut'].' au '.$tableau['date_fin'].' ('.$tableau['nb_nuits'].' nuits) : '.$tableau['nb_places'].' places';?><span class="caret"></span></button>
                        <ul class="dropdown-menu" role="menu">
                            <li><p><?php echo $tableau['nom_prenom'].' ('.$tableau['nom_orga'].')';?></p></li>
                            <li><p><?php echo $tableau['telephone'].' - '.$tableau['email'];?></p></li>
                            <li><p><?php echo $tableau['description'].' - '.$tableau['preference'];?></p></li>
                        </ul>
                    </div>
            
                 
                    <div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">&times;<span class="caret"></span></button>
                        <ul class="dropdown-menu  dropdown-menu-right" role="menu">
                            <li>
                                <button type="submit" name="suppr_dispo" class="btn btn-warning" value="<?php echo $cle;?>"">La place n'est plus disponible</button>
                            </li>
                        </ul>
                    </div>
                </div>
                <?php } 
            }
        }
        ?>
        </form>

        <h2>Pour inscrire une nouvelle personne : </h2>
        <button class="btn btn-primary" data-toggle="collapse" data-target="#ajout">Parrainer une nouvelle personne</button>
        <form method="post" action="modification_profil.php" class="collapse <?php if (isset($_GET['ajout'])) echo 'in';?> form-horizontal" id="ajout">
            <fieldset>
                <div class="form-group">
                    <label for="nouv_nom" class="control-label col-sm-2">Son nom ou pseudo :</label>
                    <div class="col-sm-10">
                        <input type="text" name="nom" id="nouv_nom" class="form-control" placeholder="" maxlength="45" required=""/>
                    </div>
                </div>
                <div class="form-group <?php if (isset($_GET['ajout']) AND preg_match("#email#",$_GET['ajout'])) echo 'has-error';?>">
                    <label for="nouv_email" class="control-label col-sm-2"><?php if (isset($_GET['ajout']) AND preg_match("#email#",$_GET['ajout'])) echo $_GET['ajout']; else echo 'Son email :';?></label>
                    <div class="col-sm-10">
                        <input type="email" name="email" id="nouv_email" class="form-control" placeholder="" maxlength="45" required=""/>
                    </div>
                </div>
                <div class="form-group <?php if (isset($_GET['ajout']) AND preg_match("#téléphone#",$_GET['ajout'])) echo 'has-error';?>">
                    <label for="nouv_telephone" class="control-label col-sm-2"><?php if (isset($_GET['ajout']) AND preg_match("#téléphone#",$_GET['ajout'])) echo $_GET['ajout']; else echo 'Son téléphone (numéro français) :';?></label>
                    <div class="col-sm-10">
                        <input type="tel" name="telephone" id="nouv_telephone" class="form-control" placeholder="" maxlength="45" required=""/>
                    </div>
                </div>
                
                <div class="form-group <?php if (isset($_GET['ajout']) AND preg_match("#passe#",$_GET['ajout'])) echo 'has-error';?>">
                    <label for="nouv_mdp" class="control-label col-sm-2"><?php if (isset($_GET['ajout']) AND preg_match("#passe#",$_GET['ajout'])) echo $_GET['ajout']; else echo 'Définir son mot de passe (modifiable par la suite) :';?></label>
                    <div class="col-sm-10">
                        <input type="password" name="mdp" id="nouv_mdp" class="form-control" placeholder="mot de passe" maxlength="45" required/>
                        <input type="password" name="mdp2" id="confirm_nouv_mdp" class="form-control" placeholder="Confirmer mot de passe" maxlength="45" onblur="verifMdp(this, 'nouv_mdp')" required/>
                    </div>
                </div>
                <div class="col-sm-10 col-sm-offset-2">
                    <button type="submit" name="ajout_profil" value="ajout" class="btn">Inscrire cette personne</button>
                </div>
            </fieldset>           
        </form>
      
    <?php } ?>
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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
        
        $("#fenetre").modal({show:true});

    </script>
  </body>
</html>