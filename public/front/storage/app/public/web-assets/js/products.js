if (document.getElementById("mainCarousel")) {
    const mainCarousel = new Carousel(document.querySelector("#mainCarousel"), {
        Dots: false,

        on: {
            createSlide: (carousel, slide) => {
                slide.Panzoom = new Panzoom(slide.$el.querySelector(".panzoom"), {
                    panOnlyZoomed: true,

                    resizeParent: true
                });
            },

            deleteSlide: (carousel, slide) => {
                if (slide.Panzoom) {
                    slide.Panzoom.destroy();

                    slide.Panzoom = null;
                }
            }
        }
    });

    const thumbCarousel = new Carousel(document.querySelector("#thumbCarousel"), {
        Sync: {
            target: mainCarousel,

            friction: 0
        },

        Dots: false,

        Navigation: false,

        center: true,

        infinite: false
    });
}

$('input[name="size"]:checked')
    .on("click", function() {
        "use strict";
        $(".product-variation").html($(this).val());

        showprice($(this).attr("data-price"), $(this).attr("data-original-price"));
    }).click();

$('input[name="size"]').on("click", function() {
    "use strict";
    $(".product-variation").html($(this).val());

    showprice($(this).attr("data-price"), $(this).attr("data-original-price"));
});

function showprice(price, original_price) {
    "use strict";

    $(".product-price").html(currency_formate(price));

    if (original_price > 0) {
        $(".product-original-price").html(currency_formate(original_price));

        $(".product-price-off-box").show();

        var off = 100 - price * 100 / original_price;

        $(".price-off").show().html(off);
    } else {
        $(".product-original-price").html("");

        $(".product-price-off-box").hide();
    }
}

if (document.getElementById("slider-range")) {
    $(function() {
        "use strict";

        $("#slider-range").slider({
            range: true,

            min: 0,

            max: max_price,

            values: [set_min_price, set_max_price],

            slide: function(event, ui) {
                $("#from").val(ui.values[0]);

                $("#to").val(ui.values[1]);
            }
        });

        $("#from").val($("#slider-range").slider("values", 0));

        $("#to").val($("#slider-range").slider("values", 1));
    });
}
$('.relatedPro').owlCarousel({

    loop: false,

    margin: 10,

    nav: false,

    dots: false,

    autoplay: true,

    autoplayTimeout: 5000,

    responsive: {

        0: {

            items: 1

        },

        600: {

            items: 3
        },

        1000: {

            items: 5

        }
    }
})

// Product Preview
$('.sp-wrap').smoothproducts();