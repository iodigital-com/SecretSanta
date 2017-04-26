function addNewParticipant(collectionHolder) {

    // remove .noitems if present
    $('.noitems').remove();

    // Get participant prototype as defined in attribute data-prototype
    var prototype = collectionHolder.attr('data-prototype');
    // Adjust participant prototype for correct naming
    var number_of_participants = collectionHolder.children().length; // Note, owner is not counted as participant
    var newFormHtml = prototype.replace(/__name__/g, number_of_participants).replace(/__participantcount__/g, number_of_participants + 1);

    // Add new participant to party with animation
    var newForm = $(newFormHtml);
    collectionHolder.append(newForm);
    newForm.show(300);

    // Handle delete button events
    bindDeleteButtonEvents();

    // Remove disabled state on delete-buttons
    $('.remove-participant').removeClass('disabled');

    // reset ranks
    resetRanks();

}

function bindDeleteButtonEvents() {
    // Loop over all delete buttons
    $('button.remove-participant').each(function (i) {
        // Remove any previously binded event
        $(this).off('click');

        // Bind event
        $(this).click(function (e) {
            e.preventDefault();

            if ($(this).parents("tr.wishlistitem").hasClass('new-row')) {
                $(this).parents("tr.wishlistitem").remove();
                $('.add-new-participant').show();
            }

            var newRowValue = $('.new-row .wishlistitem-description').val();
            if (typeof newRowValue != 'undefined' && newRowValue == '') {
                $('.new-row').remove();
                $('.add-new-participant').show();
            }

            // XXX: last item can't be removed because an empty form can't be saved. This is a workaround
            if ($('.wishlistitem-description').length == 1){
                $('.wishlistitem-description').val('');
            } else {
                $(this).parents("tr.wishlistitem").remove();
            }

            $('.ajax-response').children().hide();
            $('.ajax-response .empty').hide();
            $('.ajax-response .removed').show();

            resetRanks();
            ajaxSaveWishlist();
        });
    });
}

function resetRanks() {
    $('table.participants tbody tr').each(function (i) {
        $(this).find('td input[type="hidden"]').val(i + 1);
        $(this).find('td span.rank').text(i + 1);
    });
}

function ajaxSaveWishlist() {
    var newRowValue = $('.new-row .wishlistitem-description').val();
    if (typeof newRowValue != 'undefined' && newRowValue == '') {
        $('.ajax-response .empty').show();

        return false;
    }

    $('.ajax-response .empty').hide();
    var formData = $('#add_item_to_wishlist_form').serializeArray();
    var url = $('#add_item_to_wishlist_form').attr('action')

    $.ajax({
        type: 'POST',
        url: url,
        data: formData
    });
}

/* Variables */
var collectionHolder = $('table.participants tbody');

/* Document Ready */
jQuery(document).ready(function () {

    //Add eventlistener on add-new-participant button
    $('.add-new-participant').click(function (e) {
        e.preventDefault();

        $('.add-new-participant').hide();

        addNewParticipant(collectionHolder);
    });

    $('.update-participant').click(function (e) {
        e.preventDefault();

        $(this).hide();
        $('.ajax-response').children().hide();
        $('.ajax-response .updated').show();

        ajaxSaveWishlist();
    });

    $('#add_item_to_wishlist_form').submit(function (e) {
        e.preventDefault();

        var submitButton = $(this).find('button[type=submit]');
        submitButton.hide();

        $('.add-new-participant').hide();
        $('.ajax-response').children().hide();
        $('.ajax-response .added').show();
        $('tr.wishlistitem').removeClass('new-row');

        ajaxSaveWishlist();
        addNewParticipant(collectionHolder);
    });

    $('.wishlistitem').on('keydown', '.wishlistitem-description', function () {
        $(this).closest('.wishlistitem').find('button.update-participant').show();
    });

    bindDeleteButtonEvents();
    $('.remove-participant').removeClass('disabled');

    // sortable
    $("table.participants tbody").sortable({
        stop: function () {
            resetRanks();
            ajaxSaveWishlist();
        }
    });

    $('table.participants tbody').bind('click.sortable mousedown.sortable', function (ev) {
        ev.target.focus();
    });

});
