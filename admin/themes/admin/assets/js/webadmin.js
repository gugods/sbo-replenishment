var numsize = 10;
var app_breadcrumb = [];
var app_mode = {};
var app_list = {};
var app_searchForm = [];
var app_statusForm = [];
var app_post = [];
var app_validate = {};
var app_tabForm = [];

var myApp = angular.module('myApp', []);
myApp.controller('breadcrumbCtrl', function ($scope) {
    $scope.breadcrumb = app_breadcrumb;
    $scope.refid = app_list.refid;
});
myApp.controller('listCtrl', function ($scope) {
    $scope.refid = app_list.refid;
    $scope.ref_name = app_list.ref_name;
    $scope.ref_back = app_list.ref_back;
    $scope.title = app_list.title;
    $scope.titleSearch = app_list.titleSearch;
    $scope.titleExample = app_list.titleExample;
    $scope.totalpage = app_list.totalpage;
    $scope.pagesize = app_list.pagesize;
    $scope.thispage = app_list.thispage ? app_list.thispage : 1;
    $scope.sys_action = app_list.sys_action ? app_list.sys_action : '';
    $scope.orderby = app_list.orderby;
    $scope.sorting = app_list.sorting;
    $scope.rows = app_list.rows;
    $scope.rows_all = app_list.rows_publish + app_list.rows_modified + app_list.rows_unpublish;
    $scope.rows_publish = app_list.rows_publish;
    $scope.rows_modified = app_list.rows_modified;
    $scope.rows_unpublish = app_list.rows_unpublish;
    $scope.rows_delete = app_list.rows_delete;
    $scope.tbwidth = app_list.tbwidth;
    $scope.module = app_list.module;
    $scope.column = app_list.column;
    $scope.items = app_list.items;
    $scope.searchForm = app_searchForm;
    $scope.statusForm = app_statusForm;
    $scope.post = app_post;

    $scope.control = true;
    $scope.checkbox_mode = app_mode.checkbox_mode;
    $scope.publish_mode = app_mode.publish_mode;
    $scope.control_mode = app_mode.control_mode;
    $scope.add_mode = app_mode.add_mode;
    $scope.edit_mode = app_mode.edit_mode;
    $scope.delete_mode = app_mode.delete_mode;
    $scope.display_mode = app_mode.display_mode;
    $scope.log_mode = app_mode.log_mode;
    $scope.export_mode = app_mode.export_mode;
    $scope.import_mode = app_mode.import_mode;
    $scope.undelete_mode = app_mode.undelete_mode;

    $scope.setValue = function (value) {
        $scope.items = value;
    };
    $scope.formatValue = function (value, type) {
        return formatValue(type, value);
    };
    $scope.getObj = function (obj, name) {
        return getObj(obj, name);
    };
    $scope.setRefresh = function (url) {
        window.location.href = url;
    };
    $scope.createForm = function (searchForm, post, module, child) {
        var advanceForm = createForm(searchForm, post, module, child);
        var myEl = angular.element(document.querySelector('#advanceForm'));
        myEl.append(advanceForm);
    };
    $scope.createStatus = function (statusForm, module) {
        var statusForm = createForm(statusForm, {}, module, false);
        var myEl = angular.element(document.querySelector('#statusForm'));
        myEl.append(statusForm);
    };
});
myApp.controller('formCtrl', function ($scope) {
    $scope.id = app_list.id;
    $scope.refid = app_list.refid;
    $scope.ref_name = app_list.ref_name;
    $scope.ref_back = app_list.ref_back;
    $scope.title = app_list.title;
    $scope.totalpage = app_list.totalpage;
    $scope.pagesize = app_list.pagesize;
    $scope.orderby = app_list.orderby;
    $scope.sorting = app_list.sorting;
    $scope.rows = app_list.rows;
    $scope.tbwidth = app_list.tbwidth;
    $scope.module = app_list.module;
    $scope.column = app_list.column;
    $scope.items = app_list.items;
    $scope.tabForm = app_tabForm;
    $scope.post = app_post;

    $scope.control = false;
    $scope.checkbox_mode = app_mode.checkbox_mode;
    $scope.publish_mode = app_mode.publish_mode;
    $scope.control_mode = app_mode.control_mode;
    $scope.add_mode = app_mode.add_mode;
    $scope.edit_mode = app_mode.edit_mode;
    $scope.delete_mode = app_mode.delete_mode;
    $scope.display_mode = app_mode.display_mode;
    $scope.log_mode = app_mode.log_mode;
    $scope.action = app_mode.action;

    $scope.setValue = function (value) {
        $scope.items = value;
    };
    $scope.formatValue = function (value, type) {
        return formatValue(type, value);
    };
    $scope.getObj = function (obj, name) {
        return getObj(obj, name);
    };
    $scope.setRefresh = function (url) {
        window.location.href = url;
    };
    $scope.createForm = function (tabid, myForm, post, module, child) {
        var myForm = createForm(myForm, post, module, child);
        var myEl = angular.element(document.querySelector('#' + tabid));
        myEl.append(myForm);
    };
});
// angular.element(document).ready(function() {
//     angular.bootstrap(document.getElementById('myApp'), ['myApp']);
// });

