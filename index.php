    <?php include("database/config.php"); ?>
    <script>
        let resultatSauvegardeJS = false ; // Variable permettant de confirmer ou d'infirmer la sauvegarde des données dans la base de données
        let presencePseudoJS = false ; // Variable permettant de vérifier si le pseudo entré par l'utilisateur existe déjà dans la base de données ou non
        let afficheConfirmation = document.getElementById("confirmationBdd"); // Récupération de l'ID de la barre de notification

    </script>
   <?php
   

        // Récupérons l'image par défaut dans la base de données

        $presencePseudo = "";
        $resultatPseudoChemin = ""; // Variable permettant de récupérer le pseudo et le chemin depuis la base de données
        $CheminDataBase ; // Variable permettant de récupérer le chemin de la photo depuis la base de données
        $pseudoDataBase ; // Variable permettant de récupérer le pseudo depuis la base de données


        if( isset($_POST['pseudo']) && isset($_POST['send'])){
            $pseudo = $_POST['pseudo'] ;
            $nomFichier = "iconDefault.png" ;
            $cheminTemporaire = "";

            if(isset($_FILES['monfichier'])){

                if($_FILES['monfichier']['error'] == 0){

                
                    // vérification de l'extension du fichier
                    $infofichier = pathinfo($_FILES['monfichier']['name']);
    
                    $extension_upload = $infofichier['extension']; // On récupère l'extension du fichier envoyé
    
                    /* La fonction pathinfo renvoie un array contenant entre autres l'extension du fichier dans
                        $infosfichier['extension']. On stocke ça dans une variable $extension_upload.
                        Une fois l'extension récupérée, on peut la comparer à un tableau d'extensions autorisées (un array) et vérifier si l'extension
                        récupérée fait bien partie des extensions autorisées à l'aide de la fonction in_array(). 
                    */
    
                    // Ainsi, on crée la liste des extension autorisées
    
                    $extension_autorisees = ['jpg','jpeg','png'];
    
                    // verification des extensions
    
                    if(in_array($extension_upload,$extension_autorisees)){  
                        // nous allons enfin valider l'envoie du fichier sur le serveur
                        $cheminTemporaire = $_FILES['monfichier']['tmp_name'] ;  // Récupération de l'emplacement temporaire du fichier
                        $dateDuJour = date("d_m_Y_H_i_s"); // Récupération de la date pour définir le nom du fichier
                        $nomFichier = basename($_FILES['monfichier']['name']); // Récupération du nom d'origine du fichier
                        $nomFichier = $pseudo . $dateDuJour ; // Modification du nom du fichier par le pseudo de l'utilisateur de la date (concaténation)
                    }
                     
                }

            }
            //echo $nomFichier ;

            $nomDossierSauvegarde = 'ImageSauv/'; //  Creation du dossier pour sauvegarder les fichier
    
            $cheminDefinitif = $nomDossierSauvegarde . $nomFichier  ; // Le chemin définitif est égale au chemin du repertoire plus le nom du fichier

            // Sauvegarde du pseudo et du chemin definitif de la photo dans la base de donées

            $resultatSauvegarde = "" ; // On définit une variable qui va confirmer ou infirmer la sauvegarde des données ;
            $pseudobdd = $_POST['pseudo']; // On récupère le pseudo du formulaire dans une autre nouvelle variable

            /* ******* Maintenant, nous allons interdire à deux utilisateurs d'utiliser un meme pseudo ******* */

            // Ainsi, nous vérifions si le pseudo entré par l'utilisateur existe déjà dans le base de données

            $requetteVerificationPseudo = $connectionDatabase -> prepare('SELECT * FROM profile WHERE pseudo = :pseud');
            $requetteVerificationPseudo -> execute(array(
                'pseud' => $pseudobdd
            ));

            $resultatVerificationPseudo = $requetteVerificationPseudo->fetch();

            if($resultatVerificationPseudo){ ?> 
                <script>
                    presencePseudoJS = true ;
                </script>
            <?php
                $presencePseudo = "$pseudo est déjà utilisé, veuillez choisir un autre pseudo";
            }else{

                $requetteSauvegardePseudoPhoto = $connectionDatabase -> prepare('INSERT INTO profile(pseudo,photo) VALUE (:pseudoo, :photoo)'); // Insertion des informations dans la bdd à travers la requette
            
                if($requetteSauvegardePseudoPhoto -> execute(array(
                    'pseudoo' => $pseudobdd,
                    'photoo' => $cheminDefinitif
                ))){ ?> 
                <script>
                    resultatSauvegardeJS = true ;
                </script>

                <?php
    
                    move_uploaded_file( $cheminTemporaire,$cheminDefinitif); // Sauvegarde du fichier sur le serveur
                    
                    $resultatSauvegarde = " Informations enregistrées avec succès !";
    
                }
                else{
                    $resultatSauvegarde = "Une erreur s'est produite lors de la sauvegarde des donées !";
                }
             

            }



            /******* Récupération du pseudo de du chemin de la photo depuis la base de données  *********/

            $requetteRecuperationPseudoPhoto = $connectionDatabase -> prepare('SELECT pseudo, photo FROM profile WHERE pseudo = :pseudoR');

            $requetteRecuperationPseudoPhoto -> execute(array(
                'pseudoR' => $pseudo
            ));

            $resultatPseudoChemin = $requetteRecuperationPseudoPhoto -> fetch();
            $pseudoDataBase = $resultatPseudoChemin['pseudo'];
            $CheminDataBase = $resultatPseudoChemin['photo'];
            //echo $resultatPseudoChemin['photo'];


        }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="icons/css/all.css">
    <title>Formulaire</title>
