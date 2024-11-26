$(document).ready(function () {
    $('#btn_send_party_update').click(function (e) {
        $('#btn_send_party_update_info').show();
    });
    $('#btn_delete').click(function (e) {
        $('#delete-warning').show();
        $('#btn_delete').attr('disabled', true);
        $('#delete-confirmation').focus();
    });
    $('#btn_delete_cancel').click(function (e) {
        $('#delete-warning').hide();
        $('#btn_delete').attr('disabled', false).focus();
    });

    $('#btn_add').click(function (e) {
        $('#add-participant').show();
        $('#btn_add').attr('disabled', true);
        $('#add-participant-name').focus();
    });

    $('#btn_add_cancel').click(function (e) {
        $('#add-participant').hide();
        $('#btn_add').attr('disabled', false).focus();
    });

    $('#btn_update').click(function (e) {
        $('#update-party-details').show();
        $('#btn_update').attr('disabled', true);
    });

    $('#btn_update_cancel').click(function (e) {
        $('#update-party-details').hide();
        $('#btn_update').attr('disabled', false).focus();
    });

    $('.link_remove_participant').click(function (e) {
        $('#delete-participant').show();
        $('.link_remove_participant').attr('disabled', true);
        $('#delete-participant-confirmation').focus();
        var listUrl = $(this).data('listurl');
        var participantUrl = $(this).data('participant');
        attachAction(listUrl, participantUrl);
    });

    $('.btn_remove_participant_cancel').click(function (e) {
        $('#delete-participant').hide();
        $('.link_remove_participant').attr('disabled', false);
    });

    $('#btn_join').click(function (e) {
        $('#join-mode').show();
        $('#btn_join').attr('disabled', true);
    });

    if (Modernizr.inputtypes.date == true) {
        $("#intracto_secretsantabundle_updatepartydetailstype_eventdate").click(function (e) {
            $(this).datepicker({dateFormat: 'dd-mm-yy'});
        });
    }

    $('.js-selector-participant').select2({ width: '100%' });

    $('.participant-edit-icon').on('click', function() {
        editParticipant($(this).data('listurl'), $(this).data('participant-url'));
    });

    $(document).on('click', '.save-edit', function(){
        submitEditForm($(this).data('listurl'), $(this).data('participant-url'));
    });
});
function showExcludeErrors() {
    alert('bar');
    $('#collapsedMessage').collapse('show');
    $('html, body').animate({
        scrollTop: $("#collapsedMessage").offset().top
    }, 2000);
}

function editParticipant(listUrl, participantUrl) {
    var email = $('#email_' + participantUrl).html();
    var name = $('#name_' + participantUrl).html();
    var url = $('table#mysanta').data('editurl');
    url = url.replace("listUrl", listUrl);
    url = url.replace("participantUrl", participantUrl);
    if ($('#email_' + participantUrl).has('input').length == 0) {
        makeEditForm(participantUrl, listUrl, name, email);
    }
}

function submitEditForm(listUrl,participantUrl) {
    var url = $('table#mysanta').data('editurl');
    url = url.replace("listUrl", listUrl);
    url = url.replace("participantUrl", participantUrl);
    var name = $('#input_name_' + participantUrl).val();
    var email = $('#input_email_' + participantUrl).val();
    $('#input_name_' + participantUrl).prop('disabled', true);
    $('#input_email_' + participantUrl).prop('disabled', true);
    $('#submit_btn_' + participantUrl).prop('disabled', true);
    $('#submit_btn_' + participantUrl).html('<i class="fa fa-spinner fa-spin"></i>');
    $("#alertspan").html('');

    $.ajax({
        type: 'POST',
        url: url,
        data: {
            name: name,
            email: email
        },
        success: function(data){
            if (data.success) {
                $("#alertspan").html('<div class="alert alert-success" role="alert">' + data.message + '</div>');
                $('#name_' + participantUrl).html(data.name);
                $('#email_' + participantUrl).html(data.email);
            } else {
                $("#alertspan").html('<div class="alert alert-danger" role="alert">'+ data.message +'</div>');
                makeEditForm(participantUrl, listUrl, data.name, data.email);
            }
        }
    });
}

function makeEditForm(participantUrl, listUrl, name, email){
    var saveBtnText = $('table#mysanta').data('save-btn-text');
    $('#name_' + participantUrl).html(
        '<input type="text" id="input_name_' + participantUrl + '" class="form-control input_edit_name" name="name" value="' + name + '" data-hj-masked>'
    );
    $('#email_' + participantUrl).html(
        '<input type="text" id="input_email_' + participantUrl + '" class="form-control input_edit_email" name="email" value="' + email + '" data-hj-masked>&nbsp;' +
        '<button class="btn btn-small btn-primary save-edit" id="submit_btn_' + participantUrl + '" data-listurl="'+listUrl +'" data-participant-url="' + participantUrl + '"><i class="fa fa-check"></i> '+saveBtnText+'</button>'
    );
}

function attachAction(listUrl, participantUrl) {
    var url = $('form#delete-participant-form').data('action');
    url = url.replace('listUrl', listUrl);
    url = url.replace('participantUrl', participantUrl);
    $('#delete-participant-form').attr('action', url);
}
