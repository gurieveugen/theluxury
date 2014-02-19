<?php
class RhinoSupport {

	function create_ticket($name, $email, $message) {
		$pfields['name'] = $name;
		$pfields['emailTo'] = $email;
		$pfields['body'] = $message;
		$pfields['ticketStatus'] = 'open';
		return $this->request('Ticket', $pfields);
	}

	function update_ticket() {
	}

	function get_ticket() {
	}

	function create_user($email, $fname = '', $lname = '') {
		$pfields['emailAddress'] = $email;
		$pfields['firstName'] = $fname;
		$pfields['lastName'] = $lname;
		$pfields['userType'] = 'customer';
		return $this->request('User', $pfields);
	}

	function update_user($id, $email, $fname = '', $lname = '') {
		$pfields['id'] = $id;
		$pfields['emailAddress'] = $email;
		$pfields['firstName'] = $fname;
		$pfields['lastName'] = $lname;
		return $this->request('User', $pfields);
	}

	function request($type, $pfields) {
		global $OPTION;
		$request = curl_init ('https://www.rhinosupport.com/API/'.$type.'/');
		$pfields['apiCode'] = $OPTION['wps_sellers_rhinosupport_api_key'];
		curl_setopt ($request, CURLOPT_POST, true);
		curl_setopt ($request, CURLOPT_POSTFIELDS, http_build_query($pfields));
		curl_setopt ($request, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec ($request);
		curl_close ($request);
		return $result;
	}

}
?>