</head>
<body>

    <div class="main-content">
        <!-- Confirmation ou infirmation de la sauvegarde des informations dans la base données -->
        <p class="confirmation-bdd" id="confirmationBdd">
            <strong>
                <?php
                    if(isset($resultatSauvegarde)){
                        echo $resultatSauvegarde ; ?>
                        <script>
                            // Gestion de la petite barre de notification
                            if(resultatSauvegardeJS == true){
                                document.getElementById("confirmationBdd").style.display = "block";

                                setTimeout(function(){
                                    document.getElementById("confirmationBdd").style.display = "none";
                                }, 4200) ;
                            }

                            //alert(resultatSauvegardeJS);
                        </script>
                        

                   <?php

                   }

                   if(isset($presencePseudo)){
                        echo($presencePseudo); ?>
                        <script>
                            if(presencePseudoJS == true){
                                //document.getElementById("afficherpseudo").innerHTML = "Username";
                                //alert("Pseudo deja utilisé");
                                document.getElementById("confirmationBdd").style.display = "block";
                                document.getElementById("confirmationBdd").style.backgroundColor = "red";
                                setTimeout(function(){
                                    document.getElementById("confirmationBdd").style.display = "none";
                                }, 4300) ;

                               

                            }
                        </script>

                   <?php 
                        }
                
                ?>
            </strong>
        </p>
       <div class="profil">
            <p class="imageProfil">
                <img id="firstImage" class="main-imageProfil" src="<?php
                if(isset($CheminDataBase)){
                    echo ($CheminDataBase);
                }else{
                    echo ("ImageDefaut/iconDefault.png");
                } ?>" alt="">
                <img src="" id="secondImage" alt="">
            </p>

            <p class="name" id="afficherpseudo">
                <?php 
                    if(isset($pseudoDataBase)){
                        echo ($pseudoDataBase);
                    }else{ 
                        echo ("Username");
                    }
                ?>
            </p>

            <form action="index.php" method="POST" enctype="multipart/form-data">
                <div class="pseudo-photo">
                    <input type="text" name="pseudo" required placeholder="Entrez votre pseudo" autocomplete="off" autofocus>
                    <label title="Choisir une photo" for="camera"><i class="fa-solid fa-camera" id="image"></i></label>

                </div>
                <input type="file" id="camera" name="monfichier" onchange="pictureSelected()"> <br>
                <input type="submit" name="send" value="Valider">
            </form>
       </div>

    </div>

    <script src="Js/pictureSelected.js"></script>

    <script>
     /*   if(presencePseudoJS == true){
            document.getElementById("afficherpseudo").innerHTML= "Username";
            //alert("hello");
        } */
    </script>

</body>
</html>