function getObj(obj, name) {
    for (var o in obj) {
        if (o == name) {
            return obj[o];
        }
    }
    return '';
}

function formatValue(type, value) {
    if (type == 'date' || type == 'datetime') {
        if (value == '' || value == null) {
            value = '';
        } else {
            var datetime = value.split(' ');
            var date = datetime[0].split('-');
            if (datetime.length > 1) var time = datetime[1].split(':');
            if (type == 'date') {
                value = date[2] + '/' + date[1] + '/' + date[0];
            } else {
                value = date[2] + '/' + date[1] + '/' + date[0] + ' ' + time[0] + ':' + time[1];
            }
        }
    } else if (type == 'duration') {
        value = convert_duration_time(value);
    } else if (type == 'select') {
        value = LANG_VALUE && LANG_VALUE[value] ? LANG_VALUE[value] : value;
    }
    return value;
}

function check_all(obj) {
    var check = $(obj).attr('id');
    if ($(obj).is(':checked')) {
        $('.' + check + '[disabled!=disabled]').prop('checked', true);
    } else {
        $('.' + check + '[disabled!=disabled]').prop('checked', false);
    }
}

$(function () {
    setTimeout(function () {
        $('.menu_section li').each(function () {
            var module = $('#module').val();
            if ($(this).hasClass(module)) {
                $('.menu_section').addClass('active');
                $(this).parents('.parent_menu').addClass('active');
                $(this).parents('.child_menu').show();
                $(this).addClass('current-page');
                $(this).parents('.parent_menu').find('span.fa').addClass('fa-chevron-up').removeClass('fa-chevron-down');
                if ($('body').hasClass('nav-sm')) {
                    $(this).parents('.child_menu').hide();
                    $(this).parents('.parent_menu').removeClass('active');
                }
            }
        });

        $('.menu_section .parent_menu').each(function () {
            var obj = this;
            var i = false;
            $(obj)
                .find('li')
                .each(function () {
                    if ($(this).css('display') == 'block') {
                        i = true;
                        return false;
                    }
                });
            if (i) $(obj).attr('style', 'display:block !important');
        });
    }, 100);

    $('.menu_section .parent_menu > a').click(function () {
        var toggle = $(this).find('span.fa').hasClass('fa-chevron-down');
        $('.menu_section .parent_menu a').find('span.fa').addClass('fa-chevron-down').removeClass('fa-chevron-up');
        if (toggle) {
            $(this).find('span.fa').addClass('fa-chevron-up').removeClass('fa-chevron-down');
        } else {
            $(this).find('span.fa').addClass('fa-chevron-down').removeClass('fa-chevron-up');
        }
    });

    $('#myTab a.active').tab('show');
    $('#myTab a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    $('#myTabSearch a.active').tab('show');
    $('#myTabSearch a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
        $('#TabSelected').val($(this).attr('href'));
    });

    // tinymce.init({
    //     selector: "textarea.editor"
    // });

    $(document).delegate('input[type=file]', 'change', function () {
        var start = $(this).val().length - 3;
        var end = $(this).val().length;
        var type = $(this).val().substring(start, end);
        var start2 = $(this).val().length - 4;
        var end2 = $(this).val().length;
        var type2 = $(this).val().substring(start2, end2);
        var filetype = $(this).attr('filetype');
        var isError = true;
        if (filetype != '' && filetype != undefined) {
            $.each(filetype.split('|'), function (index, value) {
                if (value == type.toLowerCase() || value == type2.toLowerCase()) {
                    isError = false;
                    return false;
                }
            });

            if (isError) {
                alert('Please upload file extensions ' + filetype);
                $(this).val('');
            }
        }
    });

    if ($('#listCtrl').size() > 0) {
        var scope = angular.element('#listCtrl').scope();
        scope.$apply(function () {
            scope.createForm(app_searchForm, app_post, app_list.module, false);
            scope.createStatus(app_statusForm, app_list.module);
            $('#statusForm').find('.form-group').css('display', 'inline-block');
            $('#statusForm')
                .find('.form-group')
                .each(function () {
                    var label = $(this).find('label').text();
                    var name = $(this).find('select').attr('name');
                    var col = name.replace('_action', '', name);
                    $(this).find('select').attr({ col: col });
                    $(this).find('select').removeAttr('style').css('display', 'inline-block');
                    $(this).find('select option:first').text(label);
                    $(this).find('label').remove();
                    $(this).find('.col-sm-9').css('padding-right', '15px').addClass('class-status-action').removeClass('col-sm-9');
                    $(this).find('.class-status-action').append('<button  type="button" class="btn btn-default btn-status-action" style="margin-bottom: 3px;margin-left:3px;">Apply</button>');
                });
        });
        create_lookup();
    }

    if ($('#formCtrl').size() > 0) {
        var scope = angular.element('#formCtrl').scope();
        scope.$apply(function () {
            for (i in app_tabForm) {
                var item = app_tabForm[i];
                var tabid = 'tab-' + item.id;
                scope.createForm(tabid, item.form, app_post, app_list.module, false);
            }
        });
        create_lookup();
        load_validate('myForm', app_validate);
        $('#formCtrl .child').each(function (index, item) {
            load_list_child(this);
        });
        $('.form_display select,.form_display textarea').prop('disabled', true);
        $('.form_display input').prop('disabled', true);
        $('.form_display input[type=hidden]').prop('disabled', false);
        $('.form_display .glyphicon-remove').hide();
    }

    load_date_picker();
    load_fancybox();

    if ($(window).width() < 991) {
        if ($('#listCtrl').size() > 0) {
            $('#listCtrl .table-responsive').width($(window).width() - 80);
        }
    }
    $(window).resize(function () {
        if ($('#listCtrl').size() > 0) {
            if ($(window).width() < 991) {
                $('#listCtrl .table-responsive').width($(window).width() - 80);
            } else {
                $('#listCtrl .table-responsive').width('100%');
            }
        }
    });

    $('#menu_toggle').click(function () {
        if ($('body').hasClass('nav-md')) {
            $('.parent_menu.current-page .child_menu').show();
            close_menu(false);
        } else {
            $('.parent_menu.current-page .child_menu').hide();
            close_menu(true);
        }
    });

    if ($('body').hasClass('nav-sm')) {
        $('.child_menu').removeAttr('style');
        $('.parent_menu.active').addClass('current-page');
        $('.parent_menu').removeClass('active');
    }

    $('#item-sys_action a').click(function () {
        $('#item-sys_action li').removeClass('active');
        $(this).parents('li').addClass('active');
        $('#formSearch #sys_action').val($(this).attr('rel'));
        $('#thispage').val(1);
        var sys_action = {
            name: 'sys_action',
            value: $('#sys_action').val(),
        };
        remove_form_post(form_post, 'sys_action');
        form_post.push(sys_action);

        $('#TabSelected').val('#TabKeyword');

        var new_rows = $(this).find('.badge').text();
        sys_action = $('#sys_action').val();
        var scope = angular.element('#listCtrl').scope();
        scope.$apply(function () {
            scope.rows = new_rows;
            scope.totalpage = Math.ceil(new_rows / scope.pagesize);
            scope.sys_action = sys_action;
        });

        load_page_ajax();
    });

    disable_value();
    $('#myTabSearch a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        disable_value();
    });

    $(document).delegate('.imageUpload', 'change', function (e) {
        var obj = this;
        var canvas = $(obj).parents('.col-file').find('.imageCanvas canvas');
        var ctx = canvas[0].getContext('2d');
        var reader = new FileReader();
        reader.onload = function (event) {
            var img = new Image();
            img.onload = function () {
                canvas.width = 150;
                canvas.height = (150 * img.height) / img.width;
                drawImageProp(ctx, img, 0, 0, canvas.width, canvas.height, 0.5, 0.5);
            };
            img.src = event.target.result;
        };
        reader.readAsDataURL(e.target.files[0]);
        $(obj).parents('.col-file').find('.imageCanvas').fadeIn().css('display', 'inline-block');
        $(obj).parents('.col-file').find('.upload-file').hide();
    });

    $(document).delegate('.btn-remove-file', 'click', function (e) {
        var obj = this;
        $(obj).parents('.col-file').find('.upload-file').show();
        $(obj).parents('.col-file').find('.imageCanvas').fadeOut();
        $(obj).parents('.col-file').find('input[type=file]').val('');
    });

    createDataExport();
    $(document).delegate('.btn-export-all', 'click', function (e) {
        var totalItem = $('#formExport input[name=totalItem]').val();
        var numRows = parseInt($('.num_rows span').text());
        var allItem = totalItem ? parseInt(totalItem) : numRows;
        var searchItem = parseInt($('#formList table tbody tr').size());
        $('#showFormExport .search-item').text(searchItem);
        var limit = 5000;
        var html = '';
        if (allItem > limit) {
            var range = parseInt(allItem / limit);
            var remain = parseInt(allItem % limit);
            var i = 0;
            for (i = 0; i < range; i++) {
                var page = i + 1;
                var end = page * limit;
                var start = end - limit + 1;
                html += `<a class="text-info btn-download-all" href="javascript:;" page="` + page + `" limit="` + limit + `"><span class="glyphicon glyphicon-download-alt"></span> ดาวน์โหลด <strong>` + number_format(start) + `</strong> ถึง <strong>` + number_format(end) + `</strong>  รายการ</a>`;
            }
            if (remain > 0) {
                var page = i + 1;
                var end = i * limit + remain;
                var start = i * limit + 1;
                html += `<a class="text-info btn-download-all" href="javascript:;" page="` + page + `" limit="` + limit + `"><span class="glyphicon glyphicon-download-alt"></span> ดาวน์โหลด <strong>` + number_format(start) + `</strong> ถึง <strong>` + number_format(end) + `</strong>  รายการ</a>`;
            }
            var height = $(this).attr('data-height') ? $(this).attr('data-height') : 200;
        } else {
            html += `<a class="text-info btn-download-all" href="javascript:;" page="1" limit="` + allItem + `"><span class="glyphicon glyphicon-download-alt"></span> ดาวน์โหลด <strong class="all-item">` + number_format(allItem) + `</strong> รายการจากผลการค้นหาทั้งหมด</a>`;
            var height = $(this).attr('data-height') ? $(this).attr('data-height') : 60;
        }

        $('#showFormExport .link-download-all').html(html);

        $.fancybox({
            width: 400,
            height: height,
            autoSize: false,
            href: '#showFormExport',
            padding: 20,
            closeBtn: true,
            beforeShow: function () {
                $('.fancybox-overlay').css('z-index', 1000);
            },
        });
    });

    $(document).delegate('.btn-download-search', 'click', function (e) {
        if ($('#formExport').attr('new-action')) {
            $('#formExport').attr('action', $('#formExport').attr('new-action'));
        } else {
            $('#formExport').attr('action', $('#module').val() + '/cmdExport');
        }
        $('#btnSubmitExport').trigger('click');
    });

    $(document).delegate('.btn-download-all', 'click', function (e) {
        if ($('#formExport').attr('new-action')) {
            $('#formExport').attr('action', $('#formExport').attr('new-action'));
        } else {
            $('#formExport').attr('action', $('#module').val() + '/cmdExport');
        }
        var thispage = $('#thispage').val();
        var pagesize = $('#pagesize').val();

        var page = $(this).attr('page');
        var limit = $(this).attr('limit');
        $('#formExport input[name=thispage]').val(parseInt(page));
        $('#formExport input[name=pagesize]').val(parseInt(limit));
        $('#btnSubmitExport').trigger('click');

        $('#formExport input[name=thispage]').val(thispage);
        $('#formExport input[name=pagesize]').val(pagesize);
    });

    $(document).delegate('.btn-import-all', 'click', function () {
        var height = $(this).attr('data-height') ? $(this).attr('data-height') : 120;
        $.fancybox({
            width: 400,
            height: height,
            autoSize: false,
            href: '#showFormImport',
            padding: 20,
            closeBtn: true,
            beforeShow: function () {
                $('.fancybox-overlay').css('z-index', 1000);
                if (typeof load_import_on_success === 'function') load_import_on_success();
            },
        });
    });

    $(document).delegate('.btn-import-file', 'click', function () {
        if ($('#file_import').val() == '') {
            $('#alert-box .modal-title').html('Please choose your file.');
            $('#alert-box .modal-title').prepend('<span class="glyphicon glyphicon-exclamation-sign" style="color:#FF0000;"></span> ');
            $('#alert-box').modal('show');
        } else {
            $.fancybox.close();
            $.fancybox({
                width: 800,
                height: 150,
                autoSize: false,
                href: '#showWaitImport',
                padding: 20,
                closeBtn: false,
                helpers: {
                    overlay: { closeClick: false },
                },
                beforeShow: function () {
                    $('.fancybox-overlay').css('z-index', 1000);
                    $('.fancybox-skin').css({ backgroundColor: 'transparent', '-webkit-box-shadow': 'none', 'box-shadow': 'none', color: '#FFF' });
                },
            });

            if ($('#refid').size() > 0) {
                $('#formImport').append('<input type="hidden" name="refid" id="refid" value="' + $('#refid').val() + '" />');
            } else {
                $('#formImport #refid').remove();
            }

            if ($('#formImport').attr('new-action')) {
                $('#formImport').attr('action', $('#formImport').attr('new-action'));
            } else {
                $('#formImport').attr('action', $('#module').val() + '/cmdImport');
            }

            $('#formImport').submit();
        }
    });

    $('#formImport').submit(function (event) {
        var url = $(this).attr('action');
        var options = {
            url: url,
            type: 'post',
            dataType: 'json',
            clearForm: false,
            resetForm: false,
            timeout: 0,
            beforeSend: function () {
                $('.btn').prop('disabled', true);
            },
            success: function (res) {
                $('.btn').prop('disabled', false);
                if (res.status == false) {
                    $('#alert-box .modal-title').html(res.message);
                    $('#alert-box .modal-title').prepend('<span class="glyphicon glyphicon-exclamation-sign" style="color:#FF0000;"></span> ');
                    $('#alert-box').modal('show');
                } else {
                    window.location.href = res.url;
                }
                $.fancybox.close();
            },
            error: function () {
                $('.btn').prop('disabled', false);
                $.fancybox.close();
            },
        };
        $(this).ajaxSubmit(options);
        return false;
    });

    if ($('#formList').width() >= $('#formList table').width()) {
        $('#formList table').css('width', '100%');
    }

    $('#myForm').keypress(function (e) {
        if (e.which == 13) return false;
        if (e.which == 13) e.preventDefault();
    });

    $('#myForm #myTab a').on('shown.bs.tab', function (e) {
        var tabIndex = $('#myForm #myTab a').index($(this));
        $('#myTabContent .tab-pane:eq(' + tabIndex + ')')
            .find('div[id^=child_]')
            .each(function (index, item) {
                var height = $(item).find('iframe').contents().find('html').height();
                var child = $(this).attr('id').replace('child_', '');
                resize_iframe(child, height);
            });
    });

    var hashId = window.location.hash;
    if (hashId) {
        var tabIndex = $('#myTabContent ' + hashId).index();
        if (tabIndex > -1) $('#myTab a:eq(' + tabIndex + ')').tab('show');
    }

    changeSearchResetUrl();

    $('.counter').each(function () {
        $(this)
            .prop('Counter', 0)
            .animate(
                {
                    Counter: $(this).text(),
                },
                {
                    duration: 1000,
                    easing: 'swing',
                    step: function (now) {
                        $(this).text(Math.ceil(now));
                    },
                }
            );
    });
});

