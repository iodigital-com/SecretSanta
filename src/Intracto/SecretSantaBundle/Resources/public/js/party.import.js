/* Variables */
var collectionHolder = $('table.entries tbody');
var dropImportCSV = document.getElementById('importCSV');
var errorImportCSV = document.getElementById('errorImportCSV');
var warningImportCSV = document.getElementById('warningImportCSV');

/* Document Ready */
jQuery(document).ready(function () {

    //Add eventlistener on add-new-entry button
    $('.add-import-entry').click(function (e) {
        e.preventDefault();
        $('.row-import-entries').show(300);
    });

    $('.btn-import-cancel').click(function (e) {
        e.preventDefault();
        $('#importCSV').val('');
        $('#errorImportCSV').hide();
        $('#warningImportCSV').hide();
        $('.row-import-entries').hide(300);
    });

    $('.add-import-entry-do').click(function (e) {
        e.preventDefault();

        var entries = $.csv.toArrays($('.add-import-entry-data').val(), {
            headers: false,
            seperator: ',',
            delimiter: '"'
        });

        if (typeof(entries[0]) === 'undefined') {
            return;
        }

        if (entries[0][1].indexOf('@') == -1) {
            entries.splice(0, 1);
        }

        var added = 0;
        var lookForEmpty = true;
        for (var entry in entries) {

            var email = '';
            var name = '';

            for (var field in entries[entry]) {
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

    $('.add-import-entry-data').change(function () {
        // replace tab and ; delimiter with ,
        data = $(this).val().replace(/\t/g, ",").replace(/;/g, ",");
        if (data != $(this).text()) {
            $(this).val(data);
        }
    });
});

dropImportCSV.addEventListener('dragenter', function (e) {
    e.stopPropagation(e);
    e.preventDefault(e);
});

dropImportCSV.addEventListener('dragover', function (e) {
    e.stopPropagation(e);
    e.preventDefault(e);

    return false;
});

dropImportCSV.addEventListener('drop', importCSV, false);

function importCSV(e) {
    e.stopPropagation(e);
    e.preventDefault(e);

    var files = e.dataTransfer.files;
    var number = files.length;

    switch (number) {
        case 1:
            parseFiles(files);
            warningImportCSV.style.display = 'none';
            break;

        default:
            warningImportCSV.style.display = 'block';
            break;
    }
}

function parseFiles(files) {
    var file = files[0];
    var fileName = file['name'];
    var fileExtension = fileName.replace(/^.*\./, '');

    switch (fileExtension) {
        case 'csv':
        case 'txt':
            errorImportCSV.style.display = 'none';

            var reader = new FileReader();

            reader.readAsText(file, 'UTF-8');
            reader.onload = handleReaderLoad;
            break;

        default:
            errorImportCSV.style.display = 'block';
            break;
    }
}

function handleReaderLoad(e) {
    var csv = e.target.result;

    dropImportCSV.value = csv.split(';');
}