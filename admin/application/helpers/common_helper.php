<?php
if (!function_exists('getVersion')) {
	function getVersion($isAppCode = true)
	{
		if ($isAppCode) {
			return "1.0.0";
		} else {
			return "1.0.5";
		}
	}
}

if (!function_exists('t')) {
	function t($lang)
	{
		$CI = &get_instance();
		$tran = $CI->lang->line($lang);
		return ($tran == '') ? $lang : $tran;
	}
}

function get_access_token()
{
	if (!isset($_SESSION)) session_start();
	$CI = &get_instance();
	$encryption_key = $CI->config->item('encryption_key');
	$session_id = session_id();
	return md5($session_id) . md5($encryption_key);
}

function get_ip_address()
{
	$client  = @$_SERVER['HTTP_CLIENT_IP'];
	$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	$remote  = $_SERVER['REMOTE_ADDR'];

	if (filter_var($client, FILTER_VALIDATE_IP)) {
		$ip = $client;
	} elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
		$ip = $forward;
	} else {
		$ip = $remote;
	}

	return $ip;
}

function get_jwt_token($payload = [])
{
	$CI = &get_instance();

	$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

	// Create token payload as a JSON string
	$payload = json_encode($payload);

	// Encode Header to Base64Url String
	$base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

	// Encode Payload to Base64Url String
	$base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

	// Create Signature Hash
	$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $CI->config->item('encryption_key'), true);

	// Encode Signature to Base64Url String
	$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

	// Create JWT
	$jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

	return $jwt;
}

function send_mail($subject = "", $messege = "", $email = null)
{
	$CI = &get_instance();

	$result = array();

	include_once(APPPATH . "libraries/PHPMailer/smtp.php");
	include_once(APPPATH . "libraries/PHPMailer/phpmailer.php");

	$mail = new PHPMailer();

	$mail->isSMTP();
	$mail->Host = $CI->config->item('stmp_host');
	$mail->SMTPAuth = true;
	$mail->Username = $CI->config->item('stmp_user');;
	$mail->Password = $CI->config->item('stmp_pass');;
	$mail->SMTPSecure = 'ssl';
	$mail->Port = $CI->config->item('stmp_port');;
	$mail->CharSet = "utf-8";

	$mail->setFrom($CI->config->item('stmp_user'));
	if (is_array($email)) {
		foreach ($email as $val) {
			$mail->addAddress($val);
		}
	} else {
		$mail->addAddress($email);
	}
	$mail->isHTML(true);

	$mail->Subject = $subject;
	$mail->Body    = $messege;

	if (!$mail->send()) {
		$result['status']  = false;
		$result['message'] = $mail->ErrorInfo;
	} else {
		$result['status']  = true;
		$result['message'] = "success";
	}
	return $result;
}

//parameter $key string && array
function get_array_by_key($array, $key)
{
	$results = [];
	foreach ($array as $v) {
		$index =  '';
		if (is_array($key)) {
			foreach ($key as $vv) $index .= $v[$vv] . '_';
		} else {
			$index = $v[$key];
		}
		$index = trim($index, '_');
		if (!isset($results[$index])) $results[$index] = [];
		array_push($results[$index], $v);
	}
	return $results;
}

//parameter $key string && array
function get_value_by_key($array, $key, $select = '')
{
	$results = [];
	foreach ($array as $v) {
		$index =  '';
		if (is_array($key)) {
			foreach ($key as $vv) $index .= $v[$vv] . '_';
		} else {
			$index = $v[$key];
		}
		$index = trim($index, '_');
		if (!isset($results[$index])) $results[$index] = [];
		if ($select == '') {
			$results[$index] = $v;
		} else {
			$results[$index] = $v[$select];
		}
	}
	return $results;
}

//parameter string
function text_empty($text, $tag)
{
	return ($text == '') ? $tag : $text;
}

function check_date_import($date)
{
	$d = date("d", strtotime($date));
	$m = date("m", strtotime($date));
	$Y = date("Y", strtotime($date));
	if (checkdate($m, $d, $Y) && $Y < 2500) {
		return true;
	} else {
		return false;
	}
}


function get_month_import($monthly)
{
	if (strpos($monthly, 'มกราคม') !== false) {
		return "01";
	} else if (strpos($monthly, 'กุมภาพันธ์') !== false) {
		return "02";
	} else if (strpos($monthly, 'มีนาคม') !== false) {
		return "03";
	} else if (strpos($monthly, 'เมษายน') !== false) {
		return "04";
	} else if (strpos($monthly, 'พฤษภาคม') !== false) {
		return "05";
	} else if (strpos($monthly, 'มิถุนายน') !== false) {
		return "06";
	} else if (strpos($monthly, 'กรกฎาคม') !== false) {
		return "07";
	} else if (strpos($monthly, 'สิงหาคม') !== false) {
		return "08";
	} else if (strpos($monthly, 'กันยายน') !== false) {
		return "09";
	} else if (strpos($monthly, 'ตุลาคม') !== false) {
		return "10";
	} else if (strpos($monthly, 'พฤศจิกายน') !== false) {
		return "11";
	} else if (strpos($monthly, 'ธันวาคม') !== false) {
		return "12";
	}
}

function get_year_import($monthly)
{
	$year = intval(substr($monthly, strlen($monthly) - 4));
	if ($year > 2500) {
		return $year - 543;
	} else {
		return $year;
	}
}


function check_monthly_import($monthly)
{
	$month = get_month_import($monthly);
	if (intval($month) < 1 || intval($month) > 12) {
		return false;
	}
	return true;
}

