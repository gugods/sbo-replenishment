<div class="left_col scroll-view">
  <div class="navbar nav_title" style="border: 0;background: #89274d;">
    <a href="<?php echo site_url("authen"); ?>" class="site_title">
      <i class="fa fa-recycle"></i> <span style="font-size:18px">Replenishment</span>
    </a>
  </div>

  <div class="clearfix"></div>

  <!-- sidebar menu -->
  <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
      <ul class="nav side-menu">
        <li class="authen"><a href="<?php echo site_url("authen"); ?>"><i class="fa fa-home"></i> Home</a></li>
        <li class="parent_menu"><a><i class="fa fa-envelope-o"></i> ติดต่อ <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
            <li class="module sys_contacts"><a href="<?php echo site_url("sys_contacts"); ?>"><i class="fa fa-archive"></i> Inbox <?php echo (isset($INBOXS) && $INBOXS > 0) ? '<span class="pull-right badge">' . $INBOXS . '</span>' : null; ?></a></li>
            <li class="module sys_junk"><a href="<?php echo site_url("sys_junk"); ?>"><i class="fa fa-trash-o"></i> Junk Mail <?php echo (isset($JUNKS) && $JUNKS > 0) ? '<span class="pull-right badge">' . $JUNKS . '</span>' : null; ?></a></li>
          </ul>
        </li>
        <li class="parent_menu"><a><i class="fa fa-user"></i> Administrator <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
            <li class="module sys_users"><a href="<?php echo site_url("sys_users"); ?>">Users</a></li>
            <li class="module sys_roles"><a href="<?php echo site_url("sys_roles"); ?>">Roles</a></li>
            <li class="module sys_logs"><a href="<?php echo site_url("sys_logs"); ?>">Logs</a></li>
            <li class="module sys_users_block"><a href="<?php echo site_url("sys_users_block"); ?>">Users Block</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
  <!-- /sidebar menu -->

</div>