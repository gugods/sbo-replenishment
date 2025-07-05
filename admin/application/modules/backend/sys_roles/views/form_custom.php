<script type="text/javascript">
	$(function() {
		$("label[for=sys_contacts]").parents('.form-group').before('<hr /><h2>ติดต่อ</h2>');
		$("label[for=employees]").parents('.form-group').before('<hr /><h2>ข้อมูลพนักงาน</h2>');
		$("label[for=products]").parents('.form-group').before('<hr /><h2>ข้อมูลสินค้า</h2>');
		$("label[for=pos_daily]").parents('.form-group').before('<hr /><h2>ข้อมูลการขาย</h2>');
		$("label[for=confirm_pos]").parents('.form-group').before('<hr /><h2>ยืนยันยอดเกิน</h2>');
		$("label[for=commission]").parents('.form-group').before('<hr /><h2>คอมมิชชั่น</h2>');
		$("label[for=rep_sale_checked]").parents('.form-group').before('<hr /><h2>รายงาน</h2>');
		$("label[for=notifications]").parents('.form-group').before('<hr /><h2>ประชาสัมพันธ์</h2>');
	});
</script>