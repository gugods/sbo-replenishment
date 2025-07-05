<?php
$this->load->view("template/list");
$this->load->view("sys_logs/list_custom");
?><script type="text/javascript">
	app_breadcrumb = [{
		"label": "Logs",
		"link": "",
		"active": "true"
	}];
	app_mode = {
		"checkbox_mode": "OFF",
		"publish_mode": "OFF",
		"control_mode": "OFF",
		"add_mode": "OFF",
		"edit_mode": "OFF",
		"delete_mode": "OFF",
		"display_mode": "OFF",
		"log_mode": "OFF"
	};
	app_searchForm = [{
		"id": "module",
		"label": "Module",
		"name": "module",
		"type": "lookup",
		"width": "250px",
		"query": "SELECT  REPLACE(module,'sys_','') AS Label,module AS Value  FROM sys_module WHERE module NOT LIKE 'rep_%' order by priority  "
	}, {
		"id": "action",
		"label": "Action",
		"name": "action",
		"type": "select",
		"data": [{
			"value": "login",
			"label": "login"
		}, {
			"value": "logout",
			"label": "logout"
		}, {
			"value": "created",
			"label": "created"
		}, {
			"value": "modified",
			"label": "modified"
		}, {
			"value": "publish",
			"label": "publish"
		}, {
			"value": "unpublish",
			"label": "unpublish"
		}, {
			"value": "delete",
			"label": "delete"
		}, {
			"value": "undelete",
			"label": "undelete"
		}, {
			"value": "import",
			"label": "import"
		}]
	}, {
		"id": "lastupdate",
		"label": "Action Date",
		"name": "lastupdate",
		"type": "rangedate",
		"class": "rangedate"
	}];
	app_list = {
		"title": "Logs",
		"rows": <?php echo $rows; ?>,
		"rows_publish": <?php echo $rows_publish; ?>,
		"rows_modified": <?php echo $rows_modified; ?>,
		"rows_unpublish": <?php echo $rows_unpublish; ?>,
		"totalpage": <?php echo $totalpage; ?>,
		"pagesize": <?php echo $pagesize; ?>,
		"sorting": 'sys_logs_id',
		"orderby": 'desc',
		"module": "sys_logs",
		"tbwidth": "100%",
		"column": [{
			"label": "Module",
			"name": "module",
			"type": "text",
			"sort": "T",
			"width": "100",
			"class": "left"
		}, {
			"label": "Action",
			"name": "action",
			"type": "text",
			"sort": "T",
			"width": "120",
			"class": "left"
		}, {
			"label": "Message",
			"name": "message",
			"type": "text",
			"sort": "F",
			"width": "350",
			"class": "left"
		}, {
			"label": "Action Date",
			"name": "lastupdate",
			"type": "datetime",
			"sort": "T",
			"width": "150",
			"class": "left"
		}, {
			"label": "By",
			"name": "update_name",
			"type": "text",
			"sort": "T",
			"width": "100",
			"class": "left"
		}],
		"items": []
	};
	app_post = <?php echo json_encode($post); ?>;
	app_validate = {};
</script>