function addNewEntry(collectionHolder) {
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
}

function bindDeleteButtonEvents() {
    // Loop over all delete buttons
    $('button.remove-entry').each(function(i) {
        // Remove any previously binded event
        $(this).off('click');

        // Bind event
        $(this).click(function(e) {

            e.preventDefault();

            $('table tr.entry.not-owner:gt(' + i + ')').each(function(j) {
                // Move values from next row to current row
                var next_row_name = $('table tr.entry.not-owner:eq(' + (i + j + 1) + ') input.entry-name').val();
                var next_row_mail = $('table tr.entry.not-owner:eq(' + (i + j + 1) + ') input.entry-mail').val();
                $('table tr.entry.not-owner:eq(' + (i + j) + ') input.entry-name').val(next_row_name);
                $('table tr.entry.not-owner:eq(' + (i + j) + ') input.entry-mail').val(next_row_mail);
            });

            // Delete last row
            $('table tr.entry.not-owner:last').remove();

            // Remove delete events when deletable entries < 3
            if ($('table tr.entry.not-owner').length < 3) {
                $('table tr.entry.not-owner button.remove-entry').addClass('disabled');
                $('table tr.entry.not-owner button.remove-entry').off('click');

            }
        });

    });
}

/* Variables */
var collectionHolder = $('table.entries tbody');

/* Document Ready */
jQuery(document).ready(function() {

    //Add eventlistener on add-new-entry button
    $('.add-new-entry').click(function(e) {
        e.preventDefault();
        addNewEntry(collectionHolder);
    });

    // If form has more then 3 entries, provide delete functionality
    if($('table tr.entry').length > 3){
        bindDeleteButtonEvents();
        $('.remove-entry').removeClass('disabled');
    }

    // Add smooth scroll
    $('a.btn-started').click(function() {
        $.smoothScroll({
            scrollTarget: '#mysanta'
        });
        return false;
    });

});