function changeSearchResetUrl() {
    var url = window.location.href;
    window.history.pushState(null, null, url.replace('?search=reset', ''));
}

function resize_iframe(child, height) {
    $('#iframe-' + child).height(height);
}

function drawImageProp(ctx, img, x, y, w, h, offsetX, offsetY) {
    if (arguments.length === 2) {
        x = y = 0;
        w = ctx.canvas.width;
        h = ctx.canvas.height;
    }

    // default offset is center
    offsetX = typeof offsetX === 'number' ? offsetX : 0.5;
    offsetY = typeof offsetY === 'number' ? offsetY : 0.5;

    // keep bounds [0.0, 1.0]
    if (offsetX < 0) offsetX = 0;
    if (offsetY < 0) offsetY = 0;
    if (offsetX > 1) offsetX = 1;
    if (offsetY > 1) offsetY = 1;

    var iw = img.width,
        ih = img.height,
        r = Math.min(w / iw, h / ih),
        nw = iw * r, // new prop. width
        nh = ih * r, // new prop. height
        cx,
        cy,
        cw,
        ch,
        ar = 1;

    // decide which gap to fill
    if (nw < w) ar = w / nw;
    if (Math.abs(ar - 1) < 1e-14 && nh < h) ar = h / nh; // updated
    nw *= ar;
    nh *= ar;

    // calc source rectangle
    cw = iw / (nw / w);
    ch = ih / (nh / h);

    cx = (iw - cw) * offsetX;
    cy = (ih - ch) * offsetY;

    // make sure source rectangle is valid
    if (cx < 0) cx = 0;
    if (cy < 0) cy = 0;
    if (cw > iw) cw = iw;
    if (ch > ih) ch = ih;

    // fill image in dest. rectangle
    ctx.drawImage(img, cx, cy, cw, ch, x, y, w, h);
}

