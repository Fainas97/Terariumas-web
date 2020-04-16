$(document).ready(function() {
    $(document).on("click", "#mySubmitButton", function(e){
        e.preventDefault();
        var form = $("#newDepartmentForm");

        // you could make use of html5 form validation here
        if(!form[0].checkValidity()){

            // To show the native error hints you can fake a click() on the actual submit button
            // which must not be the button #mySubmitButton and shall be hidden with display:none;
            //  example:
            //  <button type="button" id="#mySubmitButton" class"btn btn-default" > Save </button>
            //  <button type="submit" id="#myHIDDENSubmitButton" style="display:none;"></button>
            //
            $("#myHIDDENSubmitButton").click();
            return false;
        }

        // get the serialized properties and values of the form
        var form_data = form.serializeObject();

        // always makes sense to signal user that something is happening
        $('#loadingSpinner').show();

        // simple approach avoid submitting multiple times
        $('#mySubmitButton').attr("disabled",true);

        // the actual request to your newAction
        $.ajax({
            url: '/department/new',
            type: 'POST',
            dataType: 'json',
            data: form_data,
            success:function(data){

                // handling the response data from the controller
                if(data.status == 'error'){
                    console.log("[API] ERROR: "+data.message);
                }
                if(data.status == 'success'){
                    console.log("[API] SUCCESS: "+data.message);
                }

                // signal to user the action is done
                $('#loadingSpinner').hide();
                $('#mySubmitButton').attr("disabled",false);
            }
        });
    });

    // Delete users
    $('#confirm-delete').on('click', '.btn-ok', function(e) {
        let $modalDiv = $(e.delegateTarget);
        $modalDiv.addClass('loading');
        let id = $(this).data('recordId');
        $modalDiv.modal('hide').removeClass('loading');
        $.ajax({
            url: '/user/delete/' + id,
            type: 'post',
            success: function (result) {
                reloadTableData();
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
        $('.btn-ok', this).data('recordId', data.recordId);
    });

    if ($('#alert').is(':visible')) {
        setTimeout(function(){
            $('#alert').fadeOut(1000);
        }, 10000);
    }

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
});

function reloadTableData() {
    let usersTable = $('#users-table');
    usersTable.html('<div class="loader"></div>');
    let url = Routing.generate('users_table');
    usersTable.load(url);
}