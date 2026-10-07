$(document).ready(function () {
    "use strict";
    setTimeout(function () {
        $("html").addClass("loaded");
    }, 2000);
    $(".zero-configuration").DataTable({
        dom: "Bfrtip",
        buttons: ["excelHtml5", "pdfHtml5"],
    });
    $("#banner-modal").modal("show");
    if (localStorage.getItem("modalPopup") != "shown") {
        setTimeout(function () {}, 1000);
        localStorage.setItem("modalPopup", "shown");
    }
    $(".theme3-main-slaider").owlCarousel({
        loop: true,
        rtl: rtl == "2" ? true : false,
        autoplay: true,
        autoplayTimeout: 4000,
        margin: 10,
        nav: false,
        dots: false,
        responsive: {
            320: {
                items: 1,
            },
            425: {
                items: 1,
            },
            768: {
                items: 2,
                center: true,
            },
            992: {
                items: 2,
                center: true,
            },
            1024: {
                items: 2,
                center: true,
            },
            1440: {
                items: 2,
                center: true,
            },
            1660: {
                items: 2,
                center: true,
            },
        },
    });
    $("#theme-5-home-slider").owlCarousel({
        loop: true,
        margin: 0,
        nav: false,
        dots: false,
        rtl: rtl == "2" ? true : false,
        responsive: {
            0: {
                items: 1,
                dot: false,
            },
            600: {
                items: 2,
                dot: false,
            },
            1000: {
                items: 2,
                dot: false,
            },
        },
    });
    //========================== theme-6-start ==========================//

    $(".theme-6-main-banner").owlCarousel({
        loop: true,
        margin: 0,
        nav: false,
        dots: true,
        autoplay: true,
        autoplayTimeout: 4000,
        rtl: rtl == "2" ? true : false,
        responsive: {
            0: {
                items: 1,
                dot: false,
            },
            600: {
                items: 1,
                dot: false,
            },
            1000: {
                items: 1,
                dot: false,
            },
        },
    });
    $(".theme-6-category-slider").owlCarousel({
        loop: false,
        margin: 10,
        nav: false,
        dots: false,
        rtl: rtl == "2" ? true : false,
        responsive: {
            0: {
                items: 2,
            },
            500: {
                items: 3,
            },
            768: {
                items: 3,
            },
            992: {
                items: 4,
            },
            1200: {
                items: 5,
            },
        },
    });

    //========================== theme-7-start ==========================//
    //------------ theme-7 owl Carousel js ------------//
    // theme-7-banner-slider //
    $(".theme-7-main-banner").owlCarousel({
        rtl: rtl == "2" ? true : false,
        loop: true,
        animateOut: "fadeOut",
        margin: 0,
        nav: true,
        dots: true,
        navText: [
            "<i class='fa-solid fa-arrow-left'></i>",
            "<i class='fa-solid fa-arrow-right'></i>",
        ],
        autoplay: true,
        autoplayTimeout: 4000,
        responsive: {
            0: {
                items: 1,
            },
            600: {
                items: 1,
            },
            1000: {
                items: 1,
            },
        },
    });
    $(".theme-7-category-slider").owlCarousel({
        loop: false,
        nav: true,
        dots: false,
        navText:
            rtl == "2"
                ? [
                      "<i class='fa-solid fa-arrow-right'></i>",
                      "<i class='fa-solid fa-arrow-left'></i>",
                  ]
                : [
                      "<i class='fa-solid fa-arrow-left'></i>",
                      "<i class='fa-solid fa-arrow-right'></i>",
                  ],
        rtl: rtl == "2" ? true : false,
        responsive: {
            0: {
                items: 2,
                margin: 10,
            },
            500: {
                items: 2,
                margin: 15,
            },
            768: {
                items: 2,
                margin: 20,
            },
            992: {
                items: 2,
                margin: 20,
            },
            1200: {
                items: 3,
                margin: 20,
            },
        },
    });
});
// theme-7-offer-banner-1-section //
$(".theme-7-offer-banner-1-carousel").owlCarousel({
    loop: false,
    responsiveClass: true,
    nav: true,
    dots: false,
    navText: [
        "<i class='fa-solid fa-arrow-left'></i>",
        "<i class='fa-solid fa-arrow-right'></i>",
    ],
    rtl: rtl == "2" ? true : false,
    responsive: {
        0: {
            items: 1,
            margin: 10,
        },
        500: {
            items: 1,
            margin: 15,
        },
        992: {
            items: 2,
            margin: 20,
        },
        1200: {
            items: 3,
            margin: 20,
        },
    },
});
// ==== theme-7-offer-banner-3-carousel
$(".theme-7-offer-banner-3-carousel").owlCarousel({
    loop: false,
    nav: true,
    dots: false,
    navText: [
        "<i class='fa-solid fa-arrow-left'></i>",
        "<i class='fa-solid fa-arrow-right'></i>",
    ],
    rtl: rtl == "2" ? true : false,
    responsive: {
        0: {
            margin: 10,
            items: 2,
        },
        500: {
            margin: 10,
            items: 2,
        },
        992: {
            margin: 20,
            items: 2,
        },
        1200: {
            margin: 20,
            items: 4,
            loop: false,
        },
    },
});

