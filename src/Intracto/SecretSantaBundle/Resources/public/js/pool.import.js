
/* Variables */
var collectionHolder = $('table.entries tbody');

/* Document Ready */
jQuery(document).ready(function() {

    //Add eventlistener on add-new-entry button
    $('.add-import-entry').click(function(e) {
        e.preventDefault();
        $('.row-import-entries').show(300);
    });

    $('.btn-import-cancel').click(function(e) {
        e.preventDefault();
        $('#importCSV').val('');
        $('.row-import-entries').hide(300);
    });

    $('.add-import-entry-do').click(function(e) {
        e.preventDefault();

        var entries = $.csv.toArrays($('.add-import-entry-data').val(), {headers: false, seperator: ',', delimiter: '"' } );

        if(typeof(entries[0]) === 'undefined') {
            return;
        }

        var added = 0; var lookForEmpty = true;
        for(var entry in entries) {

            var email = '';
            var name = '';

            for(var field in entries[entry]) {
                // very basic check, can/should probably be done some other way
                // check if this is an e-mailaddress
                if (email == '' && entries[entry][field].indexOf('@') != -1) {
                    email = entries[entry][field];
                } else {
                    // either e-mail already found, or no @ sign found
                    name = entries[entry][field];
                }
            }

            if (email != '') {
                if (name == '') name = email;

                // check to see if list contains empty entries
                if (lookForEmpty) {
                    // if so, use them, otherwise add new
                    elem = $(collectionHolder).find('.entry-name[value=""],.entry-name:not([value])');
                    if (elem.length > 0) {
                        row = $(elem[0]).parent().parent();
                        $(row).find('.entry-name').attr('value', name);
                        $(row).find('.entry-mail').attr('value', email);
                    } else {
                        // prevent lookup on next iteration
                        lookForEmpty = false;
                        addNewEntry(collectionHolder, email, name);
                    }
                } else {
                    addNewEntry(collectionHolder, email, name);
                }
                added++;
            }

        }

        if (added > 0) {
            $('.add-import-entry-data').val('');
            $('.row-import-entries').hide(300);
        }

    });

    $('.add-import-entry-data').change(function() {
        // replace tab and ; delimiter with ,
        data = $(this).val().replace(/\t/g, ",").replace(/;/g, ",");
        if (data != $(this).text()) {
            $(this).val(data);
        }
    });
});

var dropImportCSV = document.getElementById('importCSV');
var errorImportCSV = document.getElementById('errorImportCSV');

dropImportCSV.addEventListener('drop', importCSV, false);

function importCSV(e) {
    e.stopPropagation();
    e.preventDefault();

    var files = e.dataTransfer.files;
    var number = files.length;

    switch(number) {
        case 1:
            parseFiles(files);
            errorImportCSV.innerHTML = "";
            break;

        default:
            errorImportCSV.innerHTML = 'Only one file can be uploaded at a time';
            break;
    }
}

function parseFiles(files) {
    var file = files[0];
    var reader = new FileReader();

    reader.readAsText(file, 'UTF-8');
    reader.onload = handleReaderLoad;
}

function handleReaderLoad(e) {
    var csv = e.target.result;
    var splitCSVFile = csv.split(";");

    dropImportCSV.innerHTML = splitCSVFile;
}