<div ng-controller="breadcrumbCtrl">
  <ol class="breadcrumb">
    <li><a href="<?php echo site_url("authen"); ?>"><span class="fa fa-home"></span> Dashboard</a></li>
    <li ng-repeat="item in breadcrumb" class="{{item.active=='true' ? 'active' : ''}}">
      <span ng-if="item.active == 'true'">{{item.label}}</span>
      <a ng-if="item.active != 'true'" href="{{item.link.replace('{refid}',refid)}}">{{item.label}}</a>
    </li>
  </ol>
</div>