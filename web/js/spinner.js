$(document).ready(function () {
    //Spinner pour le chargement au clic du bouton d'inscription
    $('.login-button').on('click', function () {
        $('.loader-gif').html('<img src="../fonts/gifs/spinner.gif" style="display: block; margin: 0 auto;">');
    });
});