function disable_value() {
    $('#myTabSearchContent .tab-pane input').attr('disabled', false);
    $('#myTabSearchContent .tab-pane select').attr('disabled', false);
    $('#myTabSearchContent .tab-pane textarea').attr('disabled', false);
    $('#myTabSearchContent .tab-pane:hidden input').attr('disabled', true);
    $('#myTabSearchContent .tab-pane:hidden select').attr('disabled', true);
    $('#myTabSearchContent .tab-pane:hidden textarea').attr('disabled', true);
}

function close_menu(close) {
    var url = urlpath + 'authen/close_menu';
    var data = { close: close };

    $.post(url, data, function (res) {}, 'json');
}

function load_date_picker() {
    $('input.date').datetimepicker({
        format: 'd/m/Y',
        timepicker: false,
        closeOnDateSelect: true,
        scrollMonth: false,
        scrollInput: false,
        onSelectDate: function (ct, obj) {
            $(obj).parents('.form-group').removeClass('has-error has-feedback').addClass('has-success');
            $(obj).parents('.form-group').find('.help-block').hide();
        },
    });

    $('input.datetime').datetimepicker({
        format: 'd/m/Y H:i',
        scrollMonth: false,
        scrollInput: false,
        onSelectDate: function (ct, obj) {
            $(obj).parents('.form-group').removeClass('has-error has-feedback').addClass('has-success');
            $(obj).parents('.form-group').find('.help-block').hide();
        },
    });
}

