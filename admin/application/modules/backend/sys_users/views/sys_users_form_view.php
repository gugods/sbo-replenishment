<?php
$jsonPermission = '{"id":"permission","label":"Assigned permission","form":[';

if ($ROLES_ID == '1') {
	$jsonPermission .= '{"groups":"Users assigned","id":"assigned","label":"Assigned","name":"assigned","type":"checkbox","class":"","data": [{"id":"assigned","name":"assigned","value":"Y","label":"with out roles"}] },';
}

foreach ($_modules_ as $k_mod => $v_mod) :
	$data = "[";
	if ($v_mod["view"] == '1') $data .= '{"id":"' . $v_mod["module"] . '_view","label":"View","name":"' . $v_mod["module"] . '_view","value":"1"},';
	if ($v_mod["created"] == '1') $data .= '{"id":"' . $v_mod["module"] . '_created","label":"Created","name":"' . $v_mod["module"] . '_created","value":"1"},';
	if ($v_mod["modified"] == '1') $data .= '{"id":"' . $v_mod["module"] . '_modified","label":"Modified","name":"' . $v_mod["module"] . '_modified","value":"1"},';
	if ($v_mod["publish"] == '1') $data .= '{"id":"' . $v_mod["module"] . '_publish","label":"Publish","name":"' . $v_mod["module"] . '_publish","value":"1"},';
	if ($v_mod["deleted"] == '1') $data .= '{"id":"' . $v_mod["module"] . '_deleted","label":"Delete","name":"' . $v_mod["module"] . '_deleted","value":"1"},';
	if ($v_mod["import"] == '1') $data .= '{"id":"' . $v_mod["module"] . '_import","label":"Import","name":"' . $v_mod["module"] . '_import","value":"1"},';
	if ($v_mod["export"] == '1') $data .= '{"id":"' . $v_mod["module"] . '_export","label":"Export","name":"' . $v_mod["module"] . '_export","value":"1"},';
	$data = rtrim($data, ",") . "]";
	$jsonPermission .= '{"groups":"Permission", "id":"' . $v_mod["module"] . '","label":"' . t($v_mod["module"]) . '","name":"' . $v_mod["module"] . '","type":"checkbox","data":' . $data . '},';
endforeach;

$where_role = "WHERE sys_roles_id<>'1' && sys_roles_id<>'0'";
if ($ROLES_ID == '1') {
	$jsonPermission = "," . rtrim($jsonPermission, ",") . "]}";
	$where_role = "";
} else {
	$jsonPermission = "";
}
$this->load->view("template/form");
$this->load->view("sys_users/form_custom");
?>
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
		"action": '<?php echo @$action; ?>'
	};
	app_tabForm = [{
			"id": "setting",
			"label": "Setting",
			"form": [{
				"groups": "Users data",
				"id": "fullname",
				"label": "ชื่อ - นามสกุล",
				"name": "fullname",
				"type": "text",
				"maxlength": "255",
				"validate": "notEmpty",
				"class": ""
			}, {
				"groups": "Users data",
				"id": "image",
				"label": "\u0e23\u0e39\u0e1b\u0e20\u0e32\u0e1e",
				"name": "image",
				"type": "image",
				"filetype": "jpg|png|gif",
				"alert": "Upload comment size 100 X 100 px"
			}, {
				"groups": "Users Login",
				"id": "username",
				"label": "Username",
				"name": "username",
				"type": "text",
				"maxlength": "100",
				"validate": "notEmpty",
				"class": ""
			}, {
				"groups": "Users Login",
				"id": "password",
				"label": "Password",
				"name": "password",
				"type": "password",
				"maxlength": "100",
				"class": ""
			}, {
				"groups": "Users Login",
				"id": "email",
				"label": "Email",
				"name": "email",
				"type": "text",
				"maxlength": "255",
				"validate": "",
				"class": ""
			}, {
				"groups": "Users Login",
				"id": "store_ids",
				"label": "ห้างที่ดูแล",
				"name": "store_ids",
				"type": "lookup",
				"multiple": "multiple",
				"query": "SELECT  store_name AS Label,stores_id AS Value  FROM stores  WHERE stores.sys_status='active' AND stores.sys_action='publish'   ORDER BY stores.store_name asc"
			}, {
				"groups": "Users Login",
				"id": "product_section_ids",
				"label": "กลุ่มสินค้า",
				"name": "product_section_ids",
				"type": "lookup",
				"multiple": "multiple",
				"query": "SELECT  product_section.product_section_name AS Label,product_section_id AS Value  FROM product_section  WHERE product_section.sys_status='active' AND product_section.sys_action='publish'   ORDER BY product_section.product_section_name asc"
			}, {
				"groups": "Users Login",
				"id": "product_type_ids",
				"label": "ประเภทสินค้า",
				"name": "product_type_ids",
				"type": "lookup",
				"multiple": "multiple",
				"query": "SELECT  product_type.product_type_name AS Label,product_type_id AS Value  FROM product_type  WHERE product_type.sys_status='active' AND product_type.sys_action='publish'   ORDER BY product_type.product_type_name asc"
			}, {
				"groups": "Users Login",
				"id": "sys_roles_id",
				"label": "Role",
				"name": "sys_roles_id",
				"type": "lookup",
				"query": "SELECT roles_name AS Label,sys_roles_id AS Value FROM sys_roles <?php echo $where_role; ?> ORDER BY sys_roles_id ASC",
				"validate": "notEmpty",
				"class": ""
			}]
		}
		<?php echo $jsonPermission; ?>
	];
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
		"username": {
			"validators": {
				"notEmpty": {
					"message": "The value is required and cannot be empty"
				}
			}
		},
		<?php if (@$action == "add") { ?> "password": {
				"validators": {
					"notEmpty": {
						"message": "The value is required and cannot be empty"
					}
				}
			},
		<?php } ?> "email": {
			"validators": {
				"emailAddress": {
					"message": "The input is not a valid email address"
				}
			}
		},
		"sys_roles_id": {
			"validators": {
				"notEmpty": {
					"message": "The value is required and cannot be empty"
				}
			}
		}
	};
</script>