$(document).ready( function () {
    if(document.getElementById('form_role_1').checked){
        $('.champSession').show();
    }
    else{
        //On cache le champ de session de formation au chargement du formulaire pour la 1ère fois
        $('.champSession').hide();
    }

    //On affiche le formulaire si l'utilisateur a coché la case étudiant
    $('#form_role_1').on('click', function () {
        $('.champSession').fadeIn(2000);
    });

    //On recache le champ s'il a coché formateur
    $('#form_role_2').on('click', function () {
        $('.champSession').fadeOut(2000);
    });

    //On enlève la partie graphique du message d'erreur au clique sur le bouton du formulaire
    $('#form_save').on('click', function () {
       $('.alert-danger').hide();
    });
});