function default_date_picker() {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();
    var hh = today.getHours();
    var ii = today.getMinutes();
    var year = 10;

    if (dd < 10) dd = '0' + dd;
    if (mm < 10) mm = '0' + mm;
    if (hh < 10) hh = '0' + hh;
    if (ii < 10) ii = '0' + ii;

    var startdate = dd + '/' + mm + '/' + yyyy;
    var enddate = dd + '/' + mm + '/' + (yyyy + year);
    var starttime = dd + '/' + mm + '/' + yyyy + ' ' + hh + ':' + ii;
    var endtime = dd + '/' + mm + '/' + (yyyy + year) + ' ' + hh + ':' + ii;

    $('input.date.startdate').each(function () {
        if ($(this).val() == '') {
            $(this).val(startdate);
        }
    });
    $('input.date.enddate').each(function () {
        if ($(this).val() == '') {
            $(this).val(enddate);
        }
    });
    $('input.datetime.startdate').each(function () {
        if ($(this).val() == '') {
            $(this).val(starttime);
        }
    });
    $('input.datetime.enddate').each(function () {
        if ($(this).val() == '') {
            $(this).val(endtime);
        }
    });
}

function load_validate(FormID, validate) {
    $('#' + FormID).bootstrapValidator({
        fields: validate,
    });
}

