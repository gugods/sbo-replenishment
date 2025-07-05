<?php
$this->load->view("template/list");
?>
<script type="text/javascript">
	app_breadcrumb = [{
		"label": "Junk Mail",
		"link": "",
		"active": "true"
	}];
	app_mode = {
		"checkbox_mode": "ON",
		"publish_mode": "ON",
		"control_mode": "ON",
		"add_mode": "OFF",
		"edit_mode": "OFF",
		"delete_mode": "ON",
		"display_mode": "ON",
		"log_mode": "OFF"
	};
	app_searchForm = [{
		"id": "reader",
		"label": "Read",
		"name": "reader",
		"type": "select",
		"data": [{
			"id": "",
			"name": "",
			"value": "Read",
			"label": "Read"
		}, {
			"id": "",
			"name": "",
			"value": "Unread",
			"label": "Unread"
		}],
		"value": ""
	}];
	app_statusForm = [];
	app_list = {
		"refid": "<?php echo isset($post['refid']) ? $post['refid'] : 0; ?>",
		"ref_name": "",
		"ref_back": "",
		"title": "Junk Mail",
		"rows": <?php echo $rows; ?>,
		"rows_publish": <?php echo $rows_publish; ?>,
		"rows_modified": <?php echo $rows_modified; ?>,
		"rows_unpublish": <?php echo $rows_unpublish; ?>,
		"totalpage": <?php echo $totalpage; ?>,
		"thispage": "<?php echo $thispage; ?>",
		"pagesize": "<?php echo $pagesize; ?>",
		"sys_action": "<?php echo $sys_action; ?>",
		"sorting": "<?php echo $sorting; ?>",
		"orderby": "<?php echo $orderby; ?>",
		"module": "sys_junk",
		"tbwidth": "100%",
		"column": [{
			"label": "Username",
			"name": "email",
			"type": "text",
			"sort": "",
			"width": "100",
			"class": "left"
		}, {
			"label": "Name",
			"name": "fullname",
			"type": "text",
			"sort": "",
			"width": "180",
			"class": "left"
		}, {
			"label": "Subject",
			"name": "subject",
			"type": "text",
			"sort": "",
			"width": "250",
			"class": "left"
		}, {
			"label": "Send Date",
			"name": "senddate",
			"type": "date",
			"sort": "",
			"width": "100",
			"class": "center"
		}],
		"items": []
	};
	app_post = <?php echo json_encode($post); ?>;
	app_validate = {};
</script>
<?php $this->load->view("sys_junk/list_custom"); ?>