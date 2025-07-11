<?php
class Sys_logs extends Backend
{
	public static $message = "";
	var $module = "sys_logs";
	var $title = "Logs";

	function __construct()
	{
		parent::__construct();
		parent::$view_data["module"] = $this->module;
		parent::$view_data["title"] = $this->title;
		parent::$view_data["checkbox_mode"] = "off";
		parent::$view_data["publish_mode"] = "off";
		parent::$view_data["control_mode"] = "off";
		$this->load->model("sys_logs_model");
	}

	function index()
	{
		$this->formList();
	}

	function formList()
	{
		$post = $this->input->post();
		$post = get_post_session('sys_logs', $post);

		parent::check_access($this->module, "view");

		$rows = $this->sys_logs_model->get_rows($post);
		parent::$view_data["pagesize"] = 30;
		parent::$view_data["rows"] = $rows;
		parent::$view_data["rows_publish"] = $this->sys_logs_model->get_rows_publish($post);
		parent::$view_data["rows_modified"] = $this->sys_logs_model->get_rows_modified($post);
		parent::$view_data["rows_unpublish"] = $this->sys_logs_model->get_rows_unpublish($post);
		parent::$view_data["totalpage"] = ceil($rows / parent::$view_data["pagesize"]);
		parent::$view_data["post"] = $post;
		parent::$view_data["control"] = false;

		$this->load->view("logs_list_view", parent::$view_data);
	}

	function ajaxList()
	{
		$post = $this->input->post();
		if ($post) {
			set_post_session('sys_logs', $post);

			$option = array(
				"post" => $post, "orderby" => $post["orderby"], "sorting" => $post["sorting"]
			);

			$start = $post["thispage"];
			$end = $post["pagesize"];
			$item = $this->sys_logs_model->get_page($option, $start, $end);
			echo  json_encode($item);
		}
	}

	function searchList()
	{
		$post = $this->input->post();

		if ($post) {
			set_post_session('sys_logs', $post);

			parent::check_access($this->module, "view");
			$option = array(
				"post" => $post, "orderby" => $post["orderby"], "sorting" => $post["sorting"]
			);

			$start = $post["thispage"];
			$end = $post["pagesize"];

			$rows = $this->sys_logs_model->get_rows($post);
			$rows_publish = $this->sys_logs_model->get_rows_publish($post);
			$rows_modified = $this->sys_logs_model->get_rows_modified($post);
			$rows_unpublish = $this->sys_logs_model->get_rows_unpublish($post);
			$totalpage = ceil($rows / $post["pagesize"]);

			$item = $this->sys_logs_model->get_page($option, $start, $end);

			$results['item'] = $item;
			$results['option'] = [
				'rows' => $rows,
				'rows_publish' => $rows_publish,
				'rows_modified' => $rows_modified,
				'rows_unpublish' => $rows_unpublish,
				'totalpage' => $totalpage
			];
			echo  json_encode($results);
		}
	}
}
