function addNewEntry(collectionHolder) {

    // remove .noitems if present
    $('.noitems').remove();

    // Get entry prototype as defined in attribute data-prototype
    var prototype = collectionHolder.attr('data-prototype');
    // Adjust entry prototype for correct naming
    var number_of_entries = collectionHolder.children().length; // Note, owner is not counted as entry
    var newFormHtml = prototype.replace(/__name__/g, number_of_entries).replace(/__entrycount__/g, number_of_entries + 1);

    // Add new entry to pool with animation
    var newForm = $(newFormHtml);
    collectionHolder.append(newForm);
    newForm.show(300);

    // Handle delete button events
    bindDeleteButtonEvents();

    // Remove disabled state on delete-buttons
    $('.remove-entry').removeClass('disabled');

    // reset ranks
    resetRanks();

}

function bindDeleteButtonEvents() {
    // Loop over all delete buttons
    $('button.remove-entry').each(function (i) {
        // Remove any previously binded event
        $(this).off('click');

        // Bind event
        $(this).click(function (e) {
            e.preventDefault();

            if ($(this).parents("tr.wishlistitem").hasClass('new-row')) {
                $(this).parents("tr.wishlistitem").remove();
                $('.add-new-entry').show();
            }

            var newRowValue = $('.new-row .wishlistitem-description').val();
            if (typeof newRowValue != 'undefined' && newRowValue == '') {
                $('.ajax-response .empty').show();

                return false;
            }

            $('.ajax-response').children().hide();
            $('.ajax-response .empty').hide();
            $('.ajax-response .removed').show();
            $(this).parents("tr.wishlistitem").remove();

            resetRanks();
            ajaxSaveWishlist();
        });
    });
}

function resetRanks() {
    $('table.entries tbody tr').each(function (i) {
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

    $.ajax({
        type: 'POST',
        data: formData
    }, function (data) {
        if (data.responseCode == 200) {
            console.log('Succes!');
        }
    });
}

/* Variables */
var collectionHolder = $('table.entries tbody');

/* Document Ready */
jQuery(document).ready(function () {

    //Add eventlistener on add-new-entry button
    $('.add-new-entry').click(function (e) {
        e.preventDefault();

        $('.add-new-entry').hide();

        addNewEntry(collectionHolder);
    });

    $('.update-entry').click(function (e) {
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

        $('.add-new-entry').hide();
        $('.ajax-response').children().hide();
        $('.ajax-response .added').show();
        $('tr.wishlistitem').removeClass('new-row');

        ajaxSaveWishlist();
        addNewEntry(collectionHolder);
    });

    $('.wishlistitem').on('keydown', '.wishlistitem-description', function () {
        $(this).closest('.wishlistitem').find('button.update-entry').show();
    });

    bindDeleteButtonEvents();
    $('.remove-entry').removeClass('disabled');

    // sortable
    $("table.entries tbody").sortable({
        stop: function () {
            resetRanks();
            ajaxSaveWishlist();
        }
    });

    $('table.entries tbody').bind('click.sortable mousedown.sortable', function (ev) {
        ev.target.focus();
    });

});