//========================== theme-8-start ==========================//
//------------ theme-8 owl Carousel js ------------//
// theme-8-banner-slider //
$(".theme-8-main-banner").owlCarousel({
    rtl: rtl == "2" ? true : false,
    loop: true,
    animateOut: "fadeOut",
    margin: 0,
    nav: false,
    dots: true,
    navText: [
        "<i class='fa-solid fa-arrow-left'></i>",
        "<i class='fa-solid fa-arrow-right'></i>",
    ],
    autoplay: true,
    autoplayTimeout: 4000,
    responsive: {
        0: {
            items: 1,
        },
        600: {
            items: 1,
        },
        1000: {
            items: 1,
        },
    },
});

$(".theme-8-offer-banner-1-carousel").owlCarousel({
    loop: false,
    responsiveClass: true,
    navText: [
        "<i class='fa-solid fa-arrow-left'></i>",
        "<i class='fa-solid fa-arrow-right'></i>",
    ],
    dots: false,
    rtl: rtl == "2" ? true : false,
    responsive: {
        0: {
            items: 2,
            margin: 10,
            nav: false,
        },
        500: {
            items: 2,
            margin: 10,
            nav: false,
        },
        992: {
            items: 2,
            margin: 20,
            nav: false,
        },
        1200: {
            items: 3,
            margin: 20,
            nav: false,
        },
    },
});

$(".theme-8-category-slider").owlCarousel({
    loop: false,
    nav: true,
    dots: false,
    navText:
        rtl == "2"
            ? [
                  "<i class='fa-solid fa-arrow-right'></i>",
                  "<i class='fa-solid fa-arrow-left'></i>",
              ]
            : [
                  "<i class='fa-solid fa-arrow-left'></i>",
                  "<i class='fa-solid fa-arrow-right'></i>",
              ],
    rtl: rtl == "2" ? true : false,
    responsive: {
        0: {
            items: 2,
            margin: 10,
        },
        500: {
            items: 3,
            margin: 10,
        },
        768: {
            items: 3,
            margin: 15,
        },
        992: {
            items: 4,
            margin: 15,
        },
        1200: {
            items: 5,
            margin: 15,
        },
    },
});

$(".theme-8-offer-banner-3-carousel").owlCarousel({
    loop: false,
    navText:
        rtl == "2"
            ? [
                  "<i class='fa-solid fa-arrow-right'></i>",
                  "<i class='fa-solid fa-arrow-left'></i>",
              ]
            : [
                  "<i class='fa-solid fa-arrow-left'></i>",
                  "<i class='fa-solid fa-arrow-right'></i>",
              ],
    dots: false,
    rtl: rtl == "2" ? true : false,
    responsive: {
        0: {
            items: 2,
            margin: 10,
            nav: false,
        },
        500: {
            items: 2,
            margin: 10,
            nav: true,
        },
        992: {
            items: 2,
            margin: 20,
            nav: true,
        },
        1200: {
            items: 4,
            margin: 20,
            nav: true,
        },
    },
});

