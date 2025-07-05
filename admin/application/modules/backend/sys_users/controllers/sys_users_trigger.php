<?php
class sys_users_trigger extends Backend
{
	function __construct()
	{
		parent::__construct();
	}

	function set_trigger($action = "", $id = 0, $data = array())
	{
		$obj = array("status" => true, "message" => "");
		if ($action == "save:before") {
			$obj = $this->checkDuplicateUsername($id, $data);
			if (!parent::check_password($id, $data['password'], $message)) {
				$obj["message"] = $message;
				$obj["status"] = false;
			}
		} else if ($action == "update:before") {
			$obj = $this->checkChangePassword($id, $data);
		} else if ($action == "save:after") {
			$save = ['store_ids' => null];

			if (isset($data['store_ids'])) {
				$save['store_ids'] = implode(",", $data['store_ids']);
			}
			$this->db->where("sys_users_id", $id);
			$this->db->update("sys_users", $save);

			$this->updateKeyProducts($id, $data);
		}

		return $obj;
	}

	private function checkDuplicateUsername($id = 0, $data = [])
	{
		$obj = array("status" => true, "message" => "");
		$num_rows = $this->db
			->where("username", $data['username'])
			->where("sys_status", "active")
			->get("sys_users")
			->num_rows();

		if ($num_rows > 0 && $id == "") {
			$obj["message"] = 'Username can not use this. Because a user already exists.';
			$obj["status"] = false;
		} else {
			if ($id != "") {
				$result = $this->db
					->select("username")
					->where("sys_users_id", $id)
					->where("sys_status", "active")
					->get("sys_users")
					->first_row('array');

				if (@$result['username'] != $data['username'] && $num_rows > 0) {
					$obj["message"] = 'Username can not use this. Because a user already exists.';
					$obj["status"] = false;
				}
			}
		}
		return $obj;
	}

	private function checkChangePassword($id = 0, $data = [])
	{
		$obj = array("status" => true, "message" => "");
		$result = $this->db
			->select("password")
			->where("sys_users_id", $id)
			->where("sys_status", "active")
			->get("sys_users")
			->first_row('array');

		if ($data['old_password'] !== "") {
			if (!password_verify($data['old_password'], @$result['password'])) {
				$obj["message"] = 'Your old password is invalid.';
				$obj["status"] = false;
			}
			if (!parent::check_password($id, $data['password'], $message)) {
				$obj["message"] = $message;
				$obj["status"] = false;
			}
		}
		return $obj;
	}

	private function updateKeyProducts($id = 0, $data = [])
	{
		$this->db->where("sys_users_id", $id);
		$this->db->delete("key_products");

		if (isset($data['product_section_ids']) && is_array($data['product_section_ids'])) {
			foreach ($data['product_section_ids'] as $stores_id => $product_section_ids) {
				$where = ['sys_users_id' => $id, 'stores_id' => $stores_id];
				$result = $this->db->get_where("key_products", $where)->first_row('array');
				$save = [];
				if (isset($result['sys_users_id'])) {
					$save['product_section_ids'] = implode(",", $product_section_ids);
					$this->db->where($where);
					$this->db->update('key_products', $save);
				} else {
					$save['sys_users_id'] = $id;
					$save['stores_id'] = $stores_id;
					$save['product_section_ids'] = implode(",", $product_section_ids);
					$this->db->insert("key_products", $save);
				}
			}
		}

		if (isset($data['product_type_ids'])  && is_array($data['product_type_ids'])) {
			foreach ($data['product_type_ids'] as $stores_id => $product_type_ids) {
				$where = ['sys_users_id' => $id, 'stores_id' => $stores_id];
				$result = $this->db->get_where("key_products", $where)->first_row('array');
				$save = [];
				if (isset($result['sys_users_id'])) {
					$save['product_type_ids'] = implode(",", $product_type_ids);
					$this->db->where($where);
					$this->db->update('key_products', $save);
				} else {
					$save['sys_users_id'] = $id;
					$save['stores_id'] = $stores_id;
					$save['product_type_ids'] = implode(",", $product_type_ids);
					$this->db->insert("key_products", $save);
				}
			}
		}
	}
}
