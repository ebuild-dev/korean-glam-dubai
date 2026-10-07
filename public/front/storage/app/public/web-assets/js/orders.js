function cancelorder(nexturl) {
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

function copyText(copied) {
    "use strict";
    var copyText = document.getElementById("myInput");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    document.execCommand("copy");
    var tooltip = document.getElementById("myTooltip");
    tooltip.innerHTML = copied;
}