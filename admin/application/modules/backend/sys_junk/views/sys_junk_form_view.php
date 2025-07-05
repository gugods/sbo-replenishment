<?php
$this->load->view("template/form");
?>
<script type="text/javascript">
	app_breadcrumb = [{
		"label": "Junk Mail",
		"link": "sys_junk",
		"active": "false"
	}, {
		"label": "Message",
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
		"log_mode": "OFF",
		"action": "<?php echo @$action; ?>"
	};
	app_tabForm = [{
		"id": "message",
		"label": "Message",
		"form": [{
			"groups": "Contacts Info",
			"id": "fullname",
			"label": "Name",
			"name": "fullname",
			"type": "text"
		}, {
			"groups": "Contacts Info",
			"id": "email",
			"label": "Username",
			"name": "email",
			"type": "text"
		}, {
			"groups": "Contacts Info",
			"id": "subject",
			"label": "Subject",
			"name": "subject",
			"type": "text"
		}, {
			"groups": "Contacts Info",
			"id": "message",
			"label": "Message",
			"name": "message",
			"type": "textarea",
			"rows": "5"
		}, {
			"groups": "Contacts Info",
			"id": "senddate",
			"label": "Send Date",
			"name": "senddate",
			"type": "date"
		}]
	}];
	app_list = {
		"id": "<?php echo @$id; ?>",
		"refid": "<?php echo isset($post['refid']) ? $post['refid'] : 0; ?>",
		"ref_name": "",
		"ref_back": "",
		"title": "Junk Mail",
		"rows": "<?php echo @$rows; ?>",
		"totalpage": "<?php echo @$totalpage; ?>",
		"pagesize": <?php echo PAGESIZE; ?>,
		"module": "sys_junk",
		"tbwidth": "100%",
		"column": [],
		"items": []
	};
	app_post = <?php echo json_encode($post); ?>;
	app_validate = {
		reader: {
			validators: {
				notEmpty: {
					message: 'The value is required and cannot be empty'
				}
			}
		}

	};
</script>
<?php  ?>