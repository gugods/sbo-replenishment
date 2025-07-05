<script>
	$(function() {
		$('#module,#action').select2({
			width: '200'
		});
	});

	function load_on_success(res) {
		var indexStart = 0;
		$("#formList table tbody tr").each(function(index) {
			var item = res[index];
			var indexColAction = app_list.column.findIndex(col => col.name === "action");
			var record_id = item.record_id ? ' => ' + item.record_id + '' : '';
			var html = item.action + ' ' + record_id;
			$(this).find("td:eq(" + (indexColAction + indexStart) + ") div").html(html);
		});
	}
</script>