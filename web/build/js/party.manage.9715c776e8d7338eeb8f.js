webpackJsonp([10],{

/***/ "./src/Intracto/SecretSantaBundle/Resources/public/js/party.manage.js":
/*!****************************************************************************!*\
  !*** ./src/Intracto/SecretSantaBundle/Resources/public/js/party.manage.js ***!
  \****************************************************************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function($) {$(document).ready(function () {
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

    if (Modernizr.inputtypes.date == true) {
        $("#intracto_secretsantabundle_updatepartydetailstype_eventdate").click(function (e) {
            $(this).datepicker({ dateFormat: 'dd-mm-yy' });
        });
    }

    $('.js-selector-participant').select2({ width: '100%' });

    $('.participant-edit-icon').on('click', function () {
        editParticipant($(this).data('listurl'), $(this).data('participant-url'));
    });

    $(document).on('click', '.save-edit', function () {
        submitEditForm($(this).data('listurl'), $(this).data('participant-url'));
    });
});

function showExcludeErrors() {
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

function submitEditForm(listUrl, participantUrl) {
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
        success: function success(data) {
            if (data.success) {
                $("#alertspan").html('<div class="alert alert-success" role="alert">' + data.message + '</div>');
                $('#name_' + participantUrl).html(name);
                $('#email_' + participantUrl).html(email);
            } else {
                $("#alertspan").html('<div class="alert alert-danger" role="alert">' + data.message + '</div>');
                makeEditForm(participantUrl, listUrl, name, email);
            }
        }
    });
}

function makeEditForm(participantUrl, listUrl, name, email) {
    var saveBtnText = $('table#mysanta').data('save-btn-text');
    $('#name_' + participantUrl).html('<input type="text" id="input_name_' + participantUrl + '" class="form-control input_edit_name" name="name" value="' + name + '" data-hj-masked>');
    $('#email_' + participantUrl).html('<input type="text" id="input_email_' + participantUrl + '" class="form-control input_edit_email" name="email" value="' + email + '" data-hj-masked>&nbsp;' + '<button class="btn btn-small btn-primary save-edit" id="submit_btn_' + participantUrl + '" data-listurl="' + listUrl + '" data-participant-url="' + participantUrl + '"><i class="fa fa-check"></i> ' + saveBtnText + '</button>');
}

function attachAction(listUrl, participantUrl) {
    var url = $('form#delete-participant-form').data('action');
    url = url.replace('listUrl', listUrl);
    url = url.replace('participantUrl', participantUrl);
    $('#delete-participant-form').attr('action', url);
}
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ })

},["./src/Intracto/SecretSantaBundle/Resources/public/js/party.manage.js"]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9zcmMvSW50cmFjdG8vU2VjcmV0U2FudGFCdW5kbGUvUmVzb3VyY2VzL3B1YmxpYy9qcy9wYXJ0eS5tYW5hZ2UuanMiXSwibmFtZXMiOlsiJCIsImRvY3VtZW50IiwicmVhZHkiLCJjbGljayIsImUiLCJzaG93IiwiYXR0ciIsImZvY3VzIiwiaGlkZSIsImxpc3RVcmwiLCJkYXRhIiwicGFydGljaXBhbnRVcmwiLCJhdHRhY2hBY3Rpb24iLCJNb2Rlcm5penIiLCJpbnB1dHR5cGVzIiwiZGF0ZSIsImRhdGVwaWNrZXIiLCJkYXRlRm9ybWF0Iiwic2VsZWN0MiIsIndpZHRoIiwib24iLCJlZGl0UGFydGljaXBhbnQiLCJzdWJtaXRFZGl0Rm9ybSIsInNob3dFeGNsdWRlRXJyb3JzIiwiY29sbGFwc2UiLCJhbmltYXRlIiwic2Nyb2xsVG9wIiwib2Zmc2V0IiwidG9wIiwiZW1haWwiLCJodG1sIiwibmFtZSIsInVybCIsInJlcGxhY2UiLCJoYXMiLCJsZW5ndGgiLCJtYWtlRWRpdEZvcm0iLCJ2YWwiLCJwcm9wIiwiYWpheCIsInR5cGUiLCJzdWNjZXNzIiwibWVzc2FnZSIsInNhdmVCdG5UZXh0Il0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7O0FBQUEseUNBQUFBLEVBQUVDLFFBQUYsRUFBWUMsS0FBWixDQUFrQixZQUFZO0FBQzFCRixNQUFFLGFBQUYsRUFBaUJHLEtBQWpCLENBQXVCLFVBQVVDLENBQVYsRUFBYTtBQUNoQ0osVUFBRSxpQkFBRixFQUFxQkssSUFBckI7QUFDQUwsVUFBRSxhQUFGLEVBQWlCTSxJQUFqQixDQUFzQixVQUF0QixFQUFrQyxJQUFsQztBQUNBTixVQUFFLHNCQUFGLEVBQTBCTyxLQUExQjtBQUNILEtBSkQ7QUFLQVAsTUFBRSxvQkFBRixFQUF3QkcsS0FBeEIsQ0FBOEIsVUFBVUMsQ0FBVixFQUFhO0FBQ3ZDSixVQUFFLGlCQUFGLEVBQXFCUSxJQUFyQjtBQUNBUixVQUFFLGFBQUYsRUFBaUJNLElBQWpCLENB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RDtBQUN2RCxRQUFJYyxjQUFjM0MsRUFBRSxlQUFGLEVBQW1CVSxJQUFuQixDQUF3QixlQUF4QixDQUFsQjtBQUNBVixNQUFFLFdBQVdXLGNBQWIsRUFBNkJtQixJQUE3QixDQUNJLHVDQUF1Q25CLGNBQXZDLEdBQXdELDREQUF4RCxHQUF1SG9CLElBQXZILEdBQThILG1CQURsSTtBQUdBL0IsTUFBRSxZQUFZVyxjQUFkLEVBQThCbUIsSUFBOUIsQ0FDSSx3Q0FBd0NuQixjQUF4QyxHQUF5RCw4REFBekQsR0FBMEhrQixLQUExSCxHQUFrSSx5QkFBbEksR0FDQSxxRUFEQSxHQUN3RWxCLGNBRHhFLEdBQ3lGLGtCQUR6RixHQUM0R0YsT0FENUcsR0FDcUgsMEJBRHJILEdBQ2tKRSxjQURsSixHQUNtSyxnQ0FEbkssR0FDb01nQyxXQURwTSxHQUNnTixXQUZwTjtBQUlIOztBQUVELFNBQVMvQixZQUFULENBQXNCSCxPQUF0QixFQUErQkUsY0FBL0IsRUFBK0M7QUFDM0MsUUFBSXFCLE1BQU1oQyxFQUFFLDhCQUFGLEVBQWtDVSxJQUFsQyxDQUF1QyxRQUF2QyxDQUFWO0FBQ0FzQixVQUFNQSxJQUFJQyxPQUFKLENBQVksU0FBWixFQUF1QnhCLE9BQXZCLENBQU47QUFDQXVCLFVBQU1BLElBQUlDLE9BQUosQ0FBWSxnQkFBWixFQUE4QnRCLGNBQTlCLENBQU47QUFDQVgsTUFBRSwwQkFBRixFQUE4Qk0sSUFBOUIsQ0FBbUMsUUFBbkMsRUFBNkMwQixHQUE3QztBQUNILEMiLCJmaWxlIjoianMvcGFydHkubWFuYWdlLjk3MTVjNzc2ZThkNzMzOGVlYjhmLmpzIiwic291cmNlc0NvbnRlbnQiOlsiJChkb2N1bWVudCkucmVhZHkoZnVuY3Rpb24gKCkge1xuICAgICQoJyNidG5fZGVsZXRlJykuY2xpY2soZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgJCgnI2RlbGV0ZS13YXJuaW5nJykuc2hvdygpO1xuICAgICAgICAkKCcjYnRuX2RlbGV0ZScpLmF0dHIoJ2Rpc2FibGVkJywgdHJ1ZSk7XG4gICAgICAgICQoJyNkZWxldGUtY29uZmlybWF0aW9uJykuZm9jdXMoKTtcbiAgICB9KTtcbiAgICAkKCcjYnRuX2RlbGV0ZV9jYW5jZWwnKS5jbGljayhmdW5jdGlvbiAoZSkge1xuICAgICAgICAkKCcjZGVsZXRlLXdhcm5pbmcnKS5oaWRlKCk7XG4gICAgICAgICQoJyNidG5fZGVsZXRlJykuYXR0cignZGlzYWJsZWQnLCBmYWxzZSkuZm9jdXMoKTtcbiAgICB9KTtcblxuICAgICQoJyNidG5fYWRkJykuY2xpY2soZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgJCgnI2FkZC1wYXJ0aWNpcGFudCcpLnNob3coKTtcbiAgICAgICAgJCgnI2J0bl9hZGQnKS5hdHRyKCdkaXNhYmxlZCcsIHRydWUpO1xuICAgICAgICAkKCcjYWRkLXBhcnRpY2lwYW50LW5hbWUnKS5mb2N1cygpO1xuICAgIH0pO1xuXG4gICAgJCgnI2J0bl9hZGRfY2FuY2VsJykuY2xpY2soZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgJCgnI2FkZC1wYXJ0aWNpcGFudCcpLmhpZGUoKTtcbiAgICAgICAgJCgnI2J0bl9hZGQnKS5hdHRyKCdkaXNhYmxlZCcsIGZhbHNlKS5mb2N1cygpO1xuICAgIH0pO1xuXG4gICAgJCgnI2J0bl91cGRhdGUnKS5jbGljayhmdW5jdGlvbiAoZSkge1xuICAgICAgICAkKCcjdXBkYXRlLXBhcnR5LWRldGFpbHMnKS5zaG93KCk7XG4gICAgICAgICQoJyNidG5fdXBkYXRlJykuYXR0cignZGlzYWJsZWQnLCB0cnVlKTtcbiAgICB9KTtcblxuICAgICQoJyNidG5fdXBkYXRlX2NhbmNlbCcpLmNsaWNrKGZ1bmN0aW9uIChlKSB7XG4gICAgICAgICQoJyN1cGRhdGUtcGFydHktZGV0YWlscycpLmhpZGUoKTtcbiAgICAgICAgJCgnI2J0bl91cGRhdGUnKS5hdHRyKCdkaXNhYmxlZCcsIGZhbHNlKS5mb2N1cygpO1xuICAgIH0pO1xuXG4gICAgJCgnLmxpbmtfcmVtb3ZlX3BhcnRpY2lwYW50JykuY2xpY2soZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgJCgnI2RlbGV0ZS1wYXJ0aWNpcGFudCcpLnNob3coKTtcbiAgICAgICAgJCgnLmxpbmtfcmVtb3ZlX3BhcnRpY2lwYW50JykuYXR0cignZGlzYWJsZWQnLCB0cnVlKTtcbiAgICAgICAgJCgnI2RlbGV0ZS1wYXJ0aWNpcGFudC1jb25maXJtYXRpb24nKS5mb2N1cygpO1xuICAgICAgICB2YXIgbGlzdFVybCA9ICQodGhpcykuZGF0YSgnbGlzdHVybCcpO1xuICAgICAgICB2YXIgcGFydGljaXBhbnRVcmwgPSAkKHRoaXMpLmRhdGEoJ3BhcnRpY2lwYW50Jyk7XG4gICAgICAgIGF0dGFjaEFjdGlvbihsaXN0VXJsLCBwYXJ0aWNpcGFudFVybCk7XG4gICAgfSk7XG5cbiAgICAkKCcuYnRuX3JlbW92ZV9wYXJ0aWNpcGFudF9jYW5jZWwnKS5jbGljayhmdW5jdGlvbiAoZSkge1xuICAgICAgICAkKCcjZGVsZXRlLXBhcnRpY2lwYW50JykuaGlkZSgpO1xuICAgICAgICAkKCcubGlua19yZW1vdmVfcGFydGljaXBhbnQnKS5hdHRyKCdkaXNhYmxlZCcsIGZhbHNlKTtcbiAgICB9KTtcblxuICAgIGlmIChNb2Rlcm5penIuaW5wdXR0eXBlcy5kYXRlID09IHRydWUpIHtcbiAgICAgICAgJChcIiNpbnRyYWN0b19zZWNyZXRzYW50YWJ1bmRsZV91cGRhdGVwYXJ0eWRldGFpbHN0eXBlX2V2ZW50ZGF0ZVwiKS5jbGljayhmdW5jdGlvbiAoZSkge1xuICAgICAgICAgICAgJCh0aGlzKS5kYXRlcGlja2VyKHtkYXRlRm9ybWF0OiAnZGQtbW0teXknfSk7XG4gICAgICAgIH0pO1xuICAgIH1cblxuICAgICQoJy5qcy1zZWxlY3Rvci1wYXJ0aWNpcGFudCcpLnNlbGVjdDIoeyB3aWR0aDogJzEwMCUnIH0pO1xuXG4gICAgJCgnLnBhcnRpY2lwYW50LWVkaXQtaWNvbicpLm9uKCdjbGljaycsIGZ1bmN0aW9uKCkge1xuICAgICAgICBlZGl0UGFydGljaXBhbnQoJCh0aGlzKS5kYXRhKCdsaXN0dXJsJyksICQodGhpcykuZGF0YSgncGFydGljaXBhbnQtdXJsJykpO1xuICAgIH0pO1xuXG4gICAgJChkb2N1bWVudCkub24oJ2NsaWNrJywgJy5zYXZlLWVkaXQnLCBmdW5jdGlvbigpe1xuICAgICAgICBzdWJtaXRFZGl0Rm9ybSgkKHRoaXMpLmRhdGEoJ2xpc3R1cmwnKSwgJCh0aGlzKS5kYXRhKCdwYXJ0aWNpcGFudC11cmwnKSk7XG4gICAgfSk7XG59KTtcblxuZnVuY3Rpb24gc2hvd0V4Y2x1ZGVFcnJvcnMoKSB7XG4gICAgJCgnI2NvbGxhcHNlZE1lc3NhZ2UnKS5jb2xsYXBzZSgnc2hvdycpO1xuICAgICQoJ2h0bWwsIGJvZHknKS5hbmltYXRlKHtcbiAgICAgICAgc2Nyb2xsVG9wOiAkKFwiI2NvbGxhcHNlZE1lc3NhZ2VcIikub2Zmc2V0KCkudG9wXG4gICAgfSwgMjAwMCk7XG59XG5cbmZ1bmN0aW9uIGVkaXRQYXJ0aWNpcGFudChsaXN0VXJsLCBwYXJ0aWNpcGFudFVybCkge1xuICAgIHZhciBlbWFpbCA9ICQoJyNlbWFpbF8nICsgcGFydGljaXBhbnRVcmwpLmh0bWwoKTtcbiAgICB2YXIgbmFtZSA9ICQoJyNuYW1lXycgKyBwYXJ0aWNpcGFudFVybCkuaHRtbCgpO1xuICAgIHZhciB1cmwgPSAkKCd0YWJsZSNteXNhbnRhJykuZGF0YSgnZWRpdHVybCcpO1xuICAgIHVybCA9IHVybC5yZXBsYWNlKFwibGlzdFVybFwiLCBsaXN0VXJsKTtcbiAgICB1cmwgPSB1cmwucmVwbGFjZShcInBhcnRpY2lwYW50VXJsXCIsIHBhcnRpY2lwYW50VXJsKTtcbiAgICBpZiAoJCgnI2VtYWlsXycgKyBwYXJ0aWNpcGFudFVybCkuaGFzKCdpbnB1dCcpLmxlbmd0aCA9PSAwKSB7XG4gICAgICAgIG1ha2VFZGl0Rm9ybShwYXJ0aWNpcGFudFVybCwgbGlzdFVybCwgbmFtZSwgZW1haWwpO1xuICAgIH1cbn1cblxuZnVuY3Rpb24gc3VibWl0RWRpdEZvcm0obGlzdFVybCxwYXJ0aWNpcGFudFVybCkge1xuICAgIHZhciB1cmwgPSAkKCd0YWJsZSNteXNhbnRhJykuZGF0YSgnZWRpdHVybCcpO1xuICAgIHVybCA9IHVybC5yZXBsYWNlKFwibGlzdFVybFwiLCBsaXN0VXJsKTtcbiAgICB1cmwgPSB1cmwucmVwbGFjZShcInBhcnRpY2lwYW50VXJsXCIsIHBhcnRpY2lwYW50VXJsKTtcbiAgICB2YXIgbmFtZSA9ICQoJyNpbnB1dF9uYW1lXycgKyBwYXJ0aWNpcGFudFVybCkudmFsKCk7XG4gICAgdmFyIGVtYWlsID0gJCgnI2lucHV0X2VtYWlsXycgKyBwYXJ0aWNpcGFudFVybCkudmFsKCk7XG4gICAgJCgnI2lucHV0X25hbWVfJyArIHBhcnRpY2lwYW50VXJsKS5wcm9wKCdkaXNhYmxlZCcsIHRydWUpO1xuICAgICQoJyNpbnB1dF9lbWFpbF8nICsgcGFydGljaXBhbnRVcmwpLnByb3AoJ2Rpc2FibGVkJywgdHJ1ZSk7XG4gICAgJCgnI3N1Ym1pdF9idG5fJyArIHBhcnRpY2lwYW50VXJsKS5wcm9wKCdkaXNhYmxlZCcsIHRydWUpO1xuICAgICQoJyNzdWJtaXRfYnRuXycgKyBwYXJ0aWNpcGFudFVybCkuaHRtbCgnPGkgY2xhc3M9XCJmYSBmYS1zcGlubmVyIGZhLXNwaW5cIj48L2k+Jyk7XG4gICAgJChcIiNhbGVydHNwYW5cIikuaHRtbCgnJyk7XG5cbiAgICAkLmFqYXgoe1xuICAgICAgICB0eXBlOiAnUE9TVCcsXG4gICAgICAgIHVybDogdXJsLFxuICAgICAgICBkYXRhOiB7XG4gICAgICAgICAgICBuYW1lOiBuYW1lLFxuICAgICAgICAgICAgZW1haWw6IGVtYWlsXG4gICAgICAgIH0sXG4gICAgICAgIHN1Y2Nlc3M6IGZ1bmN0aW9uKGRhdGEpe1xuICAgICAgICAgICAgaWYgKGRhdGEuc3VjY2Vzcykge1xuICAgICAgICAgICAgICAgICQoXCIjYWxlcnRzcGFuXCIpLmh0bWwoJzxkaXYgY2xhc3M9XCJhbGVydCBhbGVydC1zdWNjZXNzXCIgcm9sZT1cImFsZXJ0XCI+JyArIGRhdGEubWVzc2FnZSArICc8L2Rpdj4nKTtcbiAgICAgICAgICAgICAgICAkKCcjbmFtZV8nICsgcGFydGljaXBhbnRVcmwpLmh0bWwobmFtZSk7XG4gICAgICAgICAgICAgICAgJCgnI2VtYWlsXycgKyBwYXJ0aWNpcGFudFVybCkuaHRtbChlbWFpbCk7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICQoXCIjYWxlcnRzcGFuXCIpLmh0bWwoJzxkaXYgY2xhc3M9XCJhbGVydCBhbGVydC1kYW5nZXJcIiByb2xlPVwiYWxlcnRcIj4nKyBkYXRhLm1lc3NhZ2UgKyc8L2Rpdj4nKTtcbiAgICAgICAgICAgICAgICBtYWtlRWRpdEZvcm0ocGFydGljaXBhbnRVcmwsIGxpc3RVcmwsIG5hbWUsIGVtYWlsKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH0pO1xufVxuXG5mdW5jdGlvbiBtYWtlRWRpdEZvcm0ocGFydGljaXBhbnRVcmwsIGxpc3RVcmwsIG5hbWUsIGVtYWlsKXtcbiAgICB2YXIgc2F2ZUJ0blRleHQgPSAkKCd0YWJsZSNteXNhbnRhJykuZGF0YSgnc2F2ZS1idG4tdGV4dCcpO1xuICAgICQoJyNuYW1lXycgKyBwYXJ0aWNpcGFudFVybCkuaHRtbChcbiAgICAgICAgJzxpbnB1dCB0eXBlPVwidGV4dFwiIGlkPVwiaW5wdXRfbmFtZV8nICsgcGFydGljaXBhbnRVcmwgKyAnXCIgY2xhc3M9XCJmb3JtLWNvbnRyb2wgaW5wdXRfZWRpdF9uYW1lXCIgbmFtZT1cIm5hbWVcIiB2YWx1ZT1cIicgKyBuYW1lICsgJ1wiIGRhdGEtaGotbWFza2VkPidcbiAgICApO1xuICAgICQoJyNlbWFpbF8nICsgcGFydGljaXBhbnRVcmwpLmh0bWwoXG4gICAgICAgICc8aW5wdXQgdHlwZT1cInRleHRcIiBpZD1cImlucHV0X2VtYWlsXycgKyBwYXJ0aWNpcGFudFVybCArICdcIiBjbGFzcz1cImZvcm0tY29udHJvbCBpbnB1dF9lZGl0X2VtYWlsXCIgbmFtZT1cImVtYWlsXCIgdmFsdWU9XCInICsgZW1haWwgKyAnXCIgZGF0YS1oai1tYXNrZWQ+Jm5ic3A7JyArXG4gICAgICAgICc8YnV0dG9uIGNsYXNzPVwiYnRuIGJ0bi1zbWFsbCBidG4tcHJpbWFyeSBzYXZlLWVkaXRcIiBpZD1cInN1Ym1pdF9idG5fJyArIHBhcnRpY2lwYW50VXJsICsgJ1wiIGRhdGEtbGlzdHVybD1cIicrbGlzdFVybCArJ1wiIGRhdGEtcGFydGljaXBhbnQtdXJsPVwiJyArIHBhcnRpY2lwYW50VXJsICsgJ1wiPjxpIGNsYXNzPVwiZmEgZmEtY2hlY2tcIj48L2k+ICcrc2F2ZUJ0blRleHQrJzwvYnV0dG9uPidcbiAgICApO1xufVxuXG5mdW5jdGlvbiBhdHRhY2hBY3Rpb24obGlzdFVybCwgcGFydGljaXBhbnRVcmwpIHtcbiAgICB2YXIgdXJsID0gJCgnZm9ybSNkZWxldGUtcGFydGljaXBhbnQtZm9ybScpLmRhdGEoJ2FjdGlvbicpO1xuICAgIHVybCA9IHVybC5yZXBsYWNlKCdsaXN0VXJsJywgbGlzdFVybCk7XG4gICAgdXJsID0gdXJsLnJlcGxhY2UoJ3BhcnRpY2lwYW50VXJsJywgcGFydGljaXBhbnRVcmwpO1xuICAgICQoJyNkZWxldGUtcGFydGljaXBhbnQtZm9ybScpLmF0dHIoJ2FjdGlvbicsIHVybCk7XG59XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9zcmMvSW50cmFjdG8vU2VjcmV0U2FudGFCdW5kbGUvUmVzb3VyY2VzL3B1YmxpYy9qcy9wYXJ0eS5tYW5hZ2UuanMiXSwic291cmNlUm9vdCI6IiJ9