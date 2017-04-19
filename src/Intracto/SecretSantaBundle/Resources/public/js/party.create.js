function addNewParticipant(collectionHolder, email, name) {
    // Get participant prototype as defined in attribute data-prototype
    var prototype = collectionHolder.attr('data-prototype');
    // Adjust participant prototype for correct naming
    var number_of_participants = collectionHolder.children().length - 1; // Note, owner is not counted as participant
    var newFormHtml = prototype.replace(/__name__/g,
        number_of_participants).replace(/__participantcount__/g,
        number_of_participants + 1);
    // Add new participant to party with animation
    var newForm = $(newFormHtml);
    collectionHolder.append(newForm);

    if ( (typeof(email)!=='undefined') && (typeof(name)!=='undefined') ) {
        // email and name provided, fill in the blanks
        $(newForm).find('.participant-mail').attr('value', email);
        $(newForm).find('.participant-name').attr('value', name);
        newForm.show();
    } else {
        newForm.show(300);
    }

    // Handle delete button events
    bindDeleteButtonEvents();
    // Remove disabled state on delete-buttons
    $('.remove-participant').removeClass('disabled');
}
function bindDeleteButtonEvents() {
    // Loop over all delete buttons
    $('button.remove-participant').each(function (i) {
        // Remove any previously binded event
        $(this).off('click');
        // Bind event
        $(this).click(function (e) {
            e.preventDefault();
            $('table tr.participant.not-owner:gt(' + i + ')').each(function (j) {
                // Move values from next row to current row
                var next_row_name = $('table tr.participant.not-owner:eq(' + (i + j + 1) + ') input.participant-name').val();
                var next_row_mail = $('table tr.participant.not-owner:eq(' + (i + j + 1) + ') input.participant-mail').val();
                $('table tr.participant.not-owner:eq(' + (i + j) + ') input.participant-name').val(next_row_name);
                $('table tr.participant.not-owner:eq(' + (i + j) + ') input.participant-mail').val(next_row_mail);
            });
            // Delete last row
            $('table tr.participant.not-owner:last').remove();
            // Remove delete events when deletable participants < 3
            if ($('table tr.participant.not-owner').length < 3) {
                $('table tr.participant.not-owner button.remove-participant').addClass('disabled');
                $('table tr.participant.not-owner button.remove-participant').off('click');
            }
        });
    });
}
/* Variables */
var collectionHolder = $('table.participants tbody');
/* Document Ready */
jQuery(document).ready(function () {
    //Add eventlistener on add-new-participant button
    $('.add-new-participant').click(function (e) {
        e.preventDefault();
        addNewParticipant(collectionHolder);
    });
    // If form has more then 3 participants, provide delete functionality
    if ($('table tr.participant').length > 3) {
        bindDeleteButtonEvents();
        $('.remove-participant').removeClass('disabled');
    }
    // Add smooth scroll
    $('a.btn-started').click(function () {
        $.smoothScroll({
            scrollTarget: '#mysanta'
        });
        return false;
    });
});