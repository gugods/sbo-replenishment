<style>
	.key-box {
		display: none;
	}

	.row-key {
		display: flex;
	}

	.parent-key {
		flex: 50%;
	}

	.child-key {
		display: flex;
		gap: 5px;
		padding-bottom: 10px;
	}

	.child-key label {
		font-weight: bold;
		width: 100px;
	}

	.title-key {
		margin-left: 0px !important;
	}

	.form-group:has(#product_section_ids),
	.form-group:has(#product_type_ids) {
		display: none;
	}
</style>
<script type="text/javascript">
	var CUSTOM_ID = '0';
	var ADMIN_ID = '1';
	var KEY_ID = '3';

	$(function() {

		if (app_mode.action == "add") {
			$("#username,#password").prop("disabled", true).css("background-color", "#FFF");
			setTimeout(function() {
				$("#username,#password").val("");
				$("#username,#password").prop("disabled", false);
			}, 1000);
		} else {
			$("#password").prop("disabled", true).css("background-color", "#FFF");
			setTimeout(function() {
				$("#password").val("");
				$("#password").prop("disabled", false);
			}, 1000);
			<?php if ($ROLES_ID != '1') { ?>
				$("#password,#username").parent().parent().hide();
			<?php } ?>
		}

		<?php if ($ROLES_ID == '1') { ?>
			$("#sys_roles_id").css({
				"margin-right": "5px",
				"display": "inline-block"
			}).after('<button type="button" class="btn btn-dark btn-edit-role" disabled>Custom role</button>');
			$("input[type=hidden][id=id][value=<?php echo $USER_ID; ?>]").parents("#myForm").find(".btn-delete-list,.btn-save").remove();
		<?php } ?>
		$("a[href='#tab-permission']").parent().hide();

		$("#store_ids").parents('.form-group').after('<div class="form-group key-box"><label for="key-product" class="col-sm-3 control-label"></label><div class="col-sm-9 key-product-box"></div></div>');

		check_role(app_post.sys_roles_id, true);
		$(document).delegate("#sys_roles_id", "change", function() {
			check_role($(this).val(), false);
		});

		$(document).delegate("#store_ids", "change", function() {
			get_key_product();
		});

		$("#tab-permission").hide();
		$("#tab-permission").append('<br /><div align="center"><button type="button" class="btn btn-dark btn-save-role" onclick="$.fancybox.close();">OK</button></div>')
		$(document).delegate(".btn-edit-role", "click", function() {
			$("#tab-permission").addClass("form-horizontal").css({
				"opacity": 1
			});
			$("#tab-permission h2:first,#tab-permission .form-group:first").hide();
			$.fancybox({
				'width': 700,
				'height': 400,
				'autoSize': false,
				'href': "#tab-permission",
				'padding': 20,
				'closeBtn': false
			});
		});

		$("label[for='config']").text("default config");
	});

	function check_role(sys_roles_id, isFirst) {
		$(".key-box").hide();
		if (sys_roles_id == CUSTOM_ID) {
			$(".btn-edit-role").attr("disabled", false);
			$("#assigned").prop("checked", true);
		} else {
			$(".btn-edit-role").attr("disabled", true);
			$("#assigned").prop("checked", false);
		}

		$(".key-product-box").html('');
		if (sys_roles_id == KEY_ID) {
			$("#store_ids").parent().parent().show();
			$(".key-product-box").show();
			if (!isFirst) {
				get_key_product();
			}
		} else {
			$("#store_ids").parent().parent().hide();
			$(".key-product-box").hide();
		}

	}

	function load_lookup_success() {
		//custom lookup success
		if (app_post.store_ids) {
			$.each(app_post.store_ids.split(","), function(index, value) {
				$("#store_ids option[value='" + value + "']").prop("selected", true);
			});
		}

		if (app_post.sys_roles_id == KEY_ID) {
			get_key_product();
		}

		$('#store_ids').select2({
			placeholder: "ห้างทั้งหมด...",
			width: '300',
			closeOnSelect: false,
			allowHtml: true,
			allowClear: true,
			tags: false
		});

	}

	function get_key_product() {
		$(".key-product-box").html('');

		var url = urlbase + "employees/branchs/getKeyProductAndStore";
		$.post(url, {
			sys_users_id: $("#id").val()
		}, function(res) {
			if (res.length > 0) {
				var row = 0;
				var html = '';
				html += '<div class="row-key">';
				var product_section_option = $("#product_section_ids").html();
				var product_type_option = $("#product_type_ids").html();
				var store_ids = [];
				var key_products = [];

				$("#store_ids option:selected").each(function() {
					store_ids.push($(this).val());
				});
				for (var v of res) {
					const stores_id = v['stores_id'];
					if (store_ids.includes(stores_id)) {
						html += '<div class="parent-key"><h2 class="title-key">' + v['store_name'] + '</h2>';
						html += '<div class="child-key"><label>กลุ่มสินค้า</label> <select class="form-control product_section_ids" name="product_section_ids[' + stores_id + '][]" multiple>' + product_section_option + '</select></div>';
						html += '<div class="child-key"><label>ประเภทสินค้า</label> <select class="form-control product_type_ids" name="product_type_ids[' + stores_id + '][]" multiple>' + product_type_option + '</select></div>';
						html += '</div>';
						row++;
						key_products.push(v);
					}
					if (row % 2 == 0) html += '</div><div class="row-key">';
				}
				html += '</div>'; // end row-key
				$(".key-product-box").html(html);
				$(".key-box").show();

				key_products.forEach(function(v) {
					const stores_id = v['stores_id'];
					$.each(v.product_section_ids.split(","), function(index, vv) {
						$("select[name^='product_section_ids[" + stores_id + "]'] option[value='" + vv + "']").prop("selected", true);
					});
					$.each(v.product_type_ids.split(","), function(index, vv) {
						$("select[name^='product_type_ids[" + stores_id + "]'] option[value='" + vv + "']").prop("selected", true);
					});
				});

				$('.product_section_ids').select2({
					placeholder: "กลุ่มสินค้าทั้งหมด...",
					width: '200',
					closeOnSelect: false,
					allowHtml: true,
					allowClear: true,
					tags: false
				});
				$('.product_type_ids').select2({
					placeholder: "ประเภทสินค้าทั้งหมด...",
					width: '200',
					closeOnSelect: false,
					allowHtml: true,
					allowClear: true,
					tags: false
				});
			}
			//load_ajax_success();
		}, 'json');
	}
</script>
<?php $this->load->view('sys_roles/form_custom'); ?>