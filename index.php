
   <?php
        if( isset($_POST['pseudo']) && isset($_POST['send'])){
            $pseudo = $_POST['pseudo'] ;

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

                    $nomDossierSauvegarde = 'ImageSauv/'; //  Creation du dossier pour sauvegarder les fichier

                    $cheminDefinitif = $nomDossierSauvegarde . $nomFichier  ; // Le chemin définitif est égale au chemin du repertoire plus le nom du fichier

                    move_uploaded_file( $cheminTemporaire,$cheminDefinitif); // Sauvegarde du fichier sur le serveur
                

                   // echo $cheminDefinitif ;

                }
                    

                
            }
            //$fichier = $_FILES['monfichier']['name'];
            //echo($fichier);


        }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Formulaire</title>
</head>
<body>
    <div class="main-content">
       <div class="profil">
            <p class="imageProfil">
                <img src="<?php
                if(isset($cheminDefinitif)){
                    echo ($cheminDefinitif);
                }else{
                    echo ("ImageDefaut/iconDefault.png");
                } ?>" alt="">
            </p>

            <p class="name" id="afficherpseudo">
                <?php if(isset($_POST['pseudo'])){
                    echo $_POST['pseudo'] ;
                }?>
            </p>

            <form action="index.php" method="POST" enctype="multipart/form-data">
                <input type="text" name="pseudo" required placeholder="Pseudo"><br>
                <input type="file" name="monfichier" required placeholder="cc"> <br>
                <input type="submit" name="send" value="Valider">
            </form>
       </div>

    </div>
</body>
</html>