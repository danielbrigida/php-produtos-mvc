function confirmModal(message, url) {
    bootbox.confirm({
        title: 'Atenção!',
        message: message,
        buttons: {
            'cancel': {
                label: 'Cancelar',
                className: 'btn-primary pull-left'
            },
            'confirm': {
                label: 'Confirmar',
                className: 'btn-danger pull-right'
            }
        },
        callback: function (confirm) {

            if (confirm) {
                location.href = url;
            }
        }
    });
}

function confirmRedirectModal(message, urlToRedirect) {
    bootbox.confirm({
        closeButton: false,
        title: 'Você será redirecionado!',
        message: message,
        buttons: {
            'confirm': {
                label: 'Não',
                className: 'btn-primary pull-left'
            },
            'cancel': {
                label: 'Sim',
                className: 'btn-danger pull-right'
            }
        },
        callback: function (confirm)
        {
            if (confirm === false) {
                location.href = urlToRedirect;
            } 
        }
    });
}