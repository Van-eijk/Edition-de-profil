
let camera = document.getElementById("image"); // On récupère l'id de l'icone de la camera
let secondImage = document.getElementById("secondImage"); // On récupère l'Id de la seconde Image
let firstImage = document.getElementById("firstImage"); // On récupère l'Id de la première image
let inputFile = document.querySelector("input[type=file]").files; // On récupère l'Id de la balise Input type = "file"

function pictureSelected(){
    camera.style.color = "greenyellow";

    document.getElementById("confirmationBdd").style.display = "block";
    document.getElementById("confirmationBdd").style.backgroundColor = "greenyellow";

    document.getElementById("confirmationBdd").innerHTML = "Photo sélectionnée";

    setTimeout(function(){
        document.getElementById("confirmationBdd").style.display = "none";
    }, 3000) ;


   /* firstImage.style.display = "none";
    secondImage.style.display = "block";

    if(inputFile.length > 0){
        let fileReader = new FileReader();
        fileReader.onload = function(event){
            secondImage.setAttribute("src", event.target.result);
            //console.log(event.target.result);
        };
        fileReader.readAsDataURL(file[0]);
    }*/

}