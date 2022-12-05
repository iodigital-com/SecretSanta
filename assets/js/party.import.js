require('jquery-csv');
var createModule = require('./party.create');

$.extend($.expr[':'],{
    inputEmpty: function(el){
        return $(el).val() === "";
    }
});

/* Variables */
var collectionHolder = $('table.participants tbody');
var dropImportCSV = document.getElementById('importCSV');
var errorImportCSV = document.getElementById('errorImportCSV');
var warningImportCSV = document.getElementById('warningImportCSV');

/* Document Ready */
jQuery(document).ready(function () {

    //Add eventlistener on add-new-participant button
    $('.add-import-participant').click(function (e) {
        e.preventDefault();
        $('.row-import-participants').show(300);
    });

    $('.btn-import-cancel').click(function (e) {
        e.preventDefault();
        $('#importCSV').val('');
        $('#errorImportCSV').hide();
        $('#warningImportCSV').hide();
        $('.row-import-participants').hide(300);
    });

    $('.add-import-participant-do').click(function (e) {
        e.preventDefault();

        var participants = $.csv.toArrays($('.add-import-participant-data').val(), {
            headers: false,
            seperator: ',',
            delimiter: '"'
        });

        if (typeof(participants[0]) === 'undefined') {
            return;
        }

        if (participants[0][1].indexOf('@') == -1) {
            participants.splice(0, 1);
        }

        var added = 0;
        var lookForEmpty = true;
        for (var participant in participants) {

            var email = '';
            var name = '';

            for (var field in participants[participant]) {
                // very basic check, can/should probably be done some other way
                // check if this is an e-mailaddress
                if (email == '' && participants[participant][field].indexOf('@') != -1) {
                    email = participants[participant][field];
                } else {
                    // either e-mail already found, or no @ sign found
                    name = participants[participant][field];
                }
            }

            if (email != '') {
                if (name == '') name = email;

                // check to see if list contains empty participants
                if (lookForEmpty) {
                    // if so, use them, otherwise add new
                    elem = $(collectionHolder).find('.participant-name:inputEmpty');
                    if (elem.length > 0) {
                        row = $(elem[0]).parent().parent();
                        $(row).find('.participant-name').val(name);
                        $(row).find('.participant-mail').val(email);
                    } else {
                        // prevent lookup on next iteration
                        lookForEmpty = false;
                        createModule.addNewParticipant(collectionHolder, email, name);
                    }
                } else {
                    createModule.addNewParticipant(collectionHolder, email, name);
                }
                added++;
            }

        }

        if (added > 0) {
            $('.add-import-participant-data').val('');
            $('.row-import-participants').hide(300);
        }

    });

    $('.add-import-participant-data').change(function () {
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
