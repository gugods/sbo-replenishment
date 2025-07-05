<?php

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class Graphql extends REST_Controller
{

	function __construct()
	{
		parent::__construct();

		header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

		$this->load->model("query_type_model");
		$this->load->model("mutation_type_model");
		$this->load->helper(["common", "do_upload", "db_pagination", "types"]);
		$this->load->model([
			"token_keys_model",
		]);
		$this->load->library('form_validation');
		$this->load->library('PHPExcel');
	}

	function index_get()
	{
		$this->index_post();
	}

	function index_post()
	{
		if ($this->input->get_request_header("Language")) {
			$language = $this->input->get_request_header("Language");
		} else {
			$language = 'EN';
		}
		$lang = ($language == 'EN') ? 'english' : 'thai';
		$this->lang->load("form_validation", $lang);
		$this->lang->load("message", $lang);

		if ($this->input->get_request_header("Appversion")) {
			$appversion = $this->input->get_request_header("Appversion");
		} else {
			$appversion = 0;
		}

		$authen = $this->check_access_token(false);

		/*loginDate to redis*/
		if (isset($authen['employees_id'])) {
			set_cache_login($authen['employees_id'], date("Y-m-d H:i:s"));
		}

		$schema = new \GraphQL\Type\Schema([
			'query' => $this->query_type_model->query($authen, $appversion),
			'mutation' => $this->mutation_type_model->mutation($authen, $appversion)
		]);

		$rawInput = file_get_contents('php://input');
		if ($rawInput == null) {
			$rawInput = $this->input->post('operations');
		}
		$input = json_decode($rawInput, true);
		$query = $input['query'];
		$variableValues = isset($input['variables']) ? $input['variables'] : null;

		try {
			$rootValue = [];
			$result = \GraphQL\GraphQL::executeQuery($schema, $query, $rootValue, null, $variableValues);
			$output = $result->toArray();
		} catch (\Exception $e) {
			$output = [
				'errors' => [
					[
						'message' => $e->getMessage()
					]
				]
			];
		}
		header('Content-Type: application/json');
		echo json_encode($output);
	}
}
