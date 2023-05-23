<?php

    try{
        $con = new PDO('mysql:host=localhost;dbname=membres','root','');
    }
    catch(Exception $e){
        echo "Erreur : $e -> getMessage()" ;

    }


    $code = "Jeanine";

    $requette = $con -> prepare('INSERT INTO profile(pseudo) VALUE(:c)');

   if( $requette -> execute(array(
    'c'=> $code

))){
    echo "good" ;
}
else{
    echo "erreur";
}


