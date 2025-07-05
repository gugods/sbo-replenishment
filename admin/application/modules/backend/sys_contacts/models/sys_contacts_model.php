<?php
class Sys_contacts_model extends CI_Model
{
	var $USER_ID = '';
	var $FIELDS = "sys_contacts_id,fullname,email,subject,message,senddate,reader,sys_status,sys_action,createdate,createby,lastupdate,updateby";

	function __construct()
	{
		parent::__construct();
		$this->USER_ID = $this->session->userdata("USER_ID");
	}

	function get_rows($post = array())
	{
		$this->db->where("sys_contacts.sys_status", "active");
		if (@$post["sys_action"] != "") {
			if ($post['sys_action'] == "unpublish") {
				$this->db->where("(sys_contacts.sys_action = 'created' OR sys_contacts.sys_action = 'unpublish')", null, false);
			} else {
				$this->db->where("sys_contacts.sys_action", $post['sys_action']);
			}
		}

		if (@$post["keyword"] != "") $this->db->where("( sys_contacts.email LIKE '%" . $post["keyword"] . "%' OR sys_contacts.subject LIKE '%" . $post["keyword"] . "%' )");

		if (@$post["reader"] != "") $this->db->where('sys_contacts.reader', $post["reader"]);
		if (@$post["senddate_to_start"] != "") $this->db->where('DATE(sys_contacts.senddate) >= ', setDateformat($post["senddate_to_start"]));
		if (@$post["senddate_to_end"] != "") $this->db->where('DATE(sys_contacts.senddate) <= ', setDateformat($post["senddate_to_end"]));

		$rows = $this->db
			->count_all_results("sys_contacts");

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

		$this->db->where("sys_contacts.sys_status", "active");
		if (@$post["sys_action"] != "") {
			if ($post['sys_action'] == "unpublish") {
				$this->db->where("(sys_contacts.sys_action = 'created' OR sys_contacts.sys_action = 'unpublish')", null, false);
			} else {
				$this->db->where("sys_contacts.sys_action", $post['sys_action']);
			}
		}

		if (@$post["keyword"] != "") $this->db->where("( sys_contacts.email LIKE '%" . $post["keyword"] . "%' OR sys_contacts.subject LIKE '%" . $post["keyword"] . "%' )");

		if (@$post["reader"] != "") $this->db->where('sys_contacts.reader', $post["reader"]);
		if (@$post["senddate_to_start"] != "") $this->db->where('DATE(sys_contacts.senddate) >= ', setDateformat($post["senddate_to_start"]));
		if (@$post["senddate_to_end"] != "") $this->db->where('DATE(sys_contacts.senddate) <= ', setDateformat($post["senddate_to_end"]));


		$sorting = "";
		if (@$option["sorting"] == "update_name") {
			$sorting = "updated.username";
		} else if (@$option["sorting"] == "create_name") {
			$sorting = "created.username";
		} else if (strpos(@$option["sorting"], ".") !== false) {
			$sorting = $option["sorting"];
		} else {
			$sorting = "sys_contacts." . $option["sorting"];
		}

		$sql =  $this->db->select("sys_contacts.*")
			->select("created.username AS create_name,updated.username AS update_name")
			->join("sys_users created", "created.sys_users_id = sys_contacts.createby", "LEFT")
			->join("sys_users updated", "updated.sys_users_id = sys_contacts.updateby", "LEFT")
			->order_by($sorting, @$option["orderby"])
			->from("sys_contacts")
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
		$where = array("sys_contacts_id" => $id, "sys_status" => "active");
		return $this->db->get_where("sys_contacts", $where)->first_row("array");
	}

	function insert($post = array())
	{
		$save = array();

		if (isset($post["fullname"])) $save["fullname"] = $post["fullname"];
		if (isset($post["email"])) $save["email"] = $post["email"];
		if (isset($post["subject"])) $save["subject"] = $post["subject"];
		if (isset($post["message"])) $save["message"] = $post["message"];
		if (isset($post["senddate"])) $save["senddate"] = setDateformat($post["senddate"]);


		$save["createdate"] = date("Y-m-d H:i:s");
		$save["lastupdate"] = date("Y-m-d H:i:s");
		$save["createby"] = $this->USER_ID;
		$save["updateby"] = $this->USER_ID;
		$save["sys_action"] = "created";
		$save["sys_status"] = "active";

		$this->db->insert("sys_contacts", $save);
		$val = $this->db->insert_id('sys_contacts');

		$this->update_child($val);

		return $val;
	}


