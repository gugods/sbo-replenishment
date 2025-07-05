<?php $this->load->view("template/form"); ?>
<script type="text/javascript">
	app_breadcrumb = [{
		"label": "Users",
		"link": "sys_users",
		"active": "false"
	}, {
		"label": "Setting",
		"link": "",
		"active": "true"
	}];
	app_mode = {
		"checkbox_mode": "ON",
		"publish_mode": "ON",
		"control_mode": "ON",
		"add_mode": "ON",
		"edit_mode": "ON",
		"delete_mode": "ON",
		"display_mode": "ON",
		"log_mode": "OFF",
		"action": "<?php echo @$action; ?>"
	};
	app_tabForm = [{
		id: "setting",
		label: "Setting",
		"form": [{
			"groups": "Edit profile",
			"id": "fullname",
			"label": "ชื่อ - นามสกุล",
			"name": "fullname",
			"type": "text",
			"maxlength": "255",
			"validate": "notEmpty",
			"class": ""
		}, {
			"groups": "Edit profile",
			"id": "email",
			"label": "Email",
			"name": "email",
			"type": "text",
			"maxlength": "255",
			"validate": "",
			"class": ""
		}, {

			"groups": "Edit profile",
			"id": "image",
			"label": "\u0e23\u0e39\u0e1b\u0e20\u0e32\u0e1e",
			"name": "image",
			"type": "image",
			"filetype": "jpg|png|gif",
			"alert": "Upload comment size 100 X 100 px"
		}, {
			"groups": "Change password",
			"id": "old_password",
			"label": "รหัสผ่านเดิม",
			"name": "old_password",
			"type": "password",
			"maxlength": "100",
			"class": ""
		}, {
			"groups": "Change password",
			"id": "password",
			"label": "รหัสผ่านใหม่",
			"name": "password",
			"type": "password",
			"maxlength": "100",
			"class": ""
		}, {
			"groups": "Change password",
			"id": "re_password",
			"label": "ยืนยันรหัสผ่าน",
			"name": "re_password",
			"type": "password",
			"maxlength": "100",
			"class": ""
		}]
	}];
	app_list = {
		"id": "<?php echo @$id; ?>",
		"title": "Users",
		"rows": "<?php echo @$rows; ?>",
		"totalpage": "<?php echo @$totalpage; ?>",
		"pagesize": <?php echo PAGESIZE; ?>,
		"module": "sys_users",
		"tbwidth": "100%",
		"column": [],
		"items": []
	};
	app_post = <?php echo json_encode($post); ?>;
	app_validate = {
		"fullname": {
			"validators": {
				"notEmpty": {
					"message": "The value is required and cannot be empty"
				}
			}
		},
		"password": {
			"validators": {
				"identical": {
					"field": "re_password",
					"message": "The password and its confirm are not the same"
				}
			}
		},
		"re_password": {
			"validators": {
				"identical": {
					"field": "password",
					"message": "The password are not the same"
				}
			}
		},
		"email": {
			"validators": {
				"emailAddress": {
					"message": "The input is not a valid email address"
				}
			}
		}
	};
</script>