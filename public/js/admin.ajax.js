$(".delete-admin").confirm({
    text: "Deseja realmente apagar o admin?",
    confirm: response => {

        var id = response.data('id');

        $(`button[data-id="${id}"`).attr({ 'disabled': true });
        $(`button[data-id="${id}"`).text('CARRE...');

        $.ajax({
            url: `admin/destroy/${id}`,
            method: 'DELETE',
            success: function (response) {

                if (!response.fail) {
                    window.location.replace(response.url);
                }

            },
            error: function (xhr) {

                var errors = xhr.responseJSON.errors;

                if (errors) {

                    $.each(errors, (index, value) => {
                        console.log(value.toString());
                    });

                    $(`button[data-id="${id}"`).attr({ 'disabled': false });
                    $(`button[data-id="${id}"`).text('APAGAR');

                }

            }
        });

    },
    cancel: response => { },
    cancelButton: "CANCELAR",
    confirmButton: "CONFIRMAR",
    confirmButtonClass: "btn-success",
    cancelButtonClass: "btn-secondary",
    dialogClass: "modal-dialog"
});

document.addEventListener('click', function (e) {

    var elements = document.querySelectorAll('.tr-admin-index');

    for (let element of elements) {

        var tr = document.querySelector(`tr[data-tr="${element.dataset.tr}"]`);

        if (e.target.dataset.id == element.dataset.tr) {
            tr.classList.add('tr-admin-index-active');
            tr.classList.remove('tr-admin-index-pattern');
        } else {
            tr.classList.add('tr-admin-index-pattern');
            tr.classList.remove('tr-admin-index-active');
        }

    }

});
