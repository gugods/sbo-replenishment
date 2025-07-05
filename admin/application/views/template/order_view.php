<?php $this->load->view("template/header.php"); ?>
<!-- page content -->
<div class="right_col" role="main">
  <div class="">

    <div class="page-title">
      <div class="title_left">
        <?php $this->load->view("template/breadcrumb.php"); ?>
      </div>
    </div>

    <div class="clearfix"></div>

    <div id="listCtrl" ng-controller="listCtrl" class="row">
      <div class="col-md-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>{{title}} {{ref_name}}</h2>
            <div class="pull-right">
              <button ng-click="setRefresh(ref_back)" type="button" class="btn btn-dark btn-back">
                <span class="glyphicon glyphicon-chevron-left"></span> Back
              </button>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div id="boxSearch">
              <form name="formSearch" id="formSearch" method="post" action="{{module}}" class="form-horizontal" role="form" ref="{{module}}/ajaxList">
              </form>
            </div>
            <span class="txt-gray">Drag and drop the Items below to re-arrange them.</span>
            <div id="BoxPage" class="row" style="margin-top: 0px;">
              <div class="col-md-8"></div>
            </div>
            <div class="row">
              <div class="col-md-12 col-xs-12 num_rows">Items <span class="badge" ng-bind="rows"></span></div>
            </div>
            <div class="table-responsive">
              <form name="formList" id="formList" method="post" ng-action="{{module}}/" ref="{{module}}" class="form-data">

              </form>
              <div ng-if="rows==0" class="col-md-12 col-xs-12 not_found">- ไม่พบข้อมูล -</div>
            </div>
            <input type="hidden" name="totalpage" id="totalpage" value="{{totalpage}}" />
            <input type="hidden" name="thispage" id="thispage" value="{{thispage}}" />
            <input type="hidden" name="pagesize" id="pagesize" value="{{pagesize}}" />
            <input type="hidden" name="sorting" id="sorting" value="{{sorting}}" />
            <input type="hidden" name="orderby" id="orderby" value="{{orderby}}" />
            <input type="hidden" name="module" id="module" value="{{module}}" />
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content -->
<?php $this->load->view("template/footer.php"); ?>
<script type="text/javascript">
  var app_module = '<?php echo isset($child) ? $child : $module; ?>';
  var url_update = '<?php echo isset($child) ? $module . '/' . $child : $module; ?>';

  var dd_html = "";
  var nestList = [];
  $(function() {
    if (app_list.rows > 0) {
      $("#pagesize").val(app_list.rows);
    }
    form_post = $('#formSearch').serializeArray();
    load_page_ajax();
  });

  function load_on_success(res) {

    nestList = res;
    dd_html += '<div class="root-dd" id="nestable">';
    search_parent(nestList, 0);
    dd_html += '</div>';

    $("#formList").html(dd_html);
    $("ol.dd-list").each(function() {
      if ($(this).find('li').size() == 0) $(this).remove();
    });

    $('#nestable').nestable({
        rootClass: 'root-dd',
        group: 0,
        maxDepth: 1
      })
      .on('change', updateOutput);
    $('#nestable').nestable('collapseAll');
  }

  function search_parent(nestList, parent_id) {
    dd_html += ' <ol class="dd-list dd-list-big">';
    $.each(nestList, function(key, item) {
      if (parent_id == item.parent_id) {
        var item_id = getObj(item, app_module + '_id');
        dd_html += '<li class="dd-item" data-id="' + item_id + '">';
        dd_html += '<div class="dd-handle dd3-handle ddd-handle"></div>';
        dd_html += '<div class="dd3-content ddd-content">';

        var order_no = (key + 1);
        dd_html += ' <span class="ddd-item order-no" style="flex: 0 !important">' + order_no + '</span>';

        for (var i in app_list.column) {
          var col = app_list.column[i];
          if (col.type == 'image') {
            dd_html += ' <span class="ddd-item"><img src="upload/' + app_module + '/thumb_list/' + getObj(item, col.name) + '" width="62"  onerror="this.onerror=null;this.src=\'themes/admin/assets/images/no-image.png\';" class="img-preview" /></span>';
          } else if (col.type == 'file') {
            dd_html += ' <span class="ddd-item"><a href="upload/' + app_module + '/thumb_list/' + getObj(item, col.name) + '" target="_blank">Download</a></span>';
          } else {
            dd_html += ' <span class="ddd-item">' + formatValue(col.type, getObj(item, col.name)) + '</span>';
          }
        }
        dd_html += '</div>';
        search_parent(nestList, item_id);
        dd_html += '</li>';
      }
    });
    dd_html += ' </ol>';
  }

  var isDragStop = false;

  function updateOutput(e) {
    if (isDragStop) {
      var list = e.length ? e : $(e.target),
        output = list.data('output');
      if (window.JSON) {
        if (typeof list.nestable('serialize') == "object") {
          $.ajax({
            dataType: "html",
            type: "POST",
            url: url_update + "/cmdUpdateParent",
            data: "json=" + window.JSON.stringify(list.nestable('serialize')),
            beforeSend: function() {
              $.fancybox.showLoading();
            },
            success: function(res) {

              $.each(nestList, function(key, item) {
                var order_no = (key + 1);
                $(".dd-list-big .dd-item:eq(" + key + ") .order-no").text(order_no);
              });

              $.fancybox.hideLoading();
              isDragStop = false;
            }
          });
        }
      } else {
        alert('JSON browser support required for this demo.');
      }
    }
  }
</script>