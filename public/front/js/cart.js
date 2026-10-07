
$(document).ready(function () {



    function fetchCart() {
        $.ajax({
            url: "/client/json/cart",
            type: "GET",
            success: function (response) {
                var protuis = response.produits;
                var content = "";
                //foreach produits
                for (var i = 0; i < protuis.length; i++) {
                    var produit = protuis[i];
                    content += `
                        <div class="d-flex justify-content-between">
                            <div class="card-img-cart">
                                <img src="${produit.photo}" alt="image" />
                            </div>
                            <div class="my-auto">
                                <h6>${produit.nom}</h6>
                                <div class="d-flex  small">
                                <p class="small">
                                    <b>${produit.quantite} * ${produit.prix} <sup>AED</sup></b> =
                                    ${produit.total}
                                     <sup>AED</sup>
                                </p>

                                </div>
                            </div>
                            <div>
                                <span class="text-danger btnDeleteElementCartCard" data-id="${produit.produit_id}" >
                                        <i class="fa fa-trash"></i>
                                    </span>
                                </div>
                        </div>
                    `;
                }
                // Ajouter le contenu généré dans le modal
                $("#cart-content-modal").html(content);

                // Mettre à jour les totaux
                $("#badge-cart-count").text(response.total);
                $("#count-total-cart").text(response.total);
                $("#cart-total_prix").text(response.total_prix);
            }
        });
    }
    fetchCart();


    $("#btn-clear-cart").click(function () {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                //go to route /cart/clear-cart
                window.location.href = "/cart/clear-cart";
            }
        });
    });


    //close le modal cart
    $(document).on('click', '.overlay-cart', function () {
        $(".overlay-cart").hide();
    });

    //open le modal cart
    $("#btn-open-cart").click(function () {
        $(".overlay-cart").show();
    });

    $("#btn-write-review").click(function () {
        if ($("#alert-write-review").hasClass("d-none")) {
            $("#alert-write-review").removeClass("d-none");
        } else {
            $("#alert-write-review").addClass("d-none");
        }
    });



    $(document).on('click', ".btn-add-to-cart", function () {
        var id = $(this).data("id");
        var qte = $("#qte-produit-" + id).val();
        var token = $("meta[name='csrf-token']").attr("content");
        $.ajax({
            url: "/client/ajouter_au_panier",
            type: "POST",
            data: { id_produit: id, quantite: qte, _token: token },
            success: function (response) {
                if (response.statut) {
                    Swal.fire({
                        title: response.produit.nom,
                        text: 'has been added to cart',
                        icon: "success",
                        timer: 2000
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: response.message,
                        icon: "error",
                        timer: 2000
                    });
                }
            }
        });
        //attendre 1 seconde avant de rafraichir la page
        setTimeout(function () {
            fetchCart();
        }, 3000);
    });

    $(document).on('click', ".btn-preorder", function () {
        var id = $(this).data("id");
        var qte = $("#qte-produit-" + id).val() || 1; // quantité par défaut 1
        var token = $("meta[name='csrf-token']").attr("content");

        $.ajax({
            url: "/client/preorder",
            type: "POST",
            data: { id_produit: id, quantite: qte, _token: token },
            success: function (response) {
                if (response.statut) {
                    Swal.fire({
                        title: response.produit.nom,
                        text: 'has been marked as Pre-Order',
                        icon: "success",
                        timer: 2000
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: response.message,
                        icon: "error",
                        timer: 2000
                    });
                }
            }
        });

        // Mise à jour du panier après 2 sec
        setTimeout(function () {
            fetchCart();
        }, 2000);
    });

    $(document).on('click', ".btn-add-to-favoris", function (event) {
        event.stopPropagation();
        var id = $(this).data("id");
        var token = $("meta[name='csrf-token']").attr("content");
        $.ajax({
            url: "/client/ajouter_favoris",
            type: "POST",
            data: { id_produit: id, _token: token },
            success: function (response) {
                if (response.statut) {
                    Swal.fire({
                        title: "Added",
                        text: 'has been added',
                        icon: "success",
                        timer: 2000
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: response.message,
                        icon: "error",
                        timer: 2000
                    });
                }
            }
        });
        fetchCart();
    });

    $(document).on('click', '.btnDeleteElementCart', function () {
        var produit_id = $(this).data("id");
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/panier/delete/" + produit_id,
                    type: "GET",
                    success: function (response) {
                        if (response.statut) {
                            //reload page
                            location.reload();
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: response.message,
                                icon: "error",
                                timer: 2000
                            });
                        }
                    }
                });
            }
        });
    });


    $(document).on('click', '.btnDeleteElementCartCard', function () {
        var produit_id = $(this).data("id");
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/panier/delete/" + produit_id,
                    type: "GET",
                    success: function (response) {
                        if (response.statut) {
                            fetchCart();
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: response.message,
                                icon: "error",
                                timer: 2000
                            });
                        }
                    }
                });
            }
        });
    });


    $(document).on('click', '.btnUpdateElementCart', function () {
        var produit_id = $(this).data("id");
        var quantite = $('#qte-' + produit_id).val();
        $.ajax({
            url: "/panier/update/" + produit_id + "/" + quantite,
            type: "GET",
            success: function (response) {
                if (response.statut) {
                    //reload page
                    location.reload();
                } else {
                    Swal.fire({
                        title: "Error",
                        text: response.message,
                        icon: "error",
                        timer: 2000
                    });
                }
            }
        });
    });




});






function ChangeImage(image) {
    document.getElementById('MainImage').src = image.src;
}
let qte = 1;
function IncrementQte(id) {
    qte++;
    document.getElementById('qte-produit-' + id).value = qte;
}
function DecrementQte(id) {
    if (qte > 1) {
        qte--;
        document.getElementById('qte-produit-' + id).value = qte;
    }
}
