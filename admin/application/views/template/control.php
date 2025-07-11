<div id="BoxPage" class="row">
	<div class="col-md-8">
		<ul ng-if="totalpage>1" class="pagination">
			<li><a href="javascript:;">&laquo;</a></li>
			<li class="active"><a href="javascript:;">1</a></li>
			<li><a href="javascript:;">&raquo;</a></li>
		</ul>
	</div>
	<div ng-if="control==true && sys_action!='delete'" class="col-md-4 control">
		<div class="btn-group">
			<?php if (file_exists(APPPATH . 'modules/backend/' . $module . '/views/import_custom.php')) { ?>
				<?php $this->load->view($module . '/import_custom.php'); ?>
			<?php } else { ?>
				<button ng-if="import_mode=='ON'" type="button" class="btn btn-xs btn-dark btn-import-all">
					<span class="glyphicon glyphicon-level-up"></span> Import
				</button>
			<?php } ?>
			<?php if (file_exists(APPPATH . 'modules/backend/' . $module . '/views/export_custom.php')) { ?>
				<?php $this->load->view($module . '/export_custom.php'); ?>
			<?php } else { ?>
				<button ng-if="export_mode=='ON'" type="button" class="btn btn-xs btn-dark btn-export-all">
					<span class="glyphicon glyphicon-download-alt"></span> Export
				</button>
			<?php } ?>
		</div>
		<div class="btn-group">
			<button ng-if="add_mode!='OFF'" type="button" class="btn btn-xs btn-dark btn-add" ref="{{module}}">
				<span class="glyphicon glyphicon-plus-sign"></span> Add
			</button>
			<button ng-if="delete_mode!='OFF'" type="button" class="btn btn-xs btn-dark btn-delete-all">
				<span class="glyphicon glyphicon-trash"></span> Delete
			</button>
			<button ng-if="publish_mode!='OFF'" type="button" class="btn btn-xs btn-dark btn-publish-all">
				<span class="glyphicon glyphicon-globe"></span> Publish
			</button>
		</div>
	</div>
	<div ng-if="control==true && sys_action=='delete'" class="col-md-4 control">
		<div class="btn-group">
			<button ng-if="delete_mode!='OFF'" type="button" class="btn btn-xs btn-dark btn-undelete-all">
				<span class="fa fa-refresh"></span> Undelete
			</button>
		</div>
	</div>
</div>