//========================== theme-9-start ==========================//
//------------ theme-9 owl Carousel js ------------//

// ========= category ==========
$(".theme-9-category-slider").owlCarousel({
    loop: false,
    nav: true,
    dots: false,
    navText:
        rtl == "2"
            ? [
                  "<i class='fa-solid fa-arrow-right'></i>",
                  "<i class='fa-solid fa-arrow-left'></i>",
              ]
            : [
                  "<i class='fa-solid fa-arrow-left'></i>",
                  "<i class='fa-solid fa-arrow-right'></i>",
              ],
    rtl: rtl == "2" ? true : false,
    responsive: {
        0: {
            items: 1,
            margin: 10,
        },
        500: {
            items: 2,
            margin: 15,
        },
        768: {
            items: 2,
            margin: 20,
        },
        992: {
            items: 2,
            margin: 20,
        },
        1200: {
            items: 3,
            margin: 20,
        },
    },
});

$(
    ".theme-9-offer-banner-1-carousel,.theme-10-offer-banner-1-carousel"
).owlCarousel({
    loop: false,
    responsiveClass: true,
    navText: [
        "<i class='fa-solid fa-arrow-left'></i>",
        "<i class='fa-solid fa-arrow-right'></i>",
    ],
    dots: false,
    rtl: rtl == "2" ? true : false,
    responsive: {
        0: {
            items: 2,
            margin: 10,
            nav: false,
        },
        500: {
            items: 2,
            margin: 10,
            nav: false,
        },
        992: {
            items: 2,
            margin: 20,
            nav: false,
        },
        1200: {
            items: 3,
            margin: 20,
            nav: false,
        },
    },
});

// ==== theme-9-offer-banner-3-carousel ===
$(".theme-9-offer-banner-3-carousel").owlCarousel({
    loop: false,
    nav: false,
    dots: false,
    navText: [
        "<i class='fa-solid fa-arrow-left'></i>",
        "<i class='fa-solid fa-arrow-right'></i>",
    ],
    rtl: rtl == "2" ? true : false,
    responsive: {
        0: {
            margin: 10,
            items: 2,
        },
        500: {
            margin: 10,
            items: 2,
        },
        992: {
            margin: 20,
            items: 2,
        },
        1200: {
            margin: 20,
            items: 4,
            loop: false,
        },
    },
});

//========================== theme-10-start ==========================//

// ====== theme-10 -home-slider ===
$("#theme-10-home-slider").owlCarousel({
    loop: true,
    margin: 0,
    nav: true,
    dots: false,
    animateOut: "fadeOut",
    navText: [
        "<i class='fa-solid fa-arrow-left'></i>",
        "<i class='fa-solid fa-arrow-right'></i>",
    ],
    rtl: rtl == "2" ? true : false,
    responsive: {
        0: {
            items: 1,
            dot: false,
        },
        600: {
            items: 2,
            dot: false,
        },
        1000: {
            items: 2,
            dot: false,
        },
    },
});

$(".theme-10-category-slider").owlCarousel({
    loop: false,
    nav: true,
    dots: false,
    navText:
        rtl == "2"
            ? [
                  "<i class='fa-solid fa-arrow-right'></i>",
                  "<i class='fa-solid fa-arrow-left'></i>",
              ]
            : [
                  "<i class='fa-solid fa-arrow-left'></i>",
                  "<i class='fa-solid fa-arrow-right'></i>",
              ],
    rtl: rtl == "2" ? true : false,
    responsive: {
        0: {
            items: 1,
            margin: 10,
        },
        500: {
            items: 2,
            margin: 15,
        },
        768: {
            items: 3,
            margin: 20,
        },
        992: {
            items: 4,
            margin: 20,
        },
        1200: {
            items: 5,
            margin: 20,
        },
    },
});

