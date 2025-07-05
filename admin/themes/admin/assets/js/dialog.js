$(function () {
    dialog_add();
    dialog_delete();
    dialog_undelete();
    dialog_publish();
    alert_delete();
    alert_publish();
    alert_unpublish();
    alert_publish_all();
    alert_delete_list();
    alert_publish_list();
    alert_save();
    alert_save_publish();
    alert_delete_file();
    alert_box();
    alert_undelete();

    loadToolTip();

    $('.btn-update').click(function () {
        //$('#myForm .tab-pane').addClass("active");
        $('#myForm').bootstrapValidator('validate');
        //$('#myForm .tab-pane').removeClass("active");
        //$('#myForm .tab-pane.in').addClass("active");
        var rel = $(this).attr('rel');
        var isError = $('.form-group').hasClass('has-error');
        if (!isError) {
            send_action('cmdUpdate');
        }
    });
});

function dialogCreate(obj) {
    var html = '';
    var border = '';

    if (obj.body == null) {
        border = 'style="border:none"';
    }

    $('body #' + obj.id).remove();

    html += '<div id="' + obj.id + '" class="modal fade">';
    html += '<div class="modal-dialog">';
    html += '<div class="modal-content">';
    html += '<div class="modal-header" ' + border + '">';
    html += '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
    html += '<h4 class="modal-title">' + obj.title + '</h4>';
    html += '</div>';

    if (obj.body != null) {
        html += '<div class="modal-body">';
        html += '<p>' + obj.body + '</p>';
        html += '</div>';
    }

    html += '<div class="modal-footer" ' + border + '">';
    html += '<div class="btn-group">';
    html += '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
    $.each(obj.button, function (index, item) {
        html += '<button type="button" class="btn ' + item.Class + '" onclick="' + item.func + '">' + item.label + '</button>';
    });
    html += '</div>';
    html += ' </div>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    $('body').append(html);
}

function dialog_delete() {
    $(document).delegate('.btn-delete-all', 'click', function () {
        if ($('.check-all').is(':checked')) {
            $('#alert-delete').modal('show');
        } else {
            $('#alert-box .modal-title').html('Please select at least 1 item.');
            $('#alert-box').modal('show');
        }
    });
}

function dialog_undelete() {
    $(document).delegate('.btn-undelete-all', 'click', function () {
        if ($('.check-all').is(':checked')) {
            $('#alert-undelete').modal('show');
        } else {
            $('#alert-box .modal-title').html('Please select at least 1 item.');
            $('#alert-box').modal('show');
        }
    });
}

function dialog_publish() {
    $(document).delegate('.btn-publish-all', 'click', function () {
        if ($('.check-all').is(':checked')) {
            $('#alert-publish-all').modal('show');
        } else {
            $('#alert-box .modal-title').html('Please select at least 1 item.');
            $('#alert-box').modal('show');
        }
    });
}

function dialog_add() {
    $(document).delegate('.btn-add', 'click', function () {
        var url = $(this).attr('ref');
        if ($('#refid').size() > 0) {
            window.location = urlpath + url + '/formAdd/' + $('#refid').val();
        } else {
            window.location = urlpath + url + '/formAdd';
        }
    });
}

function loadToolTip() {
    $('.btn-delete,.btn-publish,.btn-created,.btn-modified,.btn-display,.btn-edit,.btn-delete-file,.btn-unpublish,.btn-undelete').tooltip({ container: 'body' });
    $('.btn-delete,.btn-publish,.btn-created,.btn-modified,.btn-display,.btn-edit,.btn-delete-file,.btn-unpublish,.btn-undelete').tooltip('show');
    $('.btn-delete,.btn-publish,.btn-created,.btn-modified,.btn-display,.btn-edit,.btn-delete-file,.btn-unpublish,.btn-undelete').tooltip('hide');
}

function alert_box() {
    var obj = {};

    obj.title = 'Warning';
    obj.id = 'alert-box';
    obj.button = [];
    dialogCreate(obj);
}

function alert_error(msg) {
    var title = '<span class="txt-danger"><i class="fa fa-question-circle"></i> ' + msg + '</span>';
    $('#alert-box .modal-title').html(title);
    $('#alert-box').modal('show');
}