$(function () {
    $('.editor').each(function () {
        var rows = $(this).attr('rows');
        var height = rows != '' ? rows * 100 : 250;
        CKEDITOR.replace($(this).attr('id'), {
            height: height,
            toolbarGroups: [{ name: 'clipboard', groups: ['clipboard', 'undo'] }, { name: 'editing', groups: ['find', 'selection', 'spellchecker'] }, { name: 'links' }, { name: 'tools' }, { name: 'document', groups: ['mode', 'document', 'doctools'] }, { name: 'others' }, '/', { name: 'basicstyles', groups: ['basicstyles', 'cleanup'] }, { name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'] }, { name: 'styles' }, { name: 'insert', groups: ['Image'] }, { name: 'colors' }],
        });
    });
    update_ckeditor();

    $(document).delegate('button.btn-status-action', 'click', function () {
        cmd_update_status(this);
    });
});

function update_ckeditor() {
    for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].on('change', function () {
            CKEDITOR.instances[this.name].updateElement();
        });

        CKEDITOR.instances[instance].on('instanceReady', function () {
            ck_success();
        });
    }
}

function ck_success() {
    $('.cke_button__save_label')
        .parents('a')
        .removeAttr('href')
        .removeAttr('onclick')
        .removeAttr('onkeydown')
        .removeAttr('onfocus')
        .click(function (e) {
            e.preventDefault();
            $('.btn-save-publish').trigger('click');
        });
}

