$(function () {
    $('#listCtrl .btn-sort,#formCtrl .btn-sort').click(function () {
        var sorting = $(this).attr('sorting');

        if ($(this).hasClass('desc')) {
            $('#listCtrl .btn-sort,#formCtrl .btn-sort').removeClass('desc').removeClass('asc');
            $(this).addClass('asc');
            $('#orderby').val('asc');
            $('#sorting').val(sorting);
        } else {
            $('#listCtrl .btn-sort,#formCtrl .btn-sort').removeClass('desc').removeClass('asc');
            $(this).addClass('desc');
            $('#orderby').val('desc');
            $('#sorting').val(sorting);
        }

        $('#BoxPage .pagination li').removeClass('active');
        $('#BoxPage .pagination li.first').addClass('active');

        load_page_ajax();
    });

    $(document).delegate('#formSearch .btn-search', 'click', function (e) {
        get_page_ajax(1);
    });

    $(document)
        .delegate('#formSearch input[type=text]')
        .keypress(function (e) {
            if (e.which == 13) {
                get_page_ajax(1);
                return false;
            }
        });
});

function load_page_ajax() {
    $('.main_container .right_col').removeAttr('style');

    var url = '';
    if ($('#formSearch').size() > 0) {
        url = app_list.newUrl ? app_list.newUrl : $('#formSearch').attr('ref');
    } else {
        url = $('#myForm').attr('ref');
    }

    var orderby = {
        name: 'orderby',
        value: $('#orderby').val(),
    };
    var sorting = {
        name: 'sorting',
        value: $('#sorting').val(),
    };
    var thispage = {
        name: 'thispage',
        value: $('#thispage').val(),
    };
    var pagesize = {
        name: 'pagesize',
        value: $('#pagesize').val(),
    };

    var TabSelected = {
        name: 'TabSelected',
        value: $('#TabSelected').val(),
    };

    remove_form_post(form_post, 'orderby');
    form_post.push(orderby);
    remove_form_post(form_post, 'sorting');
    form_post.push(sorting);
    remove_form_post(form_post, 'thispage');
    form_post.push(thispage);
    remove_form_post(form_post, 'pagesize');
    form_post.push(pagesize);
    remove_form_post(form_post, 'TabSelected');
    form_post.push(TabSelected);

    var $scope = null;

    if ($('#listCtrl').size() > 0) {
        $scope = angular.element('#listCtrl').scope();
    } else if ($('#formCtrl').size() > 0) {
        $scope = angular.element('#formCtrl').scope();
    }

    $.ajax({
        dataType: 'json',
        type: 'POST',
        url: urlpath + url,
        data: form_post,
        beforeSend: function () {
            $.fancybox.showLoading();
        },
        success: function (res) {
            $.fancybox.hideLoading();

            $scope.$apply(function () {
                $scope.setValue(res);
                loadToolTip();
            });

            load_on_success(res);
            show_page();
            loadToolTip();
            load_fancybox();
        },
    });
}

function load_on_success(res) {
    /*custom*/
}

function remove_form_post(obj, name) {
    for (var key in obj) {
        if (!obj.hasOwnProperty(key)) continue;
        var obj2 = obj[key];
        for (var prop in obj2) {
            if (!obj2.hasOwnProperty(prop)) continue;

            if (prop == 'name' && obj2[prop] == name) {
                delete obj[key];
            }
        }
    }

    form_post = form_post.filter(function (el) {
        return el != null;
    });
}

function show_page() {
    var html = '';
    var totalpage = $('input[name=totalpage]').val();
    var thispage = $('input[name=thispage]').val();

    var size = Math.ceil(thispage / numsize) * numsize;
    var start = size - (numsize - 1);
    var limit = size > totalpage ? totalpage : size;

    html += '<li><a href="javascript:;" class="btn-first" onclick="move_page(-1,\'first\')">&laquo;</a></li>';
    html += '<li><a href="javascript:;" class="btn-prev" onclick="move_page(-1)">&lt;</a></li>';

    for (var i = start; i <= limit; i++) {
        var first = i == 1 ? ' first' : '';
        if (thispage == i) html += '<li class="active' + first + '"><a href="javascript:;">' + i + '</a></li>';
        else html += '<li><a href="javascript:;" onclick="get_page(' + i + ')">' + i + '</a></li>';
    }

    html += '<li><a href="javascript:;" class="btn-next" onclick="move_page(1)">&gt;</a></li>';
    html += '<li><a href="javascript:;" class="btn-last" onclick="move_page(1,\'last\')">&raquo;</a></li>';

    $('#BoxPage .pagination').html(html);
}

function get_page(thispage) {
    if (thispage <= $('input[id=totalpage]').val()) {
        var endpage = $('select[name=pagesize]').val();
        set_page(thispage, endpage);
        load_page_ajax();
    }
}

function set_page(thispage, endpage) {
    var startpage = thispage * endpage - endpage;
    $('input[id=startpage]').val(startpage);
    $('input[id=endpage]').val(endpage);
    $('input[id=thispage]').val(thispage);
}

function move_page(page) {
    var params = move_page.arguments[1];
    var endpage = $('select[name=pagesize]').val();
    var totalpage = parseInt($('input[id=totalpage]').val(), 10);
    var currentpage = parseInt($('input[id=thispage]').val(), 10);
    var thispage = currentpage + eval(page);

    if (params == 'first' && currentpage != 1) {
        thispage = 1;
        set_page(thispage, endpage);
        load_page_ajax();
    } else if (params == 'last' && totalpage != currentpage) {
        thispage = totalpage;
        set_page(thispage, endpage);
        load_page_ajax();
    } else if (totalpage != currentpage && page == 1) {
        set_page(thispage, endpage);
        load_page_ajax();
    } else if (currentpage != 1 && page == -1) {
        set_page(thispage, endpage);
        load_page_ajax();
    }
}

