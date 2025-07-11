    <?php $isAppCode = false; ?>
    <base href="<?php echo base_url(); ?>" />
    <script type="text/javascript">
        var urlbase = "<?php echo base_url(); ?>";
        var urlpath = "<?php echo site_url(); ?>";
    </script>
    <link rel="stylesheet" type="text/css" href="themes/admin/vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="themes/admin/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="themes/admin/vendors/nprogress/nprogress.css">
    <link rel="stylesheet" type="text/css" href="themes/admin/vendors/fancybox/jquery.fancybox.css">
    <link rel="stylesheet" type="text/css" href="themes/admin/vendors/datetimepicker/datetimepicker.css" />
    <link rel="stylesheet" type="text/css" href="themes/admin/vendors/bootstrap-validator/bootstrapValidator.min.css" />
    <link rel="stylesheet" type="text/css" href="themes/admin/assets/css/img_preview.css?v=<?php echo getVersion($isAppCode); ?>">
    <link rel="stylesheet" type="text/css" href="themes/admin/assets/css/custom.min.css?v=<?php echo getVersion($isAppCode); ?>">
    <link rel="stylesheet" type="text/css" href="themes/admin/assets/css/webadmin.css?v=<?php echo getVersion($isAppCode); ?>">
    <link rel="stylesheet" type="text/css" href="themes/admin/vendors/select2/dist/css/select2.min.css">
    <script type="text/javascript" src="themes/admin/vendors/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="themes/admin/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="themes/admin/vendors/datetimepicker/datetimepicker.js"></script>
    <script type="text/javascript" src="themes/admin/vendors/bootstrap-validator/bootstrapValidator.min.js"></script>
    <script type="text/javascript" src="themes/admin/vendors/jquery.form.js"></script>
    <script type="text/javascript" src="themes/admin/vendors/nprogress/nprogress.js"></script>
    <script type="text/javascript" src="themes/admin/vendors/angular/angular.min.js"></script>
    <script type="text/javascript" src="themes/admin/vendors/fancybox/jquery.fancybox.js"></script>
    <script type="text/javascript" src="themes/admin/vendors/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="themes/admin/vendors/jquery.nestable.js"></script>
    <script type="text/javascript" src="themes/admin/assets/js/custom.min.js"></script>
    <script type="text/javascript" src="themes/admin/assets/js/jquery.md5.js"></script>
    <script type="text/javascript" src="themes/admin/assets/js/jquery.base64.js"></script>
    <script type="text/javascript" src="themes/admin/vendors/select2/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="themes/admin/assets/js/create.form.js?v=<?php echo getVersion($isAppCode); ?>"></script>
    <script type="text/javascript" src="themes/admin/assets/js/child.js?v=<?php echo getVersion($isAppCode); ?>"></script>
    <script type="text/javascript" src="themes/admin/assets/js/dialog.js?v=<?php echo getVersion($isAppCode); ?>"></script>
    <script type="text/javascript" src="themes/admin/assets/js/pagination.js?v=<?php echo getVersion($isAppCode); ?>"></script>
    <script type="text/javascript" src="themes/admin/assets/js/img_preview.js?v=<?php echo getVersion($isAppCode); ?>"></script>
    <script type="text/javascript" src="themes/admin/assets/js/lang_value.js?v=<?php echo getVersion($isAppCode); ?>"></script>
    <script type="text/javascript" src="themes/admin/assets/js/webadmin.js?v=<?php echo getVersion($isAppCode); ?>"></script>
    <style rel="stylesheet" type="text/css" type="text/css">
        <?php echo $this->permission_model->show_permission($module); ?>
    </style>