// ==== theme-9-offer-banner-3-carousel ===
$(".theme-10-offer-banner-3-carousel").owlCarousel({
    loop: false,
    nav: true,
    dots: false,
    navText: [
        "<i class='fa-solid fa-arrow-left'></i>",
        "<i class='fa-solid fa-arrow-right'></i>",
    ],
    rtl: rtl == "2" ? true : false,
    responsive: {
        0: {
            margin: 10,
            items: 2,
        },
        500: {
            margin: 10,
            items: 2,
        },
        992: {
            margin: 20,
            items: 2,
        },
        1200: {
            margin: 20,
            items: 4,
            loop: false,
        },
    },
});

// Preloader
$(window).on("load", function () {
    "use strict";
    $("#status").fadeOut(), $("#preloader").delay(350).fadeOut("slow");
});
// Back to Top Button JS
$(window).on("scroll", function () {
    "use strict";
    if ($(window).scrollTop() > 300) {
        $("#back-to-top").addClass("show");
    } else {
        $("#back-to-top").removeClass("show");
    }
});
$("#back-to-top").on("click", function (e) {
    "use strict";
    e.preventDefault();
    $("html, body").animate(
        {
            scrollTop: 0,
        },
        "300"
    );
});
// CURRENCY_FORMATE - start
let currency = $("#currency").val();
let currency_position = $("#currency_position").val();

function currency_formate(price) {
    //  console.log(parseFloat(price).toLocaleString().split(".")[0]);
    // console.log(parseFloat(price).toFixed(formate).split(".")[1]);
    "use strict";
    let final_price = parseFloat(price).toLocaleString().split(".")[0];
    final_price = final_price.replace(",", ".");
    if (currency_position == 1) {
        return currency + final_price;
    } else {
        return final_price + currency;
    }
}
// CURRENCY_FORMATE - end
// TO DISABLE CHARACTERS IN INPUT -- START
$(".numbers_only").on("keyup", function () {
    "use strict";
    var val = $(this).val();
    if (isNaN(val)) {
        val = val.replace(/[^0-9\.]/g, "");
        if (val.split(".").length > 2) {
            val = val.replace(/\.+$/, "");
        }
    }
    $(this).val(val);
});
// TO DISABLE CHARACTERS IN INPUT -- END
function showloader() {
    "use strict";
    $("#preloader").show();
    $("#status").show();
    $("html").removeClass("loaded");
}

function hideloader() {
    "use strict";
    $("#preloader").hide();
    $("#status").hide();
    $("html").addClass("loaded");
}
// function loginrequired(message) {
//   "use strict";
//   showtoast('warning', message);
// }
// Manage_Cart
function addtocart(
    product_id,
    product_slug,
    product_name,
    product_image,
    product_tax,
    product_price,
    attribute,
    addcarturl
) {
    "use strict";
    var variation_id = $("input[name='size']:checked").attr("data-id");
    var variation_name = $("input[name='size']:checked").val();
    calladdtocart(
        product_id,
        product_slug,
        product_name,
        product_image,
        product_tax,
        product_price,
        attribute,
        variation_id,
        variation_name,
        addcarturl
    );
}

function calladdtocart(id) {
    var quantityElement = $("#qte-" + id);
    if (quantityElement.length) {
        var quantity = quantityElement.val();
    } else {
        var quantity = 1;
    }

    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    var data = {
        id_produit: id,
        quantite: quantity,
        _token: csrfToken,
    };
    fetch("/client/ajouter_au_panier", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify(data),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.statut) {
                showtoast("success", data.message);
                get_panier();
            } else {
                showtoast("error", data.message);
            }
        })
        .catch((error) => {
            console.error("Erreur:", error);
        });
    ("use strict");
    get_panier();
}
get_panier();

function get_panier() {
    $.get("/client/count_panier", function (data, status) {
        if (status) {
            // console.log(data);
            $(".cart-count").html(data.total);
        } else {
            console.log("error get panier");
        }
    });
}

function productview(id) {
    //open model
    $.get("/view_produit/" + id, function (data, status) {
        if (status) {
            // console.log(data);
            $("#product-view-modal").empty();
            $("#product-view-modal").html(data);
            $("#pro-view").modal("show");
        } else {
            console.log("error get panier");
        }
    });
}