function alert_info(msg) {
    var title = '<i class="fa fa-info-circle"></i> ' + msg;
    $('#alert-box .modal-title').html(title);
    $('#alert-box').modal('show');
}

function alert_delete() {
    var obj = {};

    obj.title = 'Confirm delete your data ?';
    obj.id = 'alert-delete';
    obj.button = [];
    obj.button.push({
        label: 'OK',
        func: "send_action('cmdDeleteToList');",
        Class: 'btn-dark',
    });
    dialogCreate(obj);

    $(document).delegate('.btn-delete', 'click', function () {
        $('input[name^=id]').prop('checked', false);
        var rel = $(this).attr('rel');
        $('#alert-delete').modal('show');
        $('input[name^=id][value=' + rel + ']').prop('checked', true);
    });
}

function alert_undelete() {
    var obj = {};

    obj.title = 'Confirm undelete your data ?';
    obj.id = 'alert-undelete';
    obj.button = [];
    obj.button.push({
        label: 'OK',
        func: "send_action('cmdUnDeleteToList');",
        Class: 'btn-dark',
    });
    dialogCreate(obj);

    $(document).delegate('.btn-undelete', 'click', function () {
        $('input[name^=id]').prop('checked', false);
        var rel = $(this).attr('rel');
        $('#alert-undelete').modal('show');
        $('input[name^=id][value=' + rel + ']').prop('checked', true);
    });
}

function alert_publish() {
    var obj = {};

    obj.title = 'Confirm publish your data ?';
    obj.id = 'alert-publish';
    obj.button = [];
    obj.button.push({
        label: 'OK',
        func: "send_action('cmdPublishToList');",
        Class: 'btn-dark',
    });
    dialogCreate(obj);

    $(document).delegate('.btn-publish', 'click', function () {
        $('input[name^=id]').prop('checked', false);
        var rel = $(this).attr('rel');
        $('#alert-unpublish').modal('show');
        $('input[name^=id][value=' + rel + ']').prop('checked', true);
    });
}

function alert_unpublish() {
    var obj = {};

    obj.title = 'Confirm unpublish your data ?';
    obj.id = 'alert-unpublish';
    obj.button = [];
    obj.button.push({
        label: 'OK',
        func: "send_action('cmdUnPublishToList');",
        Class: 'btn-dark',
    });
    dialogCreate(obj);

    $(document).delegate('.btn-unpublish,.btn-created,.btn-modified', 'click', function () {
        $('input[name^=id]').prop('checked', false);
        var rel = $(this).attr('rel');
        $('#alert-publish').modal('show');
        $('input[name^=id][value=' + rel + ']').prop('checked', true);
    });
}

function alert_publish_all() {
    var obj = {};

    obj.title = 'Confirm publish or unpublish your data ?';
    obj.id = 'alert-publish-all';
    obj.button = [];
    obj.button.push({
        label: 'Publish',
        func: "send_action('cmdPublishToList');",
        Class: 'btn-dark',
    });
    obj.button.push({
        label: 'UnPublish',
        func: "send_action('cmdUnPublishToList');",
        Class: 'btn-default',
    });
    dialogCreate(obj);
}

function alert_delete_list() {
    var obj = {};

    obj.title = 'Confirm delete your data ?';
    obj.id = 'alert-delete-list';
    obj.button = [];
    obj.button.push({
        label: 'OK',
        func: "send_action('cmdDeleteToList');",
        Class: 'btn-dark',
    });
    dialogCreate(obj);

    $(document).delegate('.btn-delete-list', 'click', function () {
        var rel = $(this).attr('rel');
        $('#alert-delete-list').modal('show');
    });
}

function alert_publish_list() {
    var obj = {};

    obj.title = 'Confirm publish your data ?';
    obj.id = 'alert-publish-list';
    obj.button = [];
    if (app_mode.edit_mode == 'OFF') {
        obj.button.push({
            label: 'OK',
            func: "send_action('cmdPublishToList');",
            Class: 'btn-dark',
        });
    } else {
        obj.button.push({
            label: 'Publish to list',
            func: "send_action('cmdPublishToList');",
            Class: 'btn-default',
        });
        obj.button.push({
            label: 'OK',
            func: "send_action('cmdPublishToForm');",
            Class: 'btn-dark',
        });
    }
    dialogCreate(obj);

    $(document).delegate('.btn-publish-list', 'click', function () {
        var rel = $(this).attr('rel');
        $('#alert-publish-list').modal('show');
    });
}