function number_format(value, dec) {
    return value.toFixed(dec).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,');
}

function cmd_update_status(obj) {
    var parent = $(obj).parents('.form-group');
    var module = $('#module').val();
    var status_col = $(parent).find('select').attr('col');
    var status_val = $(parent).find('select').val();
    if ($('#formList input[type=checkbox][name^=id]:checked').size() == 0) {
        $('#alert-box .modal-title').html('Please select at least 1 item.');
        $('#alert-box').modal('show');
    } else if (status_val == '' || status_val == null) {
        var text = $(parent).find('select option:first').text();
        $('#alert-box .modal-title').html('Please select at ' + text + '.');
        $('#alert-box').modal('show');
    } else {
        $.fancybox.showLoading();
        var url = urlbase + module + '/cmdUpdateStatus';
        var data = $('#formList').serialize() + '&status_val=' + status_val + '&status_col=' + status_col;
        $.post(url, data, function (res) {
            $.fancybox.hideLoading();
            window.location.href = urlbase + module;
        });
    }
}

function alert_rep_export() {
    var obj = {};

    obj.title = 'ระบบแนะนำให้คุณเลือกสาขาก่อนสร้างรายงานหรือใช้ "Export CSV"';
    obj.body = 'ระบบอาจทำรายการไม่สำเร็จเนื่องจากมีข้อมูลมากเกินไป<br />และส่งผลให้ผู้ใช้ระบบอยู่เกินความล่าช้าหรือล่มได้<br />';
    obj.body += 'หาจคุณต้องการข้อมูลทั้งหมดโดยไม่เลือกสาขา ระบบแนะนำให้คุณทำการส่งออกแบบ "CSV"  แทน';
    obj.id = 'alert-dialog-export';
    obj.button = [];
    obj.button.push({
        label: 'ดาวน์โหลดน์ต่อไป',
        func: 'send_action_export();',
        Class: 'btn-dark',
    });
    dialogCreate(obj);

    $(document).delegate('.btn-alert-export', 'click', function () {
        var target = $(this).attr('target');
        if ($('#branchs_id').val() == null) {
            $('#alert-dialog-export .btn-default').text('ยอมรับการยกเลิก');
            $('#alert-dialog-export .btn-dark').attr('onclick', "send_action_export('" + target + "');");
            $('#alert-dialog-export').modal('show');
        } else {
            send_action_export(target);
        }
    });
}

function get_format_duration(hour, minute, second) {
    var duration = '';
    if (hour) duration += hour + ':';
    if (minute) duration += minute + ':';
    if (second) duration += second + ':';

    var textDuration = duration.substr(0, duration.length - 1);
    if (textDuration) {
        return textDuration;
    } else {
        return 'ไม่ระบุเวลา';
    }
}

function convert_duration_time(time, isVideo = true) {
    if (isVideo) {
        hours = Math.floor(time / 3600);
        minutes = Math.floor((time / 60) % 60);
        seconds = Math.floor(time % 60);
        seconds = seconds < 10 ? `0${seconds}` : seconds;
        minutes = minutes < 10 ? `0${minutes}` : minutes;
        hours = hours < 10 ? `0${hours}` : hours;
        return `${hours}:${minutes}:${seconds}`;
    } else {
        hour = time.video_hour ? parseInt(time.video_hour) * 3600 : 0;
        minute = time.video_minute ? parseInt(time.video_minute) * 60 : 0;
        second = time.video_second ? parseInt(time.video_second) : 0;
        return hour + minute + second;
    }
}

function get_image_resize(image_url, width, height) {
    var urls = urlbase.split(window.location.hostname);
    image_url = `${urls[1]}${image_url}`.replace('//', '/');
    console.log('url::', image_url);

    var image_size = `w${width}-h${height}`;
    var image_cover = `c${width}x${height}`;
    return urlbase + `res/${image_size}-${image_cover}` + image_url;
}

function getFormGroupTemplate() {
    var formGroup = `<div class="form-group">
    <label class="col-sm-3 control-label">{{label}}</label>
      <div class="col-sm-9">
        <label class="control-label">{{value}}</label>
      </div></div>`;
    return formGroup;
}

