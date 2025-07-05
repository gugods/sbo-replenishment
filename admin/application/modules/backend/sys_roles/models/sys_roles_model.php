<?php
class Sys_roles_model extends CI_Model
{
	var $USER_ID = '';
	var $FIELDS = 'sys_roles_id,roles_name,roles_detail,sys_status,sys_action,createdate,createby,lastupdate,updateby';

	function __construct()
	{
		parent::__construct();
		$this->load->model("authen_model");
		$this->USER_ID = $this->session->userdata("USER_ID");
		$this->ROLES_ID = $this->session->userdata("ROLES_ID");
	}

	function get_rows($post = array())
	{
		$this->db->where("sys_roles.sys_status", "active");
		if (@$post["sys_action"] != "") {
			if ($post['sys_action'] == "unpublish") {
				$this->db->where("(sys_roles.sys_action = 'created' OR sys_roles.sys_action = 'unpublish')", null, false);
			} else {
				$this->db->where("sys_roles.sys_action", $post['sys_action']);
			}
		}

		if (@$post["keyword"] != "") $this->db->where("(sys_roles.roles_name LIKE '%" . $post["keyword"] . "%')");
		if ($this->ROLES_ID != '1') $this->db->where('sys_roles.sys_roles_id <>', 1);

		$this->db->where('sys_roles.sys_roles_id <>', '0');
		$rows = $this->db->count_all_results("sys_roles");
		return $rows;
	}

	function get_rows_publish($post = array())
	{
		$post['sys_action'] = "publish";
		return $this->get_rows($post);
	}

	function get_rows_modified($post = array())
	{
		$post['sys_action'] = "modified";
		return $this->get_rows($post);
	}

	function get_rows_unpublish($post = array())
	{
		$post['sys_action'] = "unpublish";
		return $this->get_rows($post);
	}

	function get_page($option = array(), $start, $end)
	{
		$post = $option["post"];
		$this->db->where("sys_roles.sys_status", "active");
		if (@$post["sys_action"] != "") {
			if ($post['sys_action'] == "unpublish") {
				$this->db->where("(sys_roles.sys_action = 'created' OR sys_roles.sys_action = 'unpublish')", null, false);
			} else {
				$this->db->where("sys_roles.sys_action", $post['sys_action']);
			}
		}

		if (@$post["keyword"] != "") $this->db->where("(sys_roles.roles_name LIKE '%" . $post["keyword"] . "%')");
		if ($this->ROLES_ID != '1') $this->db->where('sys_roles.sys_roles_id <>', 1);

		$sorting = "";
		if (@$option["sorting"] == "update_name") {
			$sorting = "updated.username";
		} else if (@$option["sorting"] == "create_name") {
			$sorting = "created.username";
		} else if (strpos(@$option["sorting"], ".") !== false) {
			$sorting = $option["sorting"];
		} else {
			$sorting = "sys_roles." . $option["sorting"];
		}

		$this->db->where('sys_roles.sys_roles_id <>', '0');
		$sql =  $this->db->select("sys_roles.*")
			->select("created.username AS create_name,updated.username AS update_name")
			->join("sys_users created", "created.sys_users_id = sys_roles.createby", "LEFT")
			->join("sys_users updated", "updated.sys_users_id = sys_roles.updateby", "LEFT")
			->order_by($sorting, @$option["orderby"])
			->from("sys_roles")
			->query_string();
		$newsql = get_page($sql, $this->db->dbdriver, $start, $end);
		return  $this->db->query($newsql)->result_array();
	}

	function count_log($id = 0, &$rows, &$totalpage)
	{
		return 0;
	}

	function get_log($option = array(), $start, $end)
	{
		return array();
	}

	function get_id($id = 0)
	{
		$where = array("sys_roles_id" => $id, "sys_status" => "active");
		return $this->db->get_where("sys_roles", $where)->first_row("array");
	}

	function insert($post = array())
	{
		$save = array();

		if (isset($post["roles_name"])) $save["roles_name"] = $post["roles_name"];
		if (isset($post["roles_detail"])) $save["roles_detail"] = $post["roles_detail"];


		$save["createdate"] = date("Y-m-d H:i:s");
		$save["lastupdate"] = date("Y-m-d H:i:s");
		$save["createby"] = $this->USER_ID;
		$save["updateby"] = $this->USER_ID;
		$save["sys_action"] = "created";
		$save["sys_status"] = "active";

		$this->db->insert("sys_roles", $save);
		$val = $this->db->insert_id();


		$this->update_child($val);
		if ($this->ROLES_ID == '1') $this->authen_model->update_permission($val, $post);

		return $val;
	}


	function update($post = array())
	{
		$val = @$post["id"];
		$where_update = array("sys_roles_id" => $val, "sys_status" => "active");


		$save = array();
		if (isset($post["roles_name"])) $save["roles_name"] = $post["roles_name"];
		if (isset($post["roles_detail"])) $save["roles_detail"] = $post["roles_detail"];


		$save["lastupdate"] = date("Y-m-d H:i:s");
		$save["updateby"] = $this->USER_ID;
		$save["sys_action"] = "modified";
		$save["sys_status"] = "active";
		$this->db->update("sys_roles", $save, $where_update);

		if ($this->ROLES_ID == '1') $this->authen_model->update_permission($val, $post);
	}

	function savePublish($val = 0, $cmd = "")
	{
		$where_update = array("sys_roles_id" => $val, "sys_status" => "active");
		$update = array("sys_status" => "active", "sys_action" => "publish", "lastupdate" => date("Y-m-d H:i:s"), "updateby" => $this->USER_ID);
		$this->db->update("sys_roles", $update, $where_update);
	}

	function delete($val)
	{
		$where_update = array("sys_roles_id" => $val, "sys_status" => "active");
		$update = array("sys_status" => "archived", "sys_action" => "delete", "lastupdate" => date("Y-m-d H:i:s"), "updateby" => $this->USER_ID);
		$this->db->update("sys_roles", $update, $where_update);
	}

	function undelete($val)
	{
		$where_update = array("sys_roles_id" => $val, "sys_status" => "archived");
		$update = array("sys_status" => "active", "sys_action" => "publish", "lastupdate" => date("Y-m-d H:i:s"), "updateby" => $this->USER_ID);
		$this->db->update("sys_roles", $update, $where_update);
	}

	function delete_file($post = array())
	{
		$val = $post["id"];
		$where = array("sys_roles_id" => $val, "sys_status" => "active");
		$update = array($post['filename'] => "");
		$this->db->update("sys_roles", $update, $where);
	}

	function save_public($val)
	{
		$where_update = array("sys_roles_id" => $val, "sys_status" => "active");
		$update = array("sys_status" => "active", "sys_action" => "publish", "lastupdate" => date("Y-m-d H:i:s"), "updateby" => $this->USER_ID);
		$this->db->update("sys_roles", $update, $where_update);
	}

	function save_unpublic($val)
	{
		$where_update = array("sys_roles_id" => $val, "sys_status" => "active");
		$update = array("sys_status" => "active", "sys_action" => "unpublish", "lastupdate" => date("Y-m-d H:i:s"), "updateby" => $this->USER_ID);
		$this->db->update("sys_roles", $update, $where_update);
	}

	function update_child($sys_parent_id = 0)
	{
	}
}
