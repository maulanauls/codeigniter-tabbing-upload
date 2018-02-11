/**
 * Created by uls on 11/02/18.
 */
function save_change(){
    $('#form-upload').ajaxForm({
        url: base_url + 'upload/add',
        dataType: 'json',
        beforeSerialize: function () {

        },
        beforeSubmit: function () {
            $('#btn-save').html('saving . . .'); //change button text
            $('#btn-save').attr('disabled', true); //set button disable
        },
        success: function (data) {
            if (data.status) //if success close modal and reload ajax table
            {
                location.reload();
            } else {
                for (var i = 0; i < data.inputerror.length; i++) {
                    $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error');
                    //$('#'+data.inputerror[i]+'').text(data.error_string[i]);
                    $('#' + data.error_id[i] + '').text(data.error_string[i]);
                }
            }
            $('#btn-save').html('submit'); //change button text
            $('#btn-save').attr('disabled', false); //set button enable
        },
        error: function (jqXHR, textStatus, errorThrown) {

            alert('error');

            $('#btn-save').html('submit'); //change button text
            $('#btn-save').attr('disabled', false); //set button enable
        }

    });
}