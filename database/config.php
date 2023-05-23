<?php

    try{
        $connectionDatabase = new PDO('mysql:host=localhost;dbname=membres','root','',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        // Le dernier argument permet de récupérer les érreurs liées à la base de données MySql
    }
    catch(Exception $e){
        echo ("Erreur : $e -> getMessage() ");
    }