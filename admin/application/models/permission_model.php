<?php
class Permission_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function check_permission($module = "", $action = "")
    {
        $ROLES_ID = $this->session->userdata("ROLES_ID");
        $ASSIGNED = $this->session->userdata("ASSIGNED");
        $USER_ID = $this->session->userdata("USER_ID");
        if ($ASSIGNED == "Y") {
            $where = array("sys_users_id" => $USER_ID, "module" => $module, "action" => $action);
            $rows = $this->db->where($where)->count_all_results("sys_permission_u");
        } else {
            $where = array("sys_roles_id" => $ROLES_ID, "module" => $module, "action" => $action);
            $rows = $this->db->where($where)->count_all_results("sys_permission");
        }
        return ($rows == 0) ? false : true;
    }

    function show_permission($module = "")
    {
        $newdata = $this->get_permission($module);
        $result  = $this->btn_permission($newdata);
        $newdata = $this->get_menu_module();
        $result .= $this->btn_menu_module($newdata);
        return $result;
    }

    function get_permission($module = "")
    {
        $ROLES_ID = $this->session->userdata("ROLES_ID");
        $ASSIGNED = $this->session->userdata("ASSIGNED");
        $USER_ID = $this->session->userdata("USER_ID");
        if ($ASSIGNED == "Y") {
            $where = array("sys_users_id" => $USER_ID, "module" => $module);
            $result = $this->db->where($where)->get("sys_permission_u")->result_array();
        } else {
            $where = array("sys_roles_id" => $ROLES_ID, "module" => $module);
            $result = $this->db->where($where)->get("sys_permission")->result_array();
        }
        $newdata = array();
        $item = "";
        foreach ($result as $item):
            array_push($newdata, $item['action']);
        endforeach;
        return $newdata;
    }

    function btn_permission($newdata)
    {
        $btn = "";
        //$permiss = array("view","created","modified","deleted","publish");
        if (in_array("view", $newdata)) :
            $btn .= ".btn-display,.btn-display-child,";
        endif;
        if (in_array("created", $newdata)) :
            $btn .= ".btn-save-list,";
            $btn .= ".btn-add,.btn-add-child,";
        endif;
        if (in_array("modified", $newdata)) :
            $btn .= ".btn-edit,\n.btn-save,";
            $btn .= ".btn-edit-child,\n.btn-save-child,";
        endif;
        if (in_array("deleted", $newdata)) :
            $btn .= ".btn-delete,\n.btn-delete-all,\n.btn-delete-list,\n.btn-undelete,\n.btn-undelete-all,";
            $btn .= ".btn-delete-child,\n.btn-delete-all-child,\n.btn-delete-list-child,\n.btn-undelete-child,\n.btn-undelete-all-child,";
        endif;
        if (in_array("publish", $newdata)) :
            $btn .= ".btn-publish,\n.btn-unpublish,\n.btn-created,\n.btn-modified,";
            $btn .= ".btn-publish-child,\n.btn-unpublish-child,\n.btn-created-child,\n.btn-modified-child,";
            $btn .= ".btn-save-publish,\n.btn-publish-all,\n.btn-publish-list,";
            $btn .= ".btn-save-publish-child,\n.btn-publish-all-child,\n.btn-publish-list-child,";
        endif;
        if (in_array("import", $newdata)) :
            $btn .= ".btn-import-all,\n.btn-import-dropdown,\n.btn-import-file,\n.btn-import-example,\n.btn-import-example-dropdown,";
        endif;
        if (in_array("export", $newdata)) :
            $btn .= ".btn-export-all,";
        endif;
        if (in_array("confirmed", $newdata)) :
            $btn .= ".btn-confirmed,\n.btn-confirmed-all,";
        endif;
        return rtrim($btn, ',') . "{display:inline-block}\n";
    }

    function get_menu_module()
    {
        $ROLES_ID = $this->session->userdata("ROLES_ID");
        $ASSIGNED = $this->session->userdata("ASSIGNED");
        $USER_ID = $this->session->userdata("USER_ID");
        if ($ASSIGNED == "Y") {
            $where = array("sys_users_id" => $USER_ID, "action" => "view");
            $result = $this->db->select("module")
                ->where($where)
                ->group_by("module")
                ->get("sys_permission_u")
                ->result_array();
        } else {
            $where = array("sys_roles_id" => $ROLES_ID, "action" => "view");
            $result = $this->db->select("module")
                ->where($where)
                ->group_by("module")
                ->get("sys_permission")
                ->result_array();
        }
        $newdata = array();
        $item = "";
        foreach ($result as $item):
            array_push($newdata, $item['module']);
        endforeach;
        return $newdata;
    }

    function btn_menu_module($newdata)
    {
        $btn = "";
        $permiss = array("view", "created", "modified", "deleted", "publish");
        $item = "";
        foreach ($newdata as $item) :
            $btn .= ".menu_section .module." . $item . ",\n";
        endforeach;
        return rtrim($btn, ",\n") . "{display:block}\n";
    }

    function check_access($module = "", $action = "")
    {
        if (!$this->check_permission($module, $action)) :
            //show_404();
            redirect(site_url("webadmin"));
            exit();
        endif;
    }

    function permission_key_products($user_id = 0, $table = '')
    {
        $user = $this->db->get_where('sys_users', ['sys_users_id' => $user_id])->first_row('array');
        $key_products = $this->db->get_where('key_products', ['sys_users_id' => $user_id])->result_array();
        $store_ids =  (@$user['store_ids'] != "") ?  explode(',', $user['store_ids']) : [];

        $sql_key_products = '';
        if (count($key_products) > 0) {
            foreach ($key_products as $k => $v) {
                $sql_product_section = '';
                $sql_product_type = '';
                if ($v['product_section_ids'] != "") $sql_product_section = " AND products.product_section_id IN ({$v['product_section_ids']}) ";
                if ($v['product_type_ids'] != "") $sql_product_type = " AND products.product_type_id IN ({$v['product_type_ids']}) ";
                if ($k === 0) {
                    $sql_key_products .= "({$table}.stores_id = '{$v['stores_id']}' {$sql_product_section} {$sql_product_type})";
                } else {
                    $sql_key_products .= " OR ({$table}.stores_id = '{$v['stores_id']}' {$sql_product_section} {$sql_product_type})";
                }
                $store_key = array_search($v['stores_id'], $store_ids);
                if ($store_key !== false) {
                    array_splice($store_ids, $store_key, 1);
                }
            }
        }

        if ($sql_key_products != "") {
            if (count($store_ids) > 0) $sql_key_products .= " OR ({$table}.stores_id IN (" . implode(',', $store_ids) . ")) ";
            $this->db->where('(' . $sql_key_products . ')', null, false);
        } else {
            if (count($store_ids) > 0) $this->db->where_in("{$table}.stores_id", $store_ids);
        }
    }
}
