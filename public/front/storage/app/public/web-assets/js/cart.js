function clearcart(nexturl) {
    "use strict";
    swalWithBootstrapButtons.fire({

        icon: 'warning',

        title: are_you_sure,

        showCancelButton: true,

        allowOutsideClick: false,

        allowEscapeKey: false,

        confirmButtonText: yes,

        cancelButtonText: no,

        reverseButtons: true,

        showLoaderOnConfirm: true,

        preConfirm: function() {

            return new Promise(function(resolve, reject) {

                location.href = nexturl;

            });

        },

    }).then((result) => {

        if (!result.isConfirmed) {

            result.dismiss === Swal.DismissReason.cancel

        }

    })

}