function random_password(length) {
    var result = '';
    var characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

AllowClear = $.fn.select2.amd.require('select2/selection/allowClear');
var oldClear = AllowClear.prototype._handleClear;
AllowClear.prototype._handleClear = function (_, event) {
    if (event.target.className === 'select2-selection__clear') {
        if (this.options.get('disabled')) {
            return;
        }
        event.stopPropagation();
        this.$element.val(null).trigger('change');
    } else {
        oldClear.apply(this, arguments);
    }
};

function replaceDoubleQuote(text) {
    if (text) {
        const newText = `${text}`.replace(/"/g, '&quot;');
        return newText;
    } else {
        return text;
    }
}

var branchAll;
var areaAll;

function addEventChangeStore(elStoreId, elBranchId, branchId, elAreaId, areaId) {
    var store_id_selected = $(elStoreId).find('option:selected').val();

    changeStoreToBranch(elBranchId, store_id_selected, branchId);

    if (elAreaId) {
        changeStoreToArea(elAreaId, store_id_selected, areaId);
    }

    $(document).delegate(elStoreId, 'change', function () {
        var store_selected = $(this).find('option:selected').size();
        var store_id_selected;
        if (store_selected > 1) {
            store_id_selected = [];
            $(this)
                .find('option:selected')
                .each(function (k, store) {
                    store_id_selected.push(store.value);
                });
        } else {
            store_id_selected = $(this).find('option:selected').val();
        }
        changeStoreToBranch(elBranchId, store_id_selected, null);
        if (elAreaId) {
            changeStoreToArea(elAreaId, store_id_selected, null);
        }
    });
}

function changeStoreToBranch(elBranchId, storeId, branchId) {
    $(elBranchId).val(null);
    if (!branchAll) {
        branchAll = [];
        $.ajax({
            dataType: 'json',
            type: 'POST',
            url: urlpath + 'employees/branchs/getBranchAll',
            success: function (res) {
                branchAll = res;
                renderBranchOption(branchAll, elBranchId, storeId, branchId);
            },
        });
    } else {
        renderBranchOption(branchAll, elBranchId, storeId, branchId);
    }
}

function renderBranchOption(branchAll, elBranchId, storeId, branchId) {
    var branchFilter = [];
    var branchHtml = '';

    if (storeId) {
        branchAll.forEach(function (branch) {
            const storeIds = Array.isArray(storeId) ? storeId : [storeId];
            storeIds.forEach(function (stores_id) {
                if (branch.stores_id == stores_id) {
                    branchFilter.push(branch);
                }
            });
        });
    } else {
        branchFilter = branchAll;
    }

    var initialOption = 'Selected';
    if (elBranchId) {
        initialOption = $(elBranchId + " option[value='']").text();
    }
    branchHtml += '<option value="">' + initialOption + '</option>';

    branchFilter.forEach(function (branch) {
        const selected = branch.branchs_id == branchId ? 'selected="selected"' : '';
        branchHtml += '<option value="' + branch.branchs_id + '" ' + selected + '>' + branch.branch_code + ' : ' + branch.branch_name + '</option>';
    });

    if (elBranchId) {
        $(elBranchId).html(branchHtml);
    }

    return branchHtml;
}

function changeStoreToArea(elAreaId, storeId, areaId) {
    $(elAreaId).val(null);
    if (!areaAll) {
        areaAll = [];
        $.ajax({
            dataType: 'json',
            type: 'POST',
            url: urlpath + 'employees/branchs/getAreaAll',
            success: function (res) {
                areaAll = res;
                renderAreaOption(areaAll, elAreaId, storeId, areaId);
            },
        });
    } else {
        renderAreaOption(areaAll, elAreaId, storeId, areaId);
    }
}

function renderAreaOption(areaAll, elAreaId, storeId, areaId) {
    var areaFilter = [];
    var areaHtml = '';

    if (storeId) {
        areaAll.forEach(function (area) {
            const storeIds = Array.isArray(storeId) ? storeId : [storeId];
            storeIds.forEach(function (stores_id) {
                if (area.store_ids.includes(`${stores_id}`)) {
                    areaFilter.push(area);
                }
            });
        });
    } else {
        areaFilter = areaAll;
    }

    areaHtml += '<option value="">Selected</option>';
    areaFilter.forEach(function (area) {
        const selected = area.employees_id == areaId ? 'selected="selected"' : '';
        areaHtml += '<option value="' + area.employees_id + '" ' + selected + '>' + area.username + ' : ' + area.fullname + '</option>';
    });

    if (elAreaId) {
        $(elAreaId).html(areaHtml);
    }

    return areaHtml;
}
