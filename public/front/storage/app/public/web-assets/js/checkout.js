if (document.getElementById("stripekey") && $("#stripekey").val() !== "") {
    var stripe = Stripe($("#stripekey").val());

    var card = stripe.elements().create("card", {
        style: {
            base: {
                fontSize: "16px",

                color: "#32325D"
            }
        }
    });

    card.mount("#card-element");

    $(".__PrivateStripeElement iframe").css({
        height: "50px",

        width: "100%",

        border: "1px solid #e5e5e5",

        "border-radius": "6px",

        display: "block",

        padding: "15px",

        "box-shadow": "0 0 6px rgba(0, 0, 0654, .3)"
    });
}

// TO-MANAGE-PAYMENT-TYPE

$("input:radio[name=transaction_type]").on("click", function(event) {
    "use strict";

    validate_transaction_type($(this).val());
});

setTimeout(function() {
    "use strict";

    $("input:radio[name=transaction_type]:checked")
        .on("click", function(event) {
            validate_transaction_type($(this).val());
        })
        .click();
}, 1000);

function validate_transaction_type(type) {
    "use strict";

    $(".walleterror").addClass("d-none");

    if (type == "3") {
        $("#card-element").removeClass("d-none");
    } else {
        $("#card-element").addClass("d-none");
    }
}

function randomdata() {
    "use strict";

    $("#user_name").val("James Carter");

    $("#user_email").val("james@yopmail.com");

    $("#user_mobile").val("(912) 756-2208");

    $("#shipping_address , #billing_address").val("9878 Ford Ave");

    $("#shipping_landmark , #billing_landmark").val("Hill");

    $("#shipping_postal_code , #billing_postal_code").val("31324");

    $("#shipping_city , #billing_city").val("Richmond");

    $("#shipping_state , #billing_state").val("Georgia");

    $("#shipping_country , #billing_country").val("United States");
}

$(function() {
    "use strict";

    if (env == "sandbox") {
        randomdata();
    }
});

function copy_billing_data() {
    "use strict";

    $("#shipping_address").val($("#billing_address").val());

    $("#shipping_landmark").val($("#billing_landmark").val());

    $("#shipping_postal_code").val($("#billing_postal_code").val());

    $("#shipping_city").val($("#billing_city").val());

    $("#shipping_state").val($("#billing_state").val());

    $("#shipping_country").val($("#billing_country").val());
}

function check_data_empty() {
    "use strict";

    let check = 0;

    $(
        ".personal-info .form-control, .billing-info .form-control, .shipping-info .form-control"
    ).each(function(index) {
        if ($(this).val() == "") {
            $(this).addClass("is-invalid").focus();

            check = 0;

            return false;
        } else if ($(this).attr("type") == "email") {
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

            if (regex.test($(this).val()) == false) {
                $(this).addClass("is-invalid").focus();

                check = 0;

                return false;
            } else {
                $(this).removeClass("is-invalid").addClass("is-valid");
            }
        } else {
            $(this).removeClass("is-invalid").addClass("is-valid");

            check = 1;
        }
    });

    if (
        check == 1 &&
        $(".shipping-area-info .form-select").find(":selected").val() == ""
    ) {
        $(".shipping-area-info .form-select").addClass("is-invalid").focus();

        check = 0;

        return false;
    }

    return check;
}

