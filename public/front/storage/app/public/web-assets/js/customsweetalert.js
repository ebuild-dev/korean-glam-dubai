const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-success mx-2",
        cancelButton: "btn btn-danger mx-2"
    },
    buttonsStyling: false
});

function showtoast(icon, message) {
    "use strict";
    const Toast = Swal.mixin({
        toast: true,
        position: 'bottom-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })
    Toast.fire({
        icon: icon,
        title: message,
    })
    // Swal.fire({
    //   position: 'top-end',
    //   icon: icon,
    //   title: message,
    //   showConfirmButton: false,
    //   timer: 2000
    // })
}