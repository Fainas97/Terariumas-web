$(document).ready(function() {
    // Notify settings
    $.notifyDefaults({
        type: "success",
        placement: {
            from: "bottom"
        },
        offset: {
            x: 80,
            y: 40
        },
        delay: 5000,
        animate: {
            enter: 'animated fadeInDown',
            exit: 'animated fadeOutUp'
        },
        template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
            '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
            '<span data-notify="title">{1}</span> ' +
            '<span data-notify="message">{2}</span>' +
            '<div class="progress" data-notify="progressbar">' +
            '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
            '</div>' +
            '</div>'
    });

    // Delete users
    $('#confirm-delete').on('click', '.btn-ok', function(e) {
        let $modalDiv = $(e.delegateTarget);
        $modalDiv.addClass('loading');
        let data = $(this).data('data');
        $modalDiv.modal('hide').removeClass('loading');
        $.ajax({
            url: '/' + data.url + '/delete/' + data.id,
            type: 'post',
            success: function (result) {
                reloadTableData(data.name);
                $.notify({
                    title: '<b>Operation successful!</b>',
                    message: result.success
                },{
                    delay: 10000
                });
            },
        });
    });

    $('#confirm-delete').on('show.bs.modal', function(e) {
        let data = $(e.relatedTarget).data();
        $('.title', this).text(data.recordTitle);
        $('.btn-ok', this).data('data',
            {
                'id' : data.recordId,
                'url' : data.url,
                'name' : data.name
            });
    });

    $('#start-stream').on('show.bs.modal', function(e) {
        let data = $(e.relatedTarget).data();
        $('.title', this).text(data.recordTitle);
        // $('.btn-ok', this).data('data',
        //     {
        //         'id' : data.recordId,
        //         'url' : data.url,
        //         'name' : data.name
        //     });
    });

    if ($('#alert').is(':visible')) {
        setTimeout(function(){
            $('#alert').fadeOut(1000);
        }, 10000);
    }

});

function reloadTableData(tableName) {
    let table = $('#' + tableName);
    table.html('<div class="loader"></div>');
    let url = Routing.generate(tableName.replace('-', '_'));
    table.load(url);
}

function getData(id, message) {
    let table = $('#' + id);
    table.html('<div class="loader"></div>');
    let url = Routing.generate(id.replace(/-/g, '_'));
    table.load(url);
    $.notify({
        title: '<b>Success!</b>',
        message: message + ' refreshed'
    },{
        delay: 5000
    });
}