//========================== theme-2-start ==========================//
//------------ theme-2 owl Carousel js ------------//
// theme-2-banner-slider //
$(".theme-2-main-banner").owlCarousel({
    rtl: rtl == "2" ? true : false,
    loop: true,
    margin: 0,
    nav: false,
    dots: true,
    autoplay: true,
    autoplayTimeout: 4000,
    responsive: {
        0: {
            items: 1,
        },
        600: {
            items: 1,
        },
        1000: {
            items: 1,
        },
    },
});
// theme-2-category-slider //
$(".category-slider").owlCarousel({
    loop: false,
    margin: 10,
    nav: false,
    dots: false,
    rtl: rtl == "2" ? true : false,
    responsive: {
        0: {
            items: 2,
        },
        768: {
            items: 3,
        },
        992: {
            items: 4,
        },
        1200: {
            items: 6,
        },
    },
});
// theme-2-offer-banner //
$(".offer-banner-carousel-1").owlCarousel({
    loop: false,
    margin: 10,
    responsiveClass: true,
    nav: false,
    dots: false,
    rtl: rtl == "2" ? true : false,
    responsive: {
        0: {
            items: 2,
            nav: false,
        },
        500: {
            items: 2,
            nav: false,
        },
        992: {
            items: 2,
            nav: false,
        },
        1200: {
            items: 3,
            nav: true,
            loop: false,
        },
    },
});
// theme-2-offer-banner-3 //
$(".offer-banner-3-carousel").owlCarousel({
    loop: false,
    margin: 10,
    nav: false,
    dots: false,
    rtl: rtl == "2" ? true : false,
    responsive: {
        0: {
            items: 2,
            nav: false,
        },
        500: {
            items: 2,
            nav: false,
        },
        992: {
            items: 2,
            nav: false,
        },
        1200: {
            items: 4,
            nav: false,
            loop: false,
        },
    },
});
// theme-2-blogs-carousel //
$(".blogs-carousel").owlCarousel({
    loop: false,
    margin: 10,
    nav: true,
    dots: false,
    responsiveClass: true,
    rtl: rtl == "2" ? true : false,
    responsive: {
        0: {
            items: 1,
        },
        500: {
            items: 2,
        },
        992: {
            items: 3,
            margin: 20,
        },
        1200: {
            items: 4,
            margin: 20,
        },
    },
});
//========================== theme-3-start ==========================//
// theme-2-slider-main-banner-section
$(
    ".theme-3-main-banner,.theme-4-main-banner, .theme-5-main-banner"
).owlCarousel({
    loop: true,
    margin: 10,
    nav: false,
    dots: true,
    // autoplay: true,
    // autoplayTimeout: 4000,
    rtl: rtl == "2" ? true : false,
    responsive: {
        0: {
            items: 1,
        },
        600: {
            items: 1,
        },
        1000: {
            items: 1,
        },
    },
});
// theme-3-offer-banner-1-section //
$(
    ".theme-3-offer-banner-1-carousel ,.theme-4-offer-banner-1-carousel,.theme-5-offer-banner-1-carousel,.theme-6-offer-banner-1-carousel,.theme-7-offer-banner-1-carousel"
).owlCarousel({
    loop: false,
    margin: 20,
    responsiveClass: true,
    nav: false,
    dots: false,
    rtl: rtl == "2" ? true : false,
    responsive: {
        0: {
            items: 2,
        },
        500: {
            items: 2,
        },
        992: {
            items: 2,
        },
        1200: {
            items: 3,
        },
    },
});
// theme-3-category-slider //
$(".theme-3-category-slider").owlCarousel({
    loop: false,
    margin: 10,
    nav: false,
    dots: false,
    rtl: rtl == "2" ? true : false,
    responsive: {
        0: {
            items: 1,
        },
        500: {
            items: 2,
        },
        768: {
            items: 3,
        },
        992: {
            items: 4,
        },
        1200: {
            items: 5,
        },
    },
});

