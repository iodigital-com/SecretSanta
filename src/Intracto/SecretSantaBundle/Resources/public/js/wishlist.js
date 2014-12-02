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
    $('button.remove-entry').each(function(i) {
        // Remove any previously binded event
        $(this).off('click');

        // Bind event
        $(this).click(function(e) {
            e.preventDefault();
            $(this).parents("tr.wishlistitem").remove();
            resetRanks();
        });

    });
}

function resetRanks() {
    $('table.entries tbody tr').each(function (i) {
        $(this).find('td input[type="hidden"]').val(i + 1);
        $(this).find('td span.rank').text(i + 1);
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

    bindDeleteButtonEvents();
    $('.remove-entry').removeClass('disabled');

    // sortable
    $("table.entries tbody").sortable({
        stop: function () {
            resetRanks();
        }
    }).disableSelection();

	$('table.entries tbody').bind('click.sortable mousedown.sortable',function(ev){
		ev.target.focus();
	});
		
});