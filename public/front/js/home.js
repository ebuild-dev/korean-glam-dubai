$(document).ready(function () {

    $(document).on('click', ".btn-change-type", function() {
        var type = $(this).data("type");
        
        // Retirer 'active' des éléments de la liste
        $('.btn-change-type.active').removeClass('active');
        // Ajouter 'active' à l'élément cliqué
        $(this).addClass('active');
        
        // Masquer tous les onglets
        $('.tab').removeClass('active');
        // Afficher uniquement l'onglet correspondant au type
        $('.tab.' + type).addClass('active');
    });
    
    
});