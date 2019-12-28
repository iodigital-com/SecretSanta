import 'jquery-csv';
import { addNewParticipant } from './party-create';

/* Variables */
var collectionHolder = $('table.participants tbody');
var dropImportCSV = document.getElementById('importCSV');
if(!(dropImportCSV instanceof HTMLTextAreaElement)){
    throw new TypeError('Element with ID "importCSV" is not an HTMLTextAreaElement.');
}
var errorImportCSV = document.getElementById('errorImportCSV');
if(!errorImportCSV){
    throw new TypeError('Element with ID "errorImportCSV" is not an HTMLElement.');
}
var warningImportCSV = document.getElementById('warningImportCSV');
if(!warningImportCSV){
    throw new TypeError('Element with ID "warningImportCSV" is not an HTMLElement.');
}

/* Document Ready */
$(document).ready(function () {

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

        var participants = $.csv.toArrays(($('.add-import-participant-data').val() as string), {
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
                    const elem = $(collectionHolder).find('.participant-name[value=""],.participant-name:not([value])');
                    if (elem.length > 0) {
                        const row = $(elem[0]).parent().parent();
                        $(row).find('.participant-name').attr('value', name);
                        $(row).find('.participant-mail').attr('value', email);
                    } else {
                        // prevent lookup on next iteration
                        lookForEmpty = false;
                        addNewParticipant(collectionHolder, email, name);
                    }
                } else {
                    addNewParticipant(collectionHolder, email, name);
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
        const data = ($(this).val() as string).replace(/\t/g, ",").replace(/;/g, ",");
        if (data != $(this).text()) {
            $(this).val(data);
        }
    });
});

dropImportCSV.addEventListener('dragenter', function (e) {
    e.stopPropagation();
    e.preventDefault();
});

dropImportCSV.addEventListener('dragover', function (e) {
    e.stopPropagation();
    e.preventDefault();

    return false;
});

dropImportCSV.addEventListener('drop', importCSV, false);

function importCSV(e: DragEvent) {
    e.stopPropagation();
    e.preventDefault();

    if(!e.dataTransfer){
        return;
    }
    var files = e.dataTransfer.files;
    var number = files.length;

    switch (number) {
        case 1:
            parseFiles(files);
            warningImportCSV!.style.display = 'none';
            break;

        default:
            warningImportCSV!.style.display = 'block';
            break;
    }
}

function parseFiles(files: FileList) {
    var file = files[0];
    var fileName = file['name'];
    var fileExtension = fileName.replace(/^.*\./, '');

    switch (fileExtension) {
        case 'csv':
        case 'txt':
            errorImportCSV!.style.display = 'none';

            var reader = new FileReader();

            reader.readAsText(file, 'UTF-8');
            reader.onload = handleReaderLoad;
            break;

        default:
            errorImportCSV!.style.display = 'block';
            break;
    }
}

function handleReaderLoad(e: ProgressEvent<FileReader>) {
    var csv = e.target?.result?.toString() || '';

    if(dropImportCSV instanceof HTMLTextAreaElement){
        dropImportCSV.value = csv.split(';').join(',');
    } else {
        throw new TypeError(`Element with id "importCSV" is not a HTMLTextAreaElement.`);
    }
}
