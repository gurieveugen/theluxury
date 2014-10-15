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