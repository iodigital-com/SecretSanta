webpackJsonp([9],{

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
        var participantId = $(this).data('participant');
        attachAction(listUrl, participantId);
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
        editParticipant($(this).data('listurl'), $(this).data('participant-id'));
    });

    $(document).on('click', '.save-edit', function () {
        submitEditForm($(this).data('listurl'), $(this).data('participant-id'));
    });
});

function showExcludeErrors() {
    $('#collapsedMessage').collapse('show');
    $('html, body').animate({
        scrollTop: $("#collapsedMessage").offset().top
    }, 2000);
}

function editParticipant(listUrl, participantId) {
    var email = $('#email_' + participantId).html();
    var name = $('#name_' + participantId).html();
    var url = $('table#mysanta').data('editurl');
    url = url.replace("listUrl", listUrl);
    url = url.replace("participantId", participantId);
    if ($('#email_' + participantId).has('form').length == 0) {
        makeEditForm(participantId, listUrl, name, email);
    }
}

function submitEditForm(listUrl, participantId) {
    var url = $('table#mysanta').data('editurl');
    url = url.replace("listUrl", listUrl);
    url = url.replace("participantId", participantId);
    var name = $('#input_name_' + participantId).val();
    var email = $('#input_email_' + participantId).val();
    $('#input_name_' + participantId).prop('disabled', true);
    $('#input_email_' + participantId).prop('disabled', true);
    $('#submit_btn_' + participantId).prop('disabled', true);
    $('#submit_btn_' + participantId).html('<i class="fa fa-spinner fa-spin"></i>');
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
                $('#name_' + participantId).html(name);
                $('#email_' + participantId).html(email);
            } else {
                $("#alertspan").html('<div class="alert alert-danger" role="alert">' + data.message + '</div>');
                makeEditForm(participantId, listUrl, name, email);
            }
        }
    });
}

function makeEditForm(participantId, listUrl, name, email) {
    var saveBtnText = $('table#mysanta').data('save-btn-text');
    $('#name_' + participantId).html('<input type="text" id="input_name_' + participantId + '" class="form-control input_edit_name" name="name" value="' + name + '" data-hj-masked>');
    $('#email_' + participantId).html('<input type="text" id="input_email_' + participantId + '" class="form-control input_edit_email" name="email" value="' + email + '" data-hj-masked>&nbsp;' + '<button class="btn btn-small btn-primary save-edit" id="submit_btn_' + participantId + '" data-listurl="' + listUrl + '" data-participant-id="' + participantId + '"><i class="fa fa-check"></i> ' + saveBtnText + '</button>');
}