function format_monthly_import($monthly)
{
	$month = get_month_import($monthly);
	$year = get_year_import($monthly);

	return $month . '-' . $year;
}


function format_money_import($value)
{
	return str_replace(',', '', $value);
}

function format_monthly_export($month, $year)
{
	if ($month == "" || $year == "") {
		return trim($month . $year);
	} else {
		return t('MONTH_' . $month) . ' ' . ($year + 543);
	}
}

function format_money_export($value)
{
	return number_format($value, 2);
}

function set_post_session($key, $post)
{
	$key = 'post_' . $key;
	if (!isset($_SESSION)) session_start();
	$_SESSION[$key] = $post;
}

function get_post_session($key, $post)
{
	$key = 'post_' . $key;

	if (!isset($_SESSION)) session_start();
	$search = isset($_GET['search']) ? $_GET['search'] : "";
	$isReset = $search == "reset" ? false : true;
	if ($isReset && isset($_SESSION[$key])) {
		return $_SESSION[$key];
	} else {
		unset($_SESSION[$key]);
		return $post;
	}
}

function get_cache_string($args)
{
	$cache_string = '';
	foreach ($args as $val) {
		if (is_array($val)) {
			if (count($val) > 0) $cache_string .= json_encode($val);
		} else {
			if ($val != "") $cache_string .= json_encode($val);
		}
	}

	return md5($cache_string);
}


function convert_duration_time($time, $isVideo = true)
{
	if ($isVideo) {
		$hours = floor($time / 3600);
		$minutes = floor(($time / 60) % 60);
		$seconds = floor($time % 60);
		$seconds = ($seconds < 10) ? "0{$seconds}" : $seconds;
		$minutes = ($minutes < 10) ? "0{$minutes}" : $minutes;
		$hours = ($hours < 10) ?  "0{$hours}" : $hours;
		return "{$hours}:{$minutes}:{$seconds}";
	} else {
		$hour = intval($time['video_hour']) * 3600;
		$minute = intval($time['video_minute']) * 60;
		$second = intval($time['video_second']);
		return ($hour + $minute + $second);
	}
}

function get_report_title_date($start_date, $end_date, $prefix_date = '')
{
	$title_date = "";
	if ($start_date != "" && $end_date != "") {
		$prefix_date = $prefix_date != "" ? $prefix_date : 'ตั้งแต่วันที่';
		$title_date = " {$prefix_date} " . $start_date . ' ถึง ' . $end_date;
	} else 	if ($start_date != "" && $end_date == "") {
		$prefix_date = $prefix_date != "" ? $prefix_date : 'เริ่มวันที่';
		$title_date = " {$prefix_date} " . $start_date;
	} else 	if ($start_date == "" && $end_date != "") {
		$prefix_date = $prefix_date != "" ? $prefix_date : 'ก่อนวันที่';
		$title_date = " {$prefix_date} " . $end_date;
	} else {
		$prefix_date = $prefix_date != "" ? $prefix_date : 'ณ วันที่';
		$title_date = " {$prefix_date} " . date("d/m/Y");
	}

	return $title_date;
}

function calculate_percent($value, $total, $showPercent = true)
{
	$total = floor($total);
	$value = floor($value);
	$suffix_percent = ($showPercent) ? '%' : '';

	if ($total > 0) {
		$percent = floor(($value * 100) / $total);
		$percent = $percent > 100 ? 100 : $percent;
		return $percent . $suffix_percent;
	} else {
		return '0' . $suffix_percent;
	}
}

function random_password($length)
{
	$alphabet = 'abcdefghijklmnopqrstuvwxyz0123456789';
	$pass = array(); //remember to declare $pass as an array
	$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	for ($i = 0; $i < $length; $i++) {
		$n = rand(0, $alphaLength);
		$pass[] = $alphabet[$n];
	}
	return implode($pass); //turn the array into a string
}


function sale_amount_format($total_amount, $sale_qty)
{
	if ($sale_qty != 0 && $total_amount != 0) {
		return number_format($total_amount / $sale_qty, '2', '.', '');
	}
}

function get_month_form_quarter($quarter_start, $quarter_end)
{
	$end = intVal($quarter_start) * 3;
	$start = $end - 3 + 1;

	if ($quarter_start != $quarter_end) {
		$end = intVal($quarter_end) * 3;
	}

	$start =  str_pad($start, 2, '0', STR_PAD_LEFT);
	$end =  str_pad($end, 2, '0', STR_PAD_LEFT);

	return [$start, $end];
}

function array_sort_by($array, $key, $sort = SORT_ASC)
{
	$keys = array_column($array, $key);
	array_multisort($keys, $sort, $array);
	return $array;
}


function array_sum_by($array, $key)
{
	$keys = array_column($array, $key);
	$sum = array_sum($keys);
	return $sum;
}


function array_number_by_key($array, $key)
{
	foreach ($array as $k => $v) {
		$array[$k] = $v;
		if (is_array($key)) {
			foreach ($key as  $vv) {
				$array[$k][$vv] = floatval($v[$vv]);
			}
		} else {
			$array[$k][$key] = floatval($v[$key]);
		}
	}

	return $array;
}

function short_number_format($number, $decimals = 0)
{
	if ($number >= 1000000) {
		return number_format($number / 1000000, $decimals) . "M";
	} else if ($number >= 1000) {
		return number_format($number / 1000, $decimals) . "K";
	} else {
		return number_format($number, $decimals);
	}
}