$("#shipping_area").on("change", function() {
    "use strict";

    $(".delivery-charge-section").removeClass("d-none");

    let delivery_charge = parseFloat(
        $(this).find(":selected").attr("data-delivery-charge")
    );

    $(".delivery_charge").html(currency_formate(delivery_charge));
    let sub_total = parseFloat($("#sub_total").val());

    let offer_amount = parseFloat($("#offer_amount").val());

    let tax_amount = parseFloat($("#tax_amount").val());

    let grand_total = sub_total - offer_amount + tax_amount + delivery_charge;
    grand_total = Number(grand_total.toFixed(2));
    $("#delivery_charge").val(delivery_charge);

    $("#grand_total").val(grand_total);

    $(".grand_total").text(currency_formate(grand_total));
});
// payment_type = COD : 1,RazorPay : 2, Stripe : 3, Flutterwave : 4, Paystack : 5, Mercado Pago : 7, PayPal : 8, MyFatoorah : 9, toyyibpay : 10
function placeorder() {
    "use strict";
    if (check_data_empty() == 0) {
        return false;
    }
    showloader();

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },

        url: orderlimit_url,

        data: {
            vendor_id: $("#vendor_id").val()
        },
        method: "POST",

        success: function(response) {
            if (response.status == 0) {
                hideloader();

                showtoast("error", response.message);

                return false;
            } else {
                var transaction_type = $(
                    "input:radio[name=transaction_type]:checked"
                ).val();

                var transaction_currency = $(
                    "input:radio[name=transaction_type]:checked"
                ).attr("data-currency");

                var grand_total = $("#grand_total").val();

                var user_name = $("#user_name").val();

                var user_email = $("#user_email").val();

                var user_mobile = $("#user_mobile").val();

                // -------------------- COD ----------------------

                if (transaction_type == "1") {
                    callplaceorder(1, "");
                }

                // ------------------ Razorpay -------------------

                if (transaction_type == "2") {
                    var options = {
                        "key": $('#razorpaykey').val(),
                        "amount": parseInt(grand_total * 100),
                        "name": title,
                        "description": description,
                        "image": 'https://badges.razorpay.com/badge-light.png',
                        "handler": function(response) {
                            callplaceorder(2, response.razorpay_payment_id);
                        },
                        "modal": {
                            "ondismiss": function() {
                                location.reload();
                            }
                        },
                        "prefill": {
                            name: user_name,

                            email: user_email,

                            contact: user_mobile
                        },
                        "theme": {
                            "color": "#366ed4"
                        }
                    };

                    var rzp1 = new Razorpay(options);

                    rzp1.open();
                }

                // ------------------ Stripe ---------------------

                if (transaction_type == "3") {
                    stripe.createToken(card).then(function(result) {
                        "use strict";

                        if (result.error) {
                            showtoast("error", result.error.message);

                            return false;
                        } else {
                            callplaceorder(3, result.token.id);
                        }
                    });
                }

                // ------------------ Flutterwave ----------------

                if (transaction_type == "4") {
                    FlutterwaveCheckout({
                        public_key: $("#flutterwavekey").val(),

                        tx_ref: user_name,

                        amount: grand_total,

                        currency: transaction_currency,

                        payment_options: "",

                        customer: {
                            name: user_name,

                            email: user_email,

                            phone_number: user_mobile
                        },

                        callback: function(response) {
                            callplaceorder(4, response.flw_ref);
                        },

                        onclose: function() {
                            location.reload();
                        },

                        customizations: {
                            title: title,

                            description: description,

                            logo: "https://flutterwave.com/images/logo/logo-mark/full.svg"
                        }
                    });
                }

                // ------------------ Paystack -------------------

                if (transaction_type == "5") {
                    let handler = PaystackPop.setup({
                        key: $("#paystackkey").val(),

                        email: user_email,

                        amount: parseFloat(grand_total * 100),

                        currency: transaction_currency, // Use USD for US Dollars OR GHS for Ghana Cedis

                        ref: "trx_" + Math.random().toString(16).slice(2),

                        label: "Paystack Order payment",

                        onClose: function() {
                            location.reload();
                        },

                        callback: function(response) {
                            callplaceorder(5, response.trxref);
                        }
                    });

                    handler.openIframe();
                }

                // ----------------- Mercado pago ----------------

                if (transaction_type == "7") {
                    callplaceorder(7, "");
                }

                // ----------------- PayPal ----------------
                if (transaction_type == "8") {
                    callplaceorder(8, "");
                }

                // ----------------- My Fatoorah ----------------
                if (transaction_type == "9") {
                    callplaceorder(9, "");
                }

                // ----------------- toyyibpay ----------------
                if (transaction_type == "10") {
                    callplaceorder(10, "");
                }

                // Banktransfer
                if (transaction_type == "6") {
                    $("#modalbankdetails").modal("show");
                    $(".data-bank-name").html(
                        $("input[name=transaction_type]:checked").attr("data-bank-name")
                    );
                    $(".data-account-holder-name").html(
                        $("input[name=transaction_type]:checked").attr(
                            "data-account-holder-name"
                        )
                    );
                    $(".data-account-number").html(
                        $("input[name=transaction_type]:checked").attr(
                            "data-account-number"
                        )
                    );
                    $(".data-bank-ifsc-code").html(
                        $("input[name=transaction_type]:checked").attr(
                            "data-bank-ifsc-code"
                        )
                    );
                    $("#modal_vendor_slug").val(vendorslug);
                    $("#modal_user_name").val($("#user_name").val());
                    $("#modal_user_name").val($("#user_name").val());
                    $("#modal_user_email").val($("#user_email").val());
                    $("#modal_user_mobile").val($("#user_mobile").val());
                    $("#modal_billing_address").val($("#billing_address").val());
                    $("#modal_billing_landmark").val($("#billing_landmark").val());
                    $("#modal_billing_postal_code").val($("#billing_postal_code").val());
                    $("#modal_billing_city").val($("#billing_city").val());
                    $("#modal_billing_state").val($("#billing_state").val());
                    $("#modal_billing_country").val($("#billing_country").val());
                    $("#modal_shipping_address").val($("#shipping_address").val());
                    $("#modal_shipping_landmark").val($("#shipping_landmark").val());
                    $("#modal_postal_code").val($("#shipping_postal_code").val());
                    $("#modal_shipping_city").val($("#shipping_city").val());
                    $("#modal_shipping_state").val($("#shipping_state").val());
                    $("#modal_shipping_country").val($("#shipping_country").val());
                    $("#modal_shipping_area").val(
                        $("#shipping_area").find(":selected").attr("data-area-name")
                    );
                    $("#modal_delivery_charge").val($("#delivery_charge").val());
                    $("#modal_grand_total").val($("#grand_total").val());
                    $("#modal_sub_total").val($("#sub_total").val());
                    $("#modal_tax").val($("#tax_amount").val());
                    $("#modal_notes").val($("#order_notes").val());
                    $("#modal_offer_code").val($("#offer_code").val());
                    $("#modal_offer_amount").val($("#offer_amount").val());
                    $("#modal_transaction_type").val(transaction_type);
                }
                if (transaction_type == "11") {
                    callplaceorder(11, "");
                }
            }
        },
        error: function(error) {
            hideloader();
            "error", wrong;

            return false;
        }
    });
}