// theme-2,4,5,6-offer-banner-3 //
$(
    ".theme-3-offer-banner-3-carousel,.theme-4-offer-banner-3-carousel,.theme-5-offer-banner-3-carousel,.theme-6-offer-banner-3-carousel"
).owlCarousel({
    loop: false,
    margin: 20,
    nav: false,
    dots: false,
    rtl: rtl == "2" ? true : false,
    responsive: {
        0: {
            items: 2,
            nav: false,
        },
        500: {
            items: 2,
            nav: false,
        },
        992: {
            items: 2,
            nav: false,
        },
        1200: {
            items: 4,
            nav: false,
            loop: false,
        },
    },
});

// theme-3-blogs-carousel //
$(".theme-3-blogs-carousel").owlCarousel({
    loop: false,
    margin: 10,
    nav: false,
    dots: false,
    responsiveClass: true,
    rtl: rtl == "2" ? true : false,
    responsive: {
        0: {
            items: 1,
            nav: false,
        },
        500: {
            items: 2,
            nav: false,
        },
        992: {
            items: 2,
            nav: false,
        },
        1200: {
            items: 3,
            nav: false,
        },
    },
});
//========================== theme-4-start ==========================//
// theme-3-category-slider //
$(".theme-4-category-slider").owlCarousel({
    loop: false,
    nav: false,
    dots: false,
    rtl: rtl == "2" ? true : false,
    responsive: {
        0: {
            items: 1,
        },
        500: {
            items: 2,
        },
        768: {
            items: 3,
        },
        992: {
            items: 4,
        },
        1200: {
            items: 5,
        },
    },
});
// theme-4-best-Selling-Products-section //
$(".theme-4-Selling-product").owlCarousel({
    loop: true,
    dots: false,
    nav: false,
    rtl: false,
    responsive: {
        0: {
            items: 1,
        },
        600: {
            items: 3,
        },
        1000: {
            items: 4,
        },
        1200: {
            items: 5,
        },
    },
});
// theme-4-new-product-section //
$(".theme-4-new-product-carousel").owlCarousel({
    loop: true,
    dots: false,
    nav: false,
    rtl: false,
    responsive: {
        0: {
            items: 1,
        },
        600: {
            items: 3,
        },
        1000: {
            items: 4,
        },
        1200: {
            items: 5,
        },
    },
});

// theme-4-blogs-carousel //
$(".theme-4-blogs-carousel").owlCarousel({
    loop: false,

    margin: 0,

    nav: true,

    dots: false,

    responsiveClass: true,

    rtl: rtl == "2" ? true : false,

    responsive: {
        0: {
            items: 1,
        },

        500: {
            items: 2,
        },

        992: {
            items: 2,
        },

        1200: {
            items: 4,
        },
    },
});
//========================== theme-5-start ==========================//
$(".theme-5-category-slider").owlCarousel({
    loop: false,
    margin: 10,
    nav: false,
    dots: false,
    rtl: rtl == "2" ? true : false,
    responsive: {
        0: {
            items: 2,
        },
        500: {
            items: 3,
        },
        768: {
            items: 3,
        },
        992: {
            items: 4,
        },
        1200: {
            items: 5,
        },
    },
});

// theme-5-blogs-carousel //
$(".theme-5-blogs-carousel").owlCarousel({
    loop: false,
    nav: true,
    dots: false,
    margin: 20,
    responsiveClass: true,
    rtl: rtl == "2" ? true : false,
    responsive: {
        0: {
            items: 1,
        },
        500: {
            items: 2,
        },
        992: {
            items: 3,
        },
        1200: {
            items: 4,
        },
    },
});

function managefavorite(id) {
    "use Strict";
    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    var data = {
        id_produit: id,
        _token: csrfToken,
    };
    fetch("/client/ajouter_favoris", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify(data),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.statut) {
                showtoast("success", data.message);
            } else {
                showtoast("error", data.message);
            }
        })
        .catch((error) => {
            console.error("Erreur:", error);
        });
}