function alert_save() {
    var obj = {};

    obj.title = 'Confirm save your data ?';
    obj.id = 'alert-save';
    //obj.body = "What do you want to do next ?";
    obj.button = [];
    if (app_mode.edit_mode == 'OFF') {
        obj.button.push({
            label: 'OK',
            func: "send_action('cmdSaveToList');",
            Class: 'btn-dark',
        });
    } else {
        obj.button.push({
            label: 'Save to list',
            func: "send_action('cmdSaveToList');",
            Class: 'btn-default',
        });
        obj.button.push({
            label: 'OK',
            func: "send_action('cmdSaveToForm');",
            Class: 'btn-dark',
        });
    }
    dialogCreate(obj);

    $(document).delegate('.btn-save', 'click', function () {
        //$('#myForm .tab-pane').addClass("active");
        $('#myForm').bootstrapValidator('validate');
        //$('#myForm .tab-pane').removeClass("active");
        //$('#myForm .tab-pane.in').addClass("active");
        var rel = $(this).attr('rel');
        var isError = $('.form-group').hasClass('has-error');
        if (!isError) {
            $('#alert-save').modal('show');
        } else {
            //$(this).prop("disabled",true);
            $('.bv-tab-error:first a').click();
            setTimeout(function () {
                $('html, body').animate(
                    {
                        scrollTop: $("small[data-bv-result='INVALID']").offset().top - 50,
                    },
                    500
                );
            }, 200);
        }
    });

    var obj = {};

    obj.title = 'Confirm save your data ?';
    obj.id = 'alert-save-list';
    //obj.body = "What do you want to do next ?";
    obj.button = [];
    obj.button.push({
        label: 'OK',
        func: "send_action('cmdSaveToList');",
        Class: 'btn-dark',
    });
    dialogCreate(obj);

    $(document).delegate('.btn-save-list', 'click', function () {
        //$('#myForm .tab-pane').addClass("active");
        $('#myForm').bootstrapValidator('validate');
        // $('#myForm .tab-pane').removeClass("active");
        // $('#myForm .tab-pane.in').addClass("active");
        var rel = $(this).attr('rel');
        var isError = $('.form-group').hasClass('has-error');
        if (!isError) {
            $('#alert-save-list').modal('show');
        } else {
            //$(this).prop("disabled",true);
            $('.bv-tab-error:first a').click();
            setTimeout(function () {
                $('html, body').animate(
                    {
                        scrollTop: $("small[data-bv-result='INVALID']").offset().top - 50,
                    },
                    500
                );
            }, 200);
        }
    });
}

function alert_save_publish() {
    var obj = {};

    obj.title = 'Confirm save your data ?';
    obj.id = 'alert-save-publish';
    //obj.body = "What do you want to do next ?";
    obj.button = [];
    if (app_mode.edit_mode == 'OFF') {
        obj.button.push({
            label: 'OK',
            func: "send_action('cmdSavePublishToList');",
            Class: 'btn-dark',
        });
    } else {
        obj.button.push({
            label: 'Publish to list',
            func: "send_action('cmdSavePublishToList');",
            Class: 'btn-default',
        });
        obj.button.push({
            label: 'OK',
            func: "send_action('cmdSavePublishToForm');",
            Class: 'btn-dark',
        });
    }
    dialogCreate(obj);

    $(document).delegate('.btn-save-publish', 'click', function () {
        // $('#myForm .tab-pane').addClass("active");
        $('#myForm').bootstrapValidator('validate');
        // $('#myForm .tab-pane').removeClass("active");
        // $('#myForm .tab-pane.in').addClass("active");
        var rel = $(this).attr('rel');
        var isError = $('.form-group').hasClass('has-error');
        if (!isError) {
            $('#alert-save-publish').modal('show');
        } else {
            //$(this).prop("disabled",true);
            $('.bv-tab-error:first a').click();
            setTimeout(function () {
                $('html, body').animate(
                    {
                        scrollTop: $("small[data-bv-result='INVALID']").offset().top - 50,
                    },
                    500
                );
            }, 200);
        }
    });
}

