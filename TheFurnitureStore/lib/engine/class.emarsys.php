<?php
require dirname(__FILE__) .'/lib/simplerestclient.php';
require dirname(__FILE__) .'/lib/ajaxGateway.php';

class Emarsys{
	function is_exist_user($email) {
		$data = array(
			'keyId'	=> '3',
			'keyValues' => array($email)
		);
		$result = $this->request('contact/getdata', 'POST', $data);
		if($result->data->result[0]->{3} === $email) {
			return true;
		}
		return false;
	}

	function add_contact($params) {
        $data = array(
			'key_id'=> '3',
			'1'	 => $params["first_name"],
			'2'	 => $params["last_name"],
			'3'	 => $params["email"],
			'31' => '1'
		);
		if ($params['gender']) {
			$gender = '1';
			if (strpos($params['gender'], 'Female') !== false) {
				$gender = '2';
			}
			$data['5'] = $gender;
		}
		if (isset($params['frompage']) && strlen($params['frompage'])) {
			$data['45968'] = $this->get_reg_source($params['frompage']);
		}
		if ($utm_params = get_utm_params()) {
			if ($utm_params['utm_source']) {
				$data['45971'] = $utm_params['utm_source'];
			}
			if ($utm_params['utm_medium']) {
				$data['45972'] = $utm_params['utm_medium'];
			}
			if ($utm_params['utm_campaign']) {
				$data['45973'] = $utm_params['utm_campaign'];
			}
		}
		if($this->is_exist_user($params["email"])) {
			$result = $this->request('contact', 'PUT', $data);
		} else {
			$result = $this->request('contact', 'POST', $data);
		}
		if($result->replyText == "OK") {
			return $result->data->id;
		}
		return false;
	}
	
	function request($rtype, $method, $data = '') {
		$gateway = new AjaxGateway($rtype);
		return json_decode($gateway->getResponse(json_encode($data), $method));
	}

	function get_reg_source($frompage) {
		$reg_source = 'Actual Register';
		if (strpos($frompage, '/sale') !== false) {
			$reg_source = 'Sale';
		} else if (strpos($frompage, '/whats-new') !== false) {
			$reg_source = 'Whats New';
		} else if (strpos($frompage, '/my-items') !== false) {
			$reg_source = 'My Items';
		} else if (strpos($frompage, '/sell-us') !== false) {
			$reg_source = 'Item Submission Form';
		} else if (strpos($frompage, 'alertslogin=true') !== false) {
			$reg_source = 'My Selection';
		} else if (strpos($frompage, 'wishlist=add') !== false) {
			$reg_source = 'Wishlist';
		}
		return $reg_source;
	}
}

add_action('init', 'emarsys_hivista_init');
function emarsys_hivista_init() {
	global $wpdb;
	if ($_GET['hivista'] == 'emarsys') {
		$emar = new Emarsys();
		$params = array('first_name' => 'Hiv 002', 'last_name' => 'Test 002', 'email' => 'hivtest001@testing.com', 'gender' => 'Male');
		$ecid = $emar->add_contact($params);
		var_dump($ecid);
		exit;
	}
}
?>