function statusupdate(nexturl) {
    "use strict";
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success mx-1",
            cancelButton: "btn btn-danger mx-1",
        },
        buttonsStyling: false,
    });
    swalWithBootstrapButtons
        .fire({
            title: are_you_sure,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: yes,
            cancelButtonText: no,
            reverseButtons: true,
        })
        .then((result) => {
            if (result.isConfirmed) {
                location.href = nexturl;
            } else {
                result.dismiss === Swal.DismissReason.cancel;
            }
        });
}
$(".mobile-number").on("keyup", function () {
    "use strict";
    var val = $(this).val();
    if (isNaN(val)) {
        val = val.replace(/[^0-9\.+.-]/g, "");
        if (val.split(".").length > 2) {
            val = val.replace(/\.+$/, "");
        }
    }
    $(this).val(val);
});
$("#btndecline").on("click", function () {
    if (localStorage.getItem("modalsubscribe") != "shown") {
        setTimeout(function () {
            $("#subsciptionmodal").modal("show");
        }, 1000);
        localStorage.setItem("modalsubscribe", "shown");
    }
});
$("#btnagree").on("click", function () {
    if (localStorage.getItem("modalsubscribe") != "shown") {
        setTimeout(function () {
            $("#subsciptionmodal").modal("show");
        }, 1000);
        localStorage.setItem("modalsubscribe", "shown");
    }
});

// ===================== //

// $(document).ready(function() {
//   $(".card").click(function() {
//     $imgsrc = $(this).find(".img-src").attr("src");
//     $("#imagechange").attr("src", $imgsrc).fadeIn(1000);
//   });
// });

$(".product_gallery a").click(function () {
    $(".big-view img").attr("src", $(this).attr("href"));
    return false;
});

$(".big-view").owlCarousel({
    loop: false,

    margin: 0,

    nav: false,

    dots: false,

    responsive: {
        0: {
            items: 1,
        },

        600: {
            items: 1,
        },

        1000: {
            items: 1,
        },
    },
});

$("#preview-img").owlCarousel({
    loop: true,
    margin: 10,
    nav: true,
    dots: false,
    responsive: {
        0: {
            items: 4,
        },
        600: {
            items: 4,
        },
        1000: {
            items: 4,
        },
    },
});

$(".mobile-menu-active li").click(function () {
    $(".mobile-menu-active li a")
        .removeClass("active")
        .eq($(this).index())
        .addClass("active");
});




// Fonction pour définir un cookie avec une date d'expiration
function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/"; // Ajoutez path=/
}
function choose_pays_select() {
    var pays = $("#pays_choosed").val();
    if (pays == "Tunisie" || pays == "Autres" || pays == "France") {
        document.location.href = "/set-cookie/" + pays;
    } else {
        console.log("Erreur du choix du pays");
    }
}

$(document).ready(function () {
    let shCurrentIndex = 0;
    const shSlides = $(".sh-slider-item");
    const shTotalSlides = shSlides.length;

    function shShowSlide(index) {
        const offset = -index * 100 + "%";
        $(".sh-slider-wrapper").css("transform", `translateX(${offset})`);
    }

    function shNextSlide() {
        shCurrentIndex = (shCurrentIndex + 1) % shTotalSlides;
        shShowSlide(shCurrentIndex);
    }

    setInterval(shNextSlide, 7000);

    // Swipe functionality for mobile
    let shTouchStartX = null;
    let shTouchEndX = null;

    $(".sh-slider-container").on("touchstart", function (e) {
        shTouchStartX = e.originalEvent.touches[0].clientX;
    });

    $(".sh-slider-container").on("touchmove", function (e) {
        shTouchEndX = e.originalEvent.touches[0].clientX;
    });

    $(".sh-slider-container").on("touchend", function () {
        if (shTouchStartX - shTouchEndX > 50) {
            shCurrentIndex = (shCurrentIndex + 1) % shTotalSlides;
            shShowSlide(shCurrentIndex);
        } else if (shTouchEndX - shTouchStartX > 50) {
            shCurrentIndex =
                (shCurrentIndex - 1 + shTotalSlides) % shTotalSlides;
            shShowSlide(shCurrentIndex);
        }
    });
});

$(document).ready(function () {
    $("#btn-header-recherche").on("click", function (e) {
        e.preventDefault();
        const searchInput = $("#search-input");
        if (searchInput.hasClass("open")) {
            searchInput.removeClass("open").slideUp("slow");
        } else {
            searchInput.addClass("open").slideDown();
        }
    });
});
