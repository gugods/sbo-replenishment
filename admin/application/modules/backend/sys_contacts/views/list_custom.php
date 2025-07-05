<script>
	function load_on_success(res) {
		$("#formList table tbody tr").each(function(index) {
			if (res[index].reader == "Unread") {
				$(this).find("td div").css({
					"font-weight": "bold",
					"color": "#2a3f54"
				});
			}
		});
	}
</script>