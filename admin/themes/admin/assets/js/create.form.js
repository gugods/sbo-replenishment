var lookup = [];
var form_post = [];

function createForm(form, post, module, child) {
    var $html = '';
    var $h2 = [];
    var $groups = '';

    for (var $i in form) {
        var $item = form[$i];

        if (typeof $item.groups !== 'undefined' && $item.groups != $groups) {
            if ($i == 0) {
                $html += '<h2>' + $item.groups + '</h2>';
            } else {
                $html += '<hr /><h2>' + $item.groups + '</h2>';
            }
            $groups = $item.groups;
        }

        var $itemClass = typeof $item.class !== 'undefined' ? $item.class : '';
        var $itemRows = typeof $item.rows !== 'undefined' ? $item.rows : '';
        var $itemValue = typeof $item.value !== 'undefined' ? $item.value : '';
        var $itemWidth = typeof $item.width !== 'undefined' ? $item.width : '';
        var $itemMultiple = typeof $item.multiple !== 'undefined' ? $item.multiple : '';
        var $itemDownload = typeof $item.download !== 'undefined' ? $item.download : '';
        var $itemMaxlength = typeof $item.maxlength !== 'undefined' ? $item.maxlength : '';
        var $required = typeof $item.validate !== 'undefined' && $item.validate.search('notEmpty') > -1 ? '<span class="required"> * </span>' : '';

        $html += '<div class="form-group">';
        if ($item.type == 'child') {
            $html += '<div id="' + $item.name + '" child="' + $item.id + '" class="col-sm-offset-1 col-sm-11 child">';
            $html += '</div>';
        } else if ($item.type == 'text' || $item.type == 'email' || $item.type == 'number') {
            $html += '<label for="' + $item.name + '" class="col-sm-3 control-label">' + $required + $item.label + '</label>';
            $html += '<div class="col-sm-9">';
            var $value = typeof post[$item.name] !== 'undefined' && post[$item.name] !== null ? post[$item.name] : $itemValue;
            $html += '<input type="text" class="form-control ' + $itemClass + '" name="' + $item.name + '" id="' + $item.id + '" placeholder="' + $item.label + '" value="' + replaceDoubleQuote($value) + '" style="width:' + $itemWidth + '" maxlength="' + $itemMaxlength + '" />';
            if (typeof $item.alert !== 'undefined') $html += '<div class="alert alert-warning">' + $item.alert + '</div>';
            $html += '</div>';
        } else if ($item.type == 'password') {
            $html += '<label for="' + $item.name + '" class="col-sm-3 control-label">' + $required + $item.label + '</label>';
            $html += '<div class="col-sm-9">';
            var $value = typeof post[$item.name] !== 'undefined' && post[$item.name] !== null ? post[$item.name] : $itemValue;
            $html += '<input type="password" class="form-control ' + $itemClass + '" name="' + $item.name + '" id="' + $item.id + '" placeholder="' + $item.label + '" value="' + replaceDoubleQuote($value) + '" style="width:' + $itemWidth + '" maxlength="' + $itemMaxlength + '" />';
            if (typeof $item.alert !== 'undefined') $html += '<div class="alert alert-warning">' + $item.alert + '</div>';
            $html += '</div>';
        } else if ($item.type == 'rangedate') {
            $html += '<label for="' + $item.name + '" class="col-sm-3 control-label">' + $required + $item.label + '</label>';
            $html += '<div class="col-sm-9">';
            var $name_to_start = $item.name + '_to_start';
            var $name_to_end = $item.name + '_to_end';
            var $id_to_start = $item.id + '_to_start';
            var $id_to_end = $item.id + '_to_end';
            var $value_to_start = typeof post[$name_to_start] !== 'undefined' && post[$name_to_start] !== null ? getDateFormat(post[$name_to_start]) : '';
            var $value_to_end = typeof post[$name_to_end] !== 'undefined' && post[$name_to_end] !== null ? getDateFormat(post[$name_to_end]) : '';
            $html += '<input type="text" class="form-control date ' + $itemClass + '" name="' + $name_to_start + '" id="' + $id_to_start + '" placeholder="Start" value="' + $value_to_start + '" style="width:' + $itemWidth + ';display: inline-block;" maxlength="' + $itemMaxlength + '" autocomplete="off" />';
            $html += '<label class="control-label" style="margin-left: 10px;margin-right: 6px;">to</label>';
            $html += '<input type="text" class="form-control date ' + $itemClass + '" name="' + $name_to_end + '" id="' + $id_to_end + '" placeholder="End" value="' + $value_to_end + '" style="width:' + $itemWidth + ';display: inline-block;" maxlength="' + $itemMaxlength + '" autocomplete="off" />';
            $html += '</div>';
        } else if ($item.type == 'date') {
            $html += '<label for="' + $item.name + '" class="col-sm-3 control-label">' + $required + $item.label + '</label>';
            $html += '<div class="col-sm-9">';
            var $value = typeof post[$item.name] !== 'undefined' && post[$item.name] !== null ? getDateFormat(post[$item.name]) : '';
            $html += '<input type="text" class="form-control date ' + $itemClass + '" name="' + $item.name + '" id="' + $item.id + '" placeholder="' + $item.label + '" value="' + replaceDoubleQuote($value) + '" style="width:' + $itemWidth + '" maxlength="' + $itemMaxlength + '" autocomplete="off" />';
            if (typeof $item.alert !== 'undefined') $html += '<div class="alert alert-warning">' + $item.alert + '</div>';
            $html += '</div>';
        } else if ($item.type == 'datetime') {
            $html += '<label for="' + $item.name + '" class="col-sm-3 control-label">' + $required + $item.label + '</label>';
            $html += '<div class="col-sm-9">';
            var $value = typeof post[$item.name] !== 'undefined' && post[$item.name] !== null ? getDatetimeFormat(post[$item.name]) : '';
            $html += '<input type="text" class="form-control datetime ' + $itemClass + '" name="' + $item.name + '" id="' + $item.id + '" placeholder="' + $item.label + '" value="' + replaceDoubleQuote($value) + '" style="width:' + $itemWidth + '" maxlength="' + $itemMaxlength + '" autocomplete="off" />';
            if (typeof $item.alert !== 'undefined') $html += '<div class="alert alert-warning">' + $item.alert + '</div>';
            $html += '</div>';
        } else if ($item.type == 'duration') {
            $html += '<label for="' + $item.name + '" class="col-sm-3 control-label">' + $required + $item.label + '</label>';
            $html += '<div class="col-sm-9">';
            var $value = typeof post[$item.name] !== 'undefined' && post[$item.name] !== null ? post[$item.name] : $itemValue;
            $html += '<input type="text" class="form-control ' + $itemClass + '" name="' + $item.name + '" id="' + $item.id + '" placeholder="' + $item.label + '" value="' + convert_duration_time($value) + '" style="width:' + $itemWidth + '" maxlength="' + $itemMaxlength + '" />';
            if (typeof $item.alert !== 'undefined') $html += '<div class="alert alert-warning">' + $item.alert + '</div>';
            $html += '</div>';
        } else if ($item.type == 'file' || $item.type == 'image') {
            $html += '<label for="' + $item.name + '" class="col-sm-3 control-label">' + $required + $item.label + '</label>';
            $html += '<div class="col-sm-9 col-file">';
            var $value = typeof post[$item.name] !== 'undefined' && post[$item.name] !== null ? post[$item.name] : '';
            var $itemFiletype = typeof $item.filetype !== 'undefined' ? $item.filetype : '';
            var $isImage = $item.type == 'image' ? true : false;
            //$filetype = explode("|",@$item["filetype"]);
            var $module = $item.filepath ? $item.filepath : module;
            var $editpath = urlbase + 'upload/' + $module + '/thumb_edit/' + $value;
            var $urlpath = urlbase + 'upload/' + $module + '/' + $value;
            var showUpload = true;
            var $imageUpload = '';
            $html += '<div class="display-file">';
            if ($isImage && $value != '') {
                if (child) {
                    $html += '<img src="' + $editpath + '" onerror="this.onerror=null;this.src=\'themes/admin/assets/images/no-image.png\';" class="img-preview-child" /> <a href="javascript:;" rel="' + replaceDoubleQuote($value) + '" ref="' + $item.name + '" class="glyphicon glyphicon-remove btn-delete-file-child" data-toggle="tooltip" title="click to delete"></a>';
                } else {
                    $html += '<img src="' + $editpath + '" onerror="this.onerror=null;this.src=\'themes/admin/assets/images/no-image.png\';" class="img-preview" /> <a href="javascript:;" rel="' + replaceDoubleQuote($value) + '" ref="' + $item.name + '" class="glyphicon glyphicon-remove btn-delete-file" data-toggle="tooltip" title="click to delete"></a>';
                }
                if ($value && $itemDownload == 'T') {
                    var $fullpath = urlbase + 'upload/' + $module + '/' + $value;
                    $html += '<div style="margin-top:5px"><a href="' + $fullpath + '" download class="btn btn-sm btn-dark"><span class="glyphicon glyphicon-download-alt"></span> Download Image</a></div>';
                }
                showUpload = false;
            }
            if (!$isImage && $value != '') {
                if (child) {
                    $html += '<a href="' + $urlpath + '" target="_blank">' + $value + '</a> <a href="javascript:;" rel="' + replaceDoubleQuote($value) + '" ref="' + $item.name + '" class="glyphicon glyphicon-remove btn-delete-file-child" data-toggle="tooltip" title="click to delete"></a>';
                } else {
                    $html += '<a href="' + $urlpath + '" target="_blank">' + $value + '</a> <a href="javascript:;" rel="' + replaceDoubleQuote($value) + '" ref="' + $item.name + '" class="glyphicon glyphicon-remove btn-delete-file" data-toggle="tooltip" title="click to delete"></a>';
                }
                showUpload = false;
            }
            $html += '</div>';

            var upload_hide = showUpload ? '' : ' style="display:none" ';
            if ($isImage) $imageUpload = 'imageUpload';
            if ($isImage) $html += '<div class="imageCanvas" style="display: none;position:relative;"><canvas></canvas><a href="javascript:;" style="position: absolute;right: 30%;top: 30%;" class="glyphicon glyphicon-remove btn-remove-file"></a></div>';
            $html += '<div class="upload-file"' + upload_hide + '>';
            $html += '<input type="file" class="' + $imageUpload + ' ' + $itemClass + '" name="' + $item.name + '"  id="' + $item.id + '" filetype="' + $itemFiletype + '" value="' + replaceDoubleQuote($value) + '" />';
            if (typeof $item.alert !== 'undefined') $html += '<div class="alert alert-warning">' + $item.alert + '</div>';
            $html += '</div>';

            $html += '</div>';
        } else if ($item.type == 'textarea') {
            $html += '<label for="' + $item.name + '" class="col-sm-3 control-label">' + $required + $item.label + '</label>';
            $html += '<div class="col-sm-9">';
            var $value = typeof post[$item.name] !== 'undefined' && post[$item.name] !== null ? post[$item.name] : $itemValue;
            $html += '<textarea class="form-control ' + $itemClass + '" name="' + $item.name + '" id="' + $item.id + '" rows="' + $itemRows + '" style="width:' + $itemWidth + '">' + $value + '</textarea>';
            if (typeof $item.alert !== 'undefined') $html += '<div class="alert alert-warning">' + $item.alert + '</div>';
            $html += '</div>';
        } else if ($item.type == 'select' || $item.type == 'lookup') {
            $html += '<label for="' + $item.name + '" class="col-sm-3 control-label">' + $required + $item.label + '</label>';
            $html += '<div class="col-sm-9">';
            var $value = typeof post[$item.name] !== 'undefined' && post[$item.name] !== null ? post[$item.name] : $itemValue;
            var $nameMultiple = $itemMultiple != 'multiple' ? '' : '[]';
            $html += '<select id="' + $item.id + '" name="' + $item.name + '' + $nameMultiple + '" ' + $itemMultiple + ' class="form-control ' + $itemClass + '" style="width:' + $itemWidth + '">';

            if ($itemMultiple != 'multiple') $html += '<option value="">Selected</option>';

            if ($item.type == 'lookup') {
                var token = $.md5($item.query);
                var newitem = { id: $item.id, token: token, value: $value, multiple: $itemMultiple };
                lookup.push(newitem);
            } else {
                if ($item.data == 'time') {
                    $item.data = [];
                    for (var i = 0; i <= 60; i++) {
                        const time = i < 10 ? '0' + i : i;
                        $item.data.push({ value: time, label: time });
                    }
                } else if ($item.data == 'number') {
                    $item.data = [];
                    $start = parseInt($item.start);
                    $end = parseInt($item.end);
                    for (var i = $start; i <= $end; i++) {
                        $item.data.push({ value: i, label: i });
                    }
                }

                for (var $i2 in $item.data) {
                    var $item2 = $item.data[$i2];

                    if ($itemMultiple != 'multiple') {
                        var $selected = $value == $item2.value ? 'selected="selected"' : '';
                    } else {
                        var $arrayValue = $value + ''.split(',');
                        var $selected = $arrayValue.includes($item2.value) ? 'selected="selected"' : '';
                    }

                    $html += '<option value="' + $item2.value + '" ' + $selected + '>' + $item2.label + '</option>';
                }
            }
            $html += '</select>';
            if (typeof $item.alert !== 'undefined') $html += '<div class="alert alert-warning">' + $item.alert + '</div>';
            $html += '</div>';
        } else if ($item.type == 'checkbox') {
            $html += '<label for="' + $item.name + '" class="col-sm-3 control-label">' + $item.label + '</label>';
            $html += '<div class="col-sm-9">';
            //var $inline = ($item.data.length>1) ? '' : 'checkbox';
            $html += '<div class="">';
            for (var $i2 in $item.data) {
                var $item2 = $item.data[$i2];
                var $checked = typeof post[$item2.name] !== 'undefined' && post[$item2.name] == $item2.value ? 'checked="checked"' : '';
                $html += '<label class="checkbox-inline">';
                $html += '<input type="checkbox" id="' + $item2.id + '" name="' + $item2.name + '" value="' + $item2.value + '" ' + $checked + ' /> ' + $item2.label;
                $html += '</label>';
            }
            $html += '</div>';
            if (typeof $item.alert !== 'undefined') $html += '<div class="alert alert-warning">' + $item.alert + '</div>';
            $html += '</div>';
        } else if ($item.type == 'radio') {
            $html += '<label for="' + $item.name + '" class="col-sm-3 control-label">' + $required + $item.label + '</label>';
            $html += '<div class="col-sm-9">';
            var $inline = $item.data.length > 1 ? '' : 'radio';
            $html += '<div class="' + $inline + '">';
            for (var $i2 in $item.data) {
                var $item2 = $item.data[$i2];
                var $checked = typeof post[$item2.name] !== 'undefined' && post[$item2.name] == $item2.value ? 'checked="checked"' : '';
                $html += '<label class="radio-inline">';
                $html += '<input type="radio" id="' + $item2.id + '" name="' + $item2.name + '" value="' + $item2.value + '" ' + $checked + ' /> ' + $item2.label;
                $html += '</label>';
            }
            $html += '</div>';
            $html += '</div>';
        }
        $html += '</div>';
    } // end for
    return $html;
}