function change_page(pagesize) {
    var items = parseInt($('.num_rows .badge').text());
    var totalpage = Math.ceil(items / pagesize);
    $('input[name=pagesize]').val(pagesize);
    $('input[name=totalpage]').val(totalpage);
    var endpage = $('input[name=pagesize]').val();
    show_page();
    set_page(1, endpage);
    load_page_ajax();
}

function get_page_ajax(thispage) {
    var endpage = $('select[name=pagesize]').val();
    set_page(thispage, endpage);
    search_page_ajax();
}

function search_page_ajax() {
    $('.main_container .right_col').removeAttr('style');
    var url = (url = app_list.newUrl ? app_list.newUrl : $('#formSearch').attr('ref').replace('ajaxList', 'searchList'));

    var orderby = {
        name: 'orderby',
        value: $('#orderby').val(),
    };
    var sorting = {
        name: 'sorting',
        value: $('#sorting').val(),
    };
    var thispage = {
        name: 'thispage',
        value: $('#thispage').val(),
    };
    var pagesize = {
        name: 'pagesize',
        value: $('#pagesize').val(),
    };

    var TabSelected = {
        name: 'TabSelected',
        value: $('#TabSelected').val(),
    };

    //clear post
    $('#formSearch #myTabSearchContent .tab-pane')
        .find('input,select,textarea')
        .each(function () {
            var name = $(this).attr('name');
            remove_form_post(form_post, name);
        });

    //add post
    $('#formSearch #myTabSearchContent .tab-pane.active')
        .find('input,select,textarea')
        .each(function () {
            var name = $(this).attr('name');
            var postObj = { name: name, value: $(this).val() };
            form_post.push(postObj);
        });

    remove_form_post(form_post, 'orderby');
    form_post.push(orderby);
    remove_form_post(form_post, 'sorting');
    form_post.push(sorting);
    remove_form_post(form_post, 'thispage');
    form_post.push(thispage);
    remove_form_post(form_post, 'pagesize');
    form_post.push(pagesize);
    remove_form_post(form_post, 'TabSelected');
    form_post.push(TabSelected);

    if (TabSelected.value == '#TabAdvance') {
        var sys_action = {
            name: 'sys_action',
            value: '',
        };
        remove_form_post(form_post, 'sys_action');
        form_post.push(sys_action);

        $('#item-sys_action li').removeClass('active');
        $('#item-sys_action li:first').addClass('active');
    }

    if ($('#refid').size() > 0) {
        var refid = { name: 'refid', value: $('#refid').val() };
        remove_form_post(form_post, 'refid');
        form_post.push(refid);
    }

    var $scope = null;

    if ($('#listCtrl').size() > 0) {
        $scope = angular.element('#listCtrl').scope();
    } else if ($('#formCtrl').size() > 0) {
        $scope = angular.element('#formCtrl').scope();
    }

    createDataExport();
    $.ajax({
        dataType: 'json',
        type: 'POST',
        url: urlpath + url,
        data: form_post,
        beforeSend: function () {
            $.fancybox.showLoading();
        },
        success: function (res) {
            $.fancybox.hideLoading();

            $scope.$apply(function () {
                var rows_all = 0;
                if ($('#sys_action').val() == '') {
                    rows_all = res.option.rows;
                } else {
                    rows_all = res.option.rows_publish + res.option.rows_modified + res.option.rows_unpublish;
                }

                $scope.rows = rows_all;
                $scope.rows_all = rows_all;
                $scope.rows_publish = res.option.rows_publish;
                $scope.rows_modified = res.option.rows_modified;
                $scope.rows_unpublish = res.option.rows_unpublish;
                $scope.rows_delete = res.option.rows_delete;
                $scope.totalpage = res.option.totalpage;

                if ($('#sys_action').val() == 'delete') {
                    $scope.rows = res.option.rows;
                    $scope.rows_all = res.option.rows_publish + res.option.rows_modified + res.option.rows_unpublish;
                }

                $scope.setValue(res.item);
                loadToolTip();
            });

            load_on_success(res.item);
            show_page();
            loadToolTip();
            load_fancybox();
        },
    });
}

function createDataExport() {
    if ($('#formExport').size() > 0) {
        var sorting = '<input type="hidden" name="sorting" value="' + $('#sorting').val() + '">';
        var orderby = '<input type="hidden" name="orderby" value="' + $('#orderby').val() + '">';
        var thispage = '<input type="hidden" name="thispage" value="' + $('#thispage').val() + '">';
        var pagesize = '<input type="hidden" name="pagesize" value="' + $('#pagesize').val() + '">';
        var refid = '<input type="hidden" name="refid" value="' + $('#refid').val() + '">';
        var sys_action = '<input type="hidden" name="sys_action" value="' + $('#sys_action').val() + '">';
        var control = sorting + orderby + thispage + pagesize + sys_action + refid;

        //add post
        var input = '';
        $('#formSearch #myTabSearchContent .tab-pane.active')
            .find('input,select,textarea')
            .each(function () {
                var name = $(this).attr('name');
                input += '<input type="hidden" name="' + name + '" value="' + $(this).val() + '">';
            });
        $('#formExport').html(input + control + '<input type="submit" id="btnSubmitExport" value="" />');
    }
}