function callplaceorder(transaction_type, transaction_id) {
    "use strict";
    showloader();
    var data = {};

    data["user_name"] = $("#user_name").val();

    data["user_email"] = $("#user_email").val();

    data["user_mobile"] = $("#user_mobile").val();

    data["billing_address"] = $("#billing_address").val();

    data["billing_landmark"] = $("#billing_landmark").val();

    data["billing_postal_code"] = $("#billing_postal_code").val();

    data["billing_city"] = $("#billing_city").val();

    data["billing_state"] = $("#billing_state").val();

    data["billing_country"] = $("#billing_country").val();

    data["shipping_address"] = $("#shipping_address").val();

    data["shipping_landmark"] = $("#shipping_landmark").val();

    data["shipping_postal_code"] = $("#shipping_postal_code").val();

    data["shipping_city"] = $("#shipping_city").val();

    data["shipping_state"] = $("#shipping_state").val();

    data["shipping_country"] = $("#shipping_country").val();

    data["shipping_area"] = $("#shipping_area")
        .find(":selected")
        .attr("data-area-name");

    data["delivery_charge"] = parseFloat($("#delivery_charge").val());

    data["grand_total"] = $("#grand_total").val();

    data["sub_total"] = $("#sub_total").val();

    data["tax_amount"] = $("#tax_amount").val();

    data["notes"] = $("#order_notes").val();

    data["offer_code"] = $("#offer_code").val();

    data["offer_amount"] = $("#offer_amount").val();

    data["transaction_type"] = transaction_type;

    data["transaction_id"] = transaction_id;

    data["successurl"] = successurl;

    data["failure"] = failure;

    var ajaxurl = "";

    var ajaxurl = location.href + "/placeorder";

    if (transaction_type == "7") {
        ajaxurl = location.href + "/placeorder/mercadopagorequest";
    }
    if (transaction_type == "8") {
        ajaxurl = location.href + "/placeorder/paypalrequest";
    }

    if (transaction_type == "9") {
        ajaxurl = location.href + "/placeorder/myfatoorahrequest";
    }

    if (transaction_type == "10") {
        ajaxurl = location.href + "/placeorder/toyyibpayrequest";
    }
    if (transaction_type == "11") {
        ajaxurl = location.href + "/placeorder/konnect";
    }

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },

        url: ajaxurl,

        data: data,

        method: "POST",

        success: function(response) {
            if (response.status == 1) {
                if (
                    transaction_type != "7" &&
                    transaction_type != "8" &&
                    transaction_type != "9" &&
                    transaction_type != "10" &&
                    transaction_type != "11"
                ) {
                    console.log(response);
                    location.href = ordersuccess + response.order_number;
                    // $(".checkout").remove();
                } else {
                    location.href = response.successurl;
                }

            } else {
                hideloader();

                showtoast("error", response.message);

                return false;
            }
        },

        error: function(error) {
            hideloader();

            "error", wrong;

            return false;
        }
    });
}