function create_lookup() {
    var lookup_rows = 0;
    for (var i in lookup) {
        var item = lookup[i];
        var $itemMultiple = item.multiple;
        if ($('#refid').size() > 0) {
            item.refid = $('#refid').val();
        }

        $.post(
            urlpath + 'create_lookup',
            item,
            function (res) {
                for (i2 in res) {
                    var item2 = res[i2];

                    if ($itemMultiple != 'multiple') {
                        var $selected = item2.post == item2.value ? 'selected="selected"' : '';
                    } else {
                        var $arrayValue = [];
                        if (item2.post) $arrayValue = item2.post.split(',');
                        var $selected = $arrayValue.includes(item2.value) ? 'selected="selected"' : '';
                    }

                    $('#' + item2.id).append('<option value="' + item2.value + '" ' + $selected + '>' + item2.label + '</option>');
                }
                lookup_rows++;
                if (lookup_rows == lookup.length) load_lookup_success();
            },
            'json'
        );
    }
}

function load_lookup_success() {
    //custom lookup success
    if ($('#formSearch').size() > 0) {
        form_post = $('#formSearch').serializeArray();
        load_page_ajax();
    }
    if ($('#myForm #stores_id').size() > 0 && $('#myForm #branchs_id').size() > 0 && $('#myForm #area_employees_id').size() > 0) {
        addEventChangeStore('#myForm #stores_id', '#myForm #branchs_id', app_post.branchs_id, '#myForm #area_employees_id', app_post.area_employees_id);
    } else if ($('#myForm #stores_id').size() > 0 && $('#myForm #branchs_id').size() > 0) {
        addEventChangeStore('#myForm #stores_id', '#myForm #branchs_id', app_post.branchs_id);
    }

    if ($('#formSearch #stores_id').size() > 0 && $('#formSearch #branchs_id').size() > 0 && $('#formSearch #area_employees_id').size() > 0) {
        addEventChangeStore('#formSearch #stores_id', '#formSearch #branchs_id', '', '#formSearch #area_employees_id');
    } else if ($('#formSearch #stores_id').size() > 0 && $('#formSearch #branchs_id').size() > 0) {
        addEventChangeStore('#formSearch #stores_id', '#formSearch #branchs_id');
    }

    lookup_callback();
}

function lookup_callback() {
    //custom lookup callback
}

function getDateFormat(datetime) {
    if (datetime.search('/') < 0 && datetime.search('-') > -1) {
        var dt = datetime.split(' ');
        var date = dt[0].split('-');
        return date[2] + '/' + date[1] + '/' + date[0];
    } else {
        return datetime;
    }
}

function getDatetimeFormat(datetime) {
    if (datetime.search('/') < 0 && datetime.search('-') > -1) {
        var dt = datetime.split(' ');
        var date = dt[0].split('-');
        if (dt.length > 1) {
            var time = dt[1].split(':');
        } else {
            var time = ['00', '00'];
        }
        return date[2] + '/' + date[1] + '/' + date[0] + ' ' + time[0] + ':' + time[1];
    } else {
        return datetime;
    }
}