function attachAction(listUrl, participantId) {
    var url = $('form#delete-participant-form').data('action');
    url = url.replace('listUrl', listUrl);
    url = url.replace('participantId', participantId);
    $('#delete-participant-form').attr('action', url);
}
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ })

},["./src/Intracto/SecretSantaBundle/Resources/public/js/party.manage.js"]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9zcmMvSW50cmFjdG8vU2VjcmV0U2FudGFCdW5kbGUvUmVzb3VyY2VzL3B1YmxpYy9qcy9wYXJ0eS5tYW5hZ2UuanMiXSwibmFtZXMiOlsiJCIsImRvY3VtZW50IiwicmVhZHkiLCJjbGljayIsImUiLCJzaG93IiwiYXR0ciIsImZvY3VzIiwiaGlkZSIsImxpc3RVcmwiLCJkYXRhIiwicGFydGljaXBhbnRJZCIsImF0dGFjaEFjdGlvbiIsIk1vZGVybml6ciIsImlucHV0dHlwZXMiLCJkYXRlIiwiZGF0ZXBpY2tlciIsImRhdGVGb3JtYXQiLCJzZWxlY3QyIiwid2lkdGgiLCJvbiIsImVkaXRQYXJ0aWNpcGFudCIsInN1Ym1pdEVkaXRGb3JtIiwic2hvd0V4Y2x1ZGVFcnJvcnMiLCJjb2xsYXBzZSIsImFuaW1hdGUiLCJzY3JvbGxUb3AiLCJvZmZzZXQiLCJ0b3AiLCJlbWFpbCIsImh0bWwiLCJuYW1lIiwidXJsIiwicmVwbGFjZSIsImhhcyIsImxlbmd0aCIsIm1ha2VFZGl0Rm9ybSIsInZhbCIsInByb3AiLCJhamF4IiwidHlwZSIsInN1Y2Nlc3MiLCJtZXNzYWdlIiwic2F2ZUJ0blRleHQiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7QUFBQSx5Q0FBQUEsRUFBRUMsUUFBRixFQUFZQyxLQUFaLENBQWtCLFlBQVk7QUFDMUJGLE1BQUUsYUFBRixFQUFpQkcsS0FBakIsQ0FBdUIsVUFBVUMsQ0FBVixFQUFhO0FBQ2hDSixVQUFFLGlCQUFGLEVBQXFCSyxJQUFyQjtBQUNBTCxVQUFFLGFBQUYsRUFBaUJNLElBQWpCLENBQXNCLFVBQXRCLEVBQWtDLElBQWxDO0FBQ0FOLFVBQUUsc0JBQUYsRUFBMEJPLEtBQTFCO0FBQ0gsS0FKRDtBQUtBUCxNQUFFLG9CQUFGLEVBQXdCRyxLQUF4QixDQUE4QixVQUFVQyxDQUFWLEVBQWE7QUFDdkNKLFVBQUUsaUJBQUYsRUFBcUJRLElBQXJCO0FBQ0FSLFVBQUUsYUFBRixFQUFpQk0sSUFBakIsQ0FB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QUN0RCxRQUFJYyxjQUFjM0MsRUFBRSxlQUFGLEVBQW1CVSxJQUFuQixDQUF3QixlQUF4QixDQUFsQjtBQUNBVixNQUFFLFdBQVdXLGFBQWIsRUFBNEJtQixJQUE1QixDQUNJLHVDQUF1Q25CLGFBQXZDLEdBQXVELDREQUF2RCxHQUFzSG9CLElBQXRILEdBQTZILG1CQURqSTtBQUdBL0IsTUFBRSxZQUFZVyxhQUFkLEVBQTZCbUIsSUFBN0IsQ0FDSSx3Q0FBd0NuQixhQUF4QyxHQUF3RCw4REFBeEQsR0FBeUhrQixLQUF6SCxHQUFpSSx5QkFBakksR0FDQSxxRUFEQSxHQUN3RWxCLGFBRHhFLEdBQ3dGLGtCQUR4RixHQUMyR0YsT0FEM0csR0FDb0gseUJBRHBILEdBQ2dKRSxhQURoSixHQUNnSyxnQ0FEaEssR0FDaU1nQyxXQURqTSxHQUM2TSxXQUZqTjtBQUlIOztBQUVELFNBQVMvQixZQUFULENBQXNCSCxPQUF0QixFQUErQkUsYUFBL0IsRUFBOEM7QUFDMUMsUUFBSXFCLE1BQU1oQyxFQUFFLDhCQUFGLEVBQWtDVSxJQUFsQyxDQUF1QyxRQUF2QyxDQUFWO0FBQ0FzQixVQUFNQSxJQUFJQyxPQUFKLENBQVksU0FBWixFQUF1QnhCLE9BQXZCLENBQU47QUFDQXVCLFVBQU1BLElBQUlDLE9BQUosQ0FBWSxlQUFaLEVBQTZCdEIsYUFBN0IsQ0FBTjtBQUNBWCxNQUFFLDBCQUFGLEVBQThCTSxJQUE5QixDQUFtQyxRQUFuQyxFQUE2QzBCLEdBQTdDO0FBQ0gsQyIsImZpbGUiOiJqcy9wYXJ0eS5tYW5hZ2UuMTNmOWRiZmM3ZWE0Njc4YmVhMGEuanMiLCJzb3VyY2VzQ29udGVudCI6WyIkKGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbiAoKSB7XG4gICAgJCgnI2J0bl9kZWxldGUnKS5jbGljayhmdW5jdGlvbiAoZSkge1xuICAgICAgICAkKCcjZGVsZXRlLXdhcm5pbmcnKS5zaG93KCk7XG4gICAgICAgICQoJyNidG5fZGVsZXRlJykuYXR0cignZGlzYWJsZWQnLCB0cnVlKTtcbiAgICAgICAgJCgnI2RlbGV0ZS1jb25maXJtYXRpb24nKS5mb2N1cygpO1xuICAgIH0pO1xuICAgICQoJyNidG5fZGVsZXRlX2NhbmNlbCcpLmNsaWNrKGZ1bmN0aW9uIChlKSB7XG4gICAgICAgICQoJyNkZWxldGUtd2FybmluZycpLmhpZGUoKTtcbiAgICAgICAgJCgnI2J0bl9kZWxldGUnKS5hdHRyKCdkaXNhYmxlZCcsIGZhbHNlKS5mb2N1cygpO1xuICAgIH0pO1xuXG4gICAgJCgnI2J0bl9hZGQnKS5jbGljayhmdW5jdGlvbiAoZSkge1xuICAgICAgICAkKCcjYWRkLXBhcnRpY2lwYW50Jykuc2hvdygpO1xuICAgICAgICAkKCcjYnRuX2FkZCcpLmF0dHIoJ2Rpc2FibGVkJywgdHJ1ZSk7XG4gICAgICAgICQoJyNhZGQtcGFydGljaXBhbnQtbmFtZScpLmZvY3VzKCk7XG4gICAgfSk7XG5cbiAgICAkKCcjYnRuX2FkZF9jYW5jZWwnKS5jbGljayhmdW5jdGlvbiAoZSkge1xuICAgICAgICAkKCcjYWRkLXBhcnRpY2lwYW50JykuaGlkZSgpO1xuICAgICAgICAkKCcjYnRuX2FkZCcpLmF0dHIoJ2Rpc2FibGVkJywgZmFsc2UpLmZvY3VzKCk7XG4gICAgfSk7XG5cbiAgICAkKCcjYnRuX3VwZGF0ZScpLmNsaWNrKGZ1bmN0aW9uIChlKSB7XG4gICAgICAgICQoJyN1cGRhdGUtcGFydHktZGV0YWlscycpLnNob3coKTtcbiAgICAgICAgJCgnI2J0bl91cGRhdGUnKS5hdHRyKCdkaXNhYmxlZCcsIHRydWUpO1xuICAgIH0pO1xuXG4gICAgJCgnI2J0bl91cGRhdGVfY2FuY2VsJykuY2xpY2soZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgJCgnI3VwZGF0ZS1wYXJ0eS1kZXRhaWxzJykuaGlkZSgpO1xuICAgICAgICAkKCcjYnRuX3VwZGF0ZScpLmF0dHIoJ2Rpc2FibGVkJywgZmFsc2UpLmZvY3VzKCk7XG4gICAgfSk7XG5cbiAgICAkKCcubGlua19yZW1vdmVfcGFydGljaXBhbnQnKS5jbGljayhmdW5jdGlvbiAoZSkge1xuICAgICAgICAkKCcjZGVsZXRlLXBhcnRpY2lwYW50Jykuc2hvdygpO1xuICAgICAgICAkKCcubGlua19yZW1vdmVfcGFydGljaXBhbnQnKS5hdHRyKCdkaXNhYmxlZCcsIHRydWUpO1xuICAgICAgICAkKCcjZGVsZXRlLXBhcnRpY2lwYW50LWNvbmZpcm1hdGlvbicpLmZvY3VzKCk7XG4gICAgICAgIHZhciBsaXN0VXJsID0gJCh0aGlzKS5kYXRhKCdsaXN0dXJsJyk7XG4gICAgICAgIHZhciBwYXJ0aWNpcGFudElkID0gJCh0aGlzKS5kYXRhKCdwYXJ0aWNpcGFudCcpO1xuICAgICAgICBhdHRhY2hBY3Rpb24obGlzdFVybCwgcGFydGljaXBhbnRJZCk7XG4gICAgfSk7XG5cbiAgICAkKCcuYnRuX3JlbW92ZV9wYXJ0aWNpcGFudF9jYW5jZWwnKS5jbGljayhmdW5jdGlvbiAoZSkge1xuICAgICAgICAkKCcjZGVsZXRlLXBhcnRpY2lwYW50JykuaGlkZSgpO1xuICAgICAgICAkKCcubGlua19yZW1vdmVfcGFydGljaXBhbnQnKS5hdHRyKCdkaXNhYmxlZCcsIGZhbHNlKTtcbiAgICB9KTtcblxuICAgIGlmIChNb2Rlcm5penIuaW5wdXR0eXBlcy5kYXRlID09IHRydWUpIHtcbiAgICAgICAgJChcIiNpbnRyYWN0b19zZWNyZXRzYW50YWJ1bmRsZV91cGRhdGVwYXJ0eWRldGFpbHN0eXBlX2V2ZW50ZGF0ZVwiKS5jbGljayhmdW5jdGlvbiAoZSkge1xuICAgICAgICAgICAgJCh0aGlzKS5kYXRlcGlja2VyKHtkYXRlRm9ybWF0OiAnZGQtbW0teXknfSk7XG4gICAgICAgIH0pO1xuICAgIH1cblxuICAgICQoJy5qcy1zZWxlY3Rvci1wYXJ0aWNpcGFudCcpLnNlbGVjdDIoeyB3aWR0aDogJzEwMCUnIH0pO1xuXG4gICAgJCgnLnBhcnRpY2lwYW50LWVkaXQtaWNvbicpLm9uKCdjbGljaycsIGZ1bmN0aW9uKCkge1xuICAgICAgICBlZGl0UGFydGljaXBhbnQoJCh0aGlzKS5kYXRhKCdsaXN0dXJsJyksICQodGhpcykuZGF0YSgncGFydGljaXBhbnQtaWQnKSk7XG4gICAgfSk7XG5cbiAgICAkKGRvY3VtZW50KS5vbignY2xpY2snLCAnLnNhdmUtZWRpdCcsIGZ1bmN0aW9uKCl7XG4gICAgICAgIHN1Ym1pdEVkaXRGb3JtKCQodGhpcykuZGF0YSgnbGlzdHVybCcpLCAkKHRoaXMpLmRhdGEoJ3BhcnRpY2lwYW50LWlkJykpO1xuICAgIH0pO1xufSk7XG5cbmZ1bmN0aW9uIHNob3dFeGNsdWRlRXJyb3JzKCkge1xuICAgICQoJyNjb2xsYXBzZWRNZXNzYWdlJykuY29sbGFwc2UoJ3Nob3cnKTtcbiAgICAkKCdodG1sLCBib2R5JykuYW5pbWF0ZSh7XG4gICAgICAgIHNjcm9sbFRvcDogJChcIiNjb2xsYXBzZWRNZXNzYWdlXCIpLm9mZnNldCgpLnRvcFxuICAgIH0sIDIwMDApO1xufVxuXG5mdW5jdGlvbiBlZGl0UGFydGljaXBhbnQobGlzdFVybCwgcGFydGljaXBhbnRJZCkge1xuICAgIHZhciBlbWFpbCA9ICQoJyNlbWFpbF8nICsgcGFydGljaXBhbnRJZCkuaHRtbCgpO1xuICAgIHZhciBuYW1lID0gJCgnI25hbWVfJyArIHBhcnRpY2lwYW50SWQpLmh0bWwoKTtcbiAgICB2YXIgdXJsID0gJCgndGFibGUjbXlzYW50YScpLmRhdGEoJ2VkaXR1cmwnKTtcbiAgICB1cmwgPSB1cmwucmVwbGFjZShcImxpc3RVcmxcIiwgbGlzdFVybCk7XG4gICAgdXJsID0gdXJsLnJlcGxhY2UoXCJwYXJ0aWNpcGFudElkXCIsIHBhcnRpY2lwYW50SWQpO1xuICAgIGlmICgkKCcjZW1haWxfJyArIHBhcnRpY2lwYW50SWQpLmhhcygnZm9ybScpLmxlbmd0aCA9PSAwKSB7XG4gICAgICAgIG1ha2VFZGl0Rm9ybShwYXJ0aWNpcGFudElkLCBsaXN0VXJsLCBuYW1lLCBlbWFpbCk7XG4gICAgfVxufVxuXG5mdW5jdGlvbiBzdWJtaXRFZGl0Rm9ybShsaXN0VXJsLHBhcnRpY2lwYW50SWQpIHtcbiAgICB2YXIgdXJsID0gJCgndGFibGUjbXlzYW50YScpLmRhdGEoJ2VkaXR1cmwnKTtcbiAgICB1cmwgPSB1cmwucmVwbGFjZShcImxpc3RVcmxcIiwgbGlzdFVybCk7XG4gICAgdXJsID0gdXJsLnJlcGxhY2UoXCJwYXJ0aWNpcGFudElkXCIsIHBhcnRpY2lwYW50SWQpO1xuICAgIHZhciBuYW1lID0gJCgnI2lucHV0X25hbWVfJyArIHBhcnRpY2lwYW50SWQpLnZhbCgpO1xuICAgIHZhciBlbWFpbCA9ICQoJyNpbnB1dF9lbWFpbF8nICsgcGFydGljaXBhbnRJZCkudmFsKCk7XG4gICAgJCgnI2lucHV0X25hbWVfJyArIHBhcnRpY2lwYW50SWQpLnByb3AoJ2Rpc2FibGVkJywgdHJ1ZSk7XG4gICAgJCgnI2lucHV0X2VtYWlsXycgKyBwYXJ0aWNpcGFudElkKS5wcm9wKCdkaXNhYmxlZCcsIHRydWUpO1xuICAgICQoJyNzdWJtaXRfYnRuXycgKyBwYXJ0aWNpcGFudElkKS5wcm9wKCdkaXNhYmxlZCcsIHRydWUpO1xuICAgICQoJyNzdWJtaXRfYnRuXycgKyBwYXJ0aWNpcGFudElkKS5odG1sKCc8aSBjbGFzcz1cImZhIGZhLXNwaW5uZXIgZmEtc3BpblwiPjwvaT4nKTtcbiAgICAkKFwiI2FsZXJ0c3BhblwiKS5odG1sKCcnKTtcblxuICAgICQuYWpheCh7XG4gICAgICAgIHR5cGU6ICdQT1NUJyxcbiAgICAgICAgdXJsOiB1cmwsXG4gICAgICAgIGRhdGE6IHtcbiAgICAgICAgICAgIG5hbWU6IG5hbWUsXG4gICAgICAgICAgICBlbWFpbDogZW1haWxcbiAgICAgICAgfSxcbiAgICAgICAgc3VjY2VzczogZnVuY3Rpb24oZGF0YSl7XG4gICAgICAgICAgICBpZiAoZGF0YS5zdWNjZXNzKSB7XG4gICAgICAgICAgICAgICAgJChcIiNhbGVydHNwYW5cIikuaHRtbCgnPGRpdiBjbGFzcz1cImFsZXJ0IGFsZXJ0LXN1Y2Nlc3NcIiByb2xlPVwiYWxlcnRcIj4nICsgZGF0YS5tZXNzYWdlICsgJzwvZGl2PicpO1xuICAgICAgICAgICAgICAgICQoJyNuYW1lXycgKyBwYXJ0aWNpcGFudElkKS5odG1sKG5hbWUpO1xuICAgICAgICAgICAgICAgICQoJyNlbWFpbF8nICsgcGFydGljaXBhbnRJZCkuaHRtbChlbWFpbCk7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICQoXCIjYWxlcnRzcGFuXCIpLmh0bWwoJzxkaXYgY2xhc3M9XCJhbGVydCBhbGVydC1kYW5nZXJcIiByb2xlPVwiYWxlcnRcIj4nKyBkYXRhLm1lc3NhZ2UgKyc8L2Rpdj4nKTtcbiAgICAgICAgICAgICAgICBtYWtlRWRpdEZvcm0ocGFydGljaXBhbnRJZCwgbGlzdFVybCwgbmFtZSwgZW1haWwpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfSk7XG59XG5cbmZ1bmN0aW9uIG1ha2VFZGl0Rm9ybShwYXJ0aWNpcGFudElkLCBsaXN0VXJsLCBuYW1lLCBlbWFpbCl7XG4gICAgdmFyIHNhdmVCdG5UZXh0ID0gJCgndGFibGUjbXlzYW50YScpLmRhdGEoJ3NhdmUtYnRuLXRleHQnKTtcbiAgICAkKCcjbmFtZV8nICsgcGFydGljaXBhbnRJZCkuaHRtbChcbiAgICAgICAgJzxpbnB1dCB0eXBlPVwidGV4dFwiIGlkPVwiaW5wdXRfbmFtZV8nICsgcGFydGljaXBhbnRJZCArICdcIiBjbGFzcz1cImZvcm0tY29udHJvbCBpbnB1dF9lZGl0X25hbWVcIiBuYW1lPVwibmFtZVwiIHZhbHVlPVwiJyArIG5hbWUgKyAnXCIgZGF0YS1oai1tYXNrZWQ+J1xuICAgICk7XG4gICAgJCgnI2VtYWlsXycgKyBwYXJ0aWNpcGFudElkKS5odG1sKFxuICAgICAgICAnPGlucHV0IHR5cGU9XCJ0ZXh0XCIgaWQ9XCJpbnB1dF9lbWFpbF8nICsgcGFydGljaXBhbnRJZCArICdcIiBjbGFzcz1cImZvcm0tY29udHJvbCBpbnB1dF9lZGl0X2VtYWlsXCIgbmFtZT1cImVtYWlsXCIgdmFsdWU9XCInICsgZW1haWwgKyAnXCIgZGF0YS1oai1tYXNrZWQ+Jm5ic3A7JyArXG4gICAgICAgICc8YnV0dG9uIGNsYXNzPVwiYnRuIGJ0bi1zbWFsbCBidG4tcHJpbWFyeSBzYXZlLWVkaXRcIiBpZD1cInN1Ym1pdF9idG5fJyArIHBhcnRpY2lwYW50SWQgKyAnXCIgZGF0YS1saXN0dXJsPVwiJytsaXN0VXJsICsnXCIgZGF0YS1wYXJ0aWNpcGFudC1pZD1cIicgKyBwYXJ0aWNpcGFudElkICsgJ1wiPjxpIGNsYXNzPVwiZmEgZmEtY2hlY2tcIj48L2k+ICcrc2F2ZUJ0blRleHQrJzwvYnV0dG9uPidcbiAgICApO1xufVxuXG5mdW5jdGlvbiBhdHRhY2hBY3Rpb24obGlzdFVybCwgcGFydGljaXBhbnRJZCkge1xuICAgIHZhciB1cmwgPSAkKCdmb3JtI2RlbGV0ZS1wYXJ0aWNpcGFudC1mb3JtJykuZGF0YSgnYWN0aW9uJyk7XG4gICAgdXJsID0gdXJsLnJlcGxhY2UoJ2xpc3RVcmwnLCBsaXN0VXJsKTtcbiAgICB1cmwgPSB1cmwucmVwbGFjZSgncGFydGljaXBhbnRJZCcsIHBhcnRpY2lwYW50SWQpO1xuICAgICQoJyNkZWxldGUtcGFydGljaXBhbnQtZm9ybScpLmF0dHIoJ2FjdGlvbicsIHVybCk7XG59XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9zcmMvSW50cmFjdG8vU2VjcmV0U2FudGFCdW5kbGUvUmVzb3VyY2VzL3B1YmxpYy9qcy9wYXJ0eS5tYW5hZ2UuanMiXSwic291cmNlUm9vdCI6IiJ9