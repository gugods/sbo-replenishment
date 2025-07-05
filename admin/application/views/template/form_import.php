<div id="showFormImport" style="display:none">
	<form id="formImport" action="" class="form-horizontal" method="post" enctype="multipart/form-data">
		<div class="form-group">
			<label class="col-sm-12">Choose File : <input type="file" name="file_import" id="file_import" accept=".xls,.xlsx,.csv" filetype="xls|xlsx|csv" style="padding: 6px 12px;display: inline-block;" /></label>
		</div>
		<hr />
		<div class="form-group">
			<div class="col-sm-offset-4 col-sm-8"><button type="button" class="btn btn-dark btn-import-file">Import File</button></div>
		</div>
	</form>
</div>

<div id="showWaitImport" style="display:none">
	<form class="form-horizontal">
		<div class="form-group">
			<label class="col-sm-12">
				<h3 style="text-align:center">ระบบกำลังทำงานอาจใช้เวลานานกรุณารอสักครู่...</h3>
				<h3 style="text-align:center">ห้ามปิดหน้าต่างนี้ระบบจะหยุดทำงานทันที !!</h3>
			</label>
		</div>
	</form>
</div>