function alert_delete_file() {
    var obj = {};

    obj.title = 'Confirm delete your file ?';
    obj.id = 'alert-delete-file';
    obj.button = [];
    obj.button.push({
        label: 'OK',
        func: "send_action('cmdDeleteFile');",
        Class: 'btn-dark',
    });
    dialogCreate(obj);

    $(document).delegate('.btn-delete-file', 'click', function () {
        _element_ = this;
        var rel = $(this).attr('rel');
        var ref = $(this).attr('ref');
        $('#filepath').val(rel);
        $('#filename').val(ref);
        $('#alert-delete-file').modal('show');
    });
}

var submitting = true;
$(function () {
    $('#alert-popover .close').click(function () {
        $('#alert-popover').fadeOut();
    });

    $('.form-data').submit(function (event) {
        var url = $(this).attr('action') == null ? $(this).attr('ng-action') : $(this).attr('action');
        var posturl = urlpath + url + _action_;
        var isProgress = $('#ProgressForUpload').size() > 0;
        var timeout = isProgress ? 0 : 60000;

        var options = {
            url: posturl,
            type: 'post',
            dataType: 'json',
            clearForm: false,
            resetForm: false,
            timeout: timeout,
            beforeSend: function () {
                $.fancybox.showLoading();
                $('.modal').click();
                $('.modal-backdrop').remove();
                $('.btn').prop('disabled', true);
                if (isProgress) {
                    $('#ProgressForUpload .progress-bar').width('0');
                    $('#ProgressForUpload .progress-bar').html('');
                }
            },
            uploadProgress: function (event, position, total, percentComplete) {
                if (isProgress) {
                    var percentVal = percentComplete + '%';
                    $('#ProgressForUpload .progress-bar').width(percentVal);
                    $('#ProgressForUpload .progress-bar').html(percentVal);
                }
            },
            success: function (res) {
                submitting = true;
                $('.btn').prop('disabled', false);
                if (res.status == false) {
                    $('#alert-box .modal-title').html(res.message);
                    $('#alert-box .modal-title').prepend('<span class="glyphicon glyphicon-exclamation-sign" style="color:#FF0000;"></span> ');
                    $('#alert-box').modal('show');
                } else {
                    if (isProgress) {
                        $('#ProgressForUpload .progress-bar').width('0');
                        $('#ProgressForUpload .progress-bar').html('');
                    }

                    if (_button_ == 'cmdDeleteFile') {
                        $(_element_).parents('.col-file').find('.display-file').hide('fast');
                        $(_element_).parents('.col-file').find('.upload-file').show('fast');
                    } else if (_button_ == 'cmdDeleteToList' && $('#formList').size() > 0) {
                        $('#alert-popover').hide().fadeIn();
                        $('#alert-popover .alert-title').html('You delete completed.');
                        get_page_ajax(1);
                    } else if (_button_ == 'cmdPublishToList' && $('#formList').size() > 0) {
                        $('#alert-popover').hide().fadeIn();
                        $('#alert-popover .alert-title').html('You publish completed.');
                        get_page_ajax(1);
                    } else if (_button_ == 'cmdUnPublishToList' && $('#formList').size() > 0) {
                        $('#alert-popover').hide().fadeIn();
                        $('#alert-popover .alert-title').html('You unpublish completed.');
                        get_page_ajax(1);
                    } else if (_button_ == 'cmdUnDeleteToList' && $('#formList').size() > 0) {
                        $('#alert-popover').hide().fadeIn();
                        $('#alert-popover .alert-title').html('You undelete completed.');
                        get_page_ajax(1);
                    } else {
                        window.location.href = res.url;
                    }
                }
                $.fancybox.hideLoading();
            },
        };

        if (submitting) {
            submitting = false;
            $(this).ajaxSubmit(options);
        }
        return false;
    });
});

var _action_;
var _button_;
var _element_;

function send_action(action) {
    if (action != '') {
        _button_ = action;
        if ($('#refid').size() > 0) {
            _action_ = action + '/' + $('#refid').val();
        } else {
            _action_ = action;
        }
        $('.form-data').submit();
    }
}