	function update($post = array())
	{
		$val = @$post["id"];
		$where_update = array("sys_contacts_id" => $val, "sys_status" => "active");

		$save = array();

		if (isset($post["fullname"])) $save["fullname"] = $post["fullname"];
		if (isset($post["email"])) $save["email"] = $post["email"];
		if (isset($post["subject"])) $save["subject"] = $post["subject"];
		if (isset($post["message"])) $save["message"] = $post["message"];
		if (isset($post["senddate"])) $save["senddate"] = setDateformat($post["senddate"]);


		$save["lastupdate"] = date("Y-m-d H:i:s");
		$save["updateby"] = $this->USER_ID;
		$save["sys_action"] = "publish";
		$save["sys_status"] = "active";
		$this->db->update("sys_contacts", $save, $where_update);
	}

	function savePublish($val = 0, $cmd = "")
	{
		$where_update = array("sys_contacts_id" => $val, "sys_status" => "active");
		$update = array("sys_status" => "active", "sys_action" => "publish", "lastupdate" => date("Y-m-d H:i:s"), "updateby" => $this->USER_ID);
		$this->db->update("sys_contacts", $update, $where_update);
	}

	function delete($val)
	{
		$this->db->query("INSERT INTO sys_contacts_log (" . $this->FIELDS . ") SELECT " . $this->FIELDS . " FROM sys_contacts WHERE sys_contacts_id = '{$val}' AND sys_status = 'active'");
		$where_update = array("sys_contacts_id" => $val, "sys_status" => "active");
		$this->db->delete("sys_contacts", $where_update);

		$max = $this->db->select_max('log_id', '"log_id"')->get_where("sys_contacts_log", $where_update)->first_row('array');
		$where_update = array("log_id" => $max["log_id"]);
		$update = array("sys_status" => "archived", "sys_action" => "delete", "lastupdate" => date("Y-m-d H:i:s"), "updateby" => $this->USER_ID);
		$this->db->update("sys_contacts_log", $update, $where_update);
	}

	function delete_file($post = array())
	{
		$val = $post["id"];
		$where = array("sys_contacts_id" => $val, "sys_status" => "active");
		$update = array($post['filename'] => "");
		$this->db->update("sys_contacts", $update, $where);
	}

	function save_public($val)
	{
		$where_update = array("sys_contacts_id" => $val, "sys_status" => "active");
		$update = array("sys_status" => "active", "sys_action" => "publish", "lastupdate" => date("Y-m-d H:i:s"), "updateby" => $this->USER_ID);
		$this->db->update("sys_contacts", $update, $where_update);
	}

	function save_unpublic($val)
	{
		$where_update = array("sys_contacts_id" => $val, "sys_status" => "active");
		$update = array("sys_status" => "active", "sys_action" => "unpublish", "lastupdate" => date("Y-m-d H:i:s"), "updateby" => $this->USER_ID);
		$this->db->update("sys_contacts", $update, $where_update);
	}

	function update_child($sys_parent_id = 0)
	{
	}

	function delete_child()
	{
	}

	function update_parent($json_array = array(), $parent_id = 0)
	{
		$parent_order = 1;
		foreach ($json_array as $k_json => $v_json) {

			$where_update = array("sys_contacts_id" => $v_json['id'], "sys_status" => "active");
			$update = array("parent_id" => $parent_id, "parent_order" => ($parent_order++));
			$this->db->update("sys_contacts", $update, $where_update);

			if (isset($v_json['children']) && count($v_json['children']) > 0) {
				$this->update_parent($v_json['children'], $v_json['id']);
			}
		}
	}

	function update_status($post = array())
	{
		$status_col = $post['status_col'];
		$status_val = $post['status_val'];

		if (isset($post['id'])) {
			foreach ($post['id'] as $val) {

				$save[$status_col] = $status_val;
				$save["lastupdate"] = date("Y-m-d H:i:s");
				$save["updateby"]   = $this->USER_ID;

				$this->db->where("sys_contacts_id", $val);
				$this->db->where("sys_status", "active");
				$this->db->update("sys_contacts", $save);
			}
		}
	}
}
