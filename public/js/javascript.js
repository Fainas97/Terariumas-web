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

    $('#confirm-delete').on('click', '.btn-ok', function(e) {
        let $modalDiv = $(e.delegateTarget);
        $modalDiv.addClass('loading');
        let id = $(this).data('recordId');
        $.ajax({
            url: '/user/delete/' + id,
            type: 'post',
            success: function () {
                let modal = $('#modalSuccess');
                modal.find('#successBody').text('hello');
            },
        });
        $modalDiv.modal('hide').removeClass('loading');
        $('#modalSuccess').modal('show');

    });

    $('#confirm-delete').on('show.bs.modal', function(e) {
        let data = $(e.relatedTarget).data();
        $('.title', this).text(data.recordTitle);
        $('.btn-ok', this).data('recordId', data.recordId);
    });
});

function deleteRow(r) {

}