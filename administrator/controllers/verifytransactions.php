<?php
require_once('../core/init.php');

$errors = array();

if (Helpers::isXHR() && Input::exists() && isset($_POST['ref'])) {
	$result = array();
	//The parameter after verify/ is the transaction reference to be verified
	$url = 'https://api.paystack.co/transaction/verify/' . $_POST['ref'];

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt(
		$ch,
		CURLOPT_HTTPHEADER,
		[
			'Authorization: Bearer sk_test_9977294a7909eba9036e87efa9ff1c0e8903dedb'
		]
	);
	$request = curl_exec($ch);
	$error = curl_error($ch);
	//echo $error;
	curl_close($ch);

	if ($request) {
		$result = json_decode($request, true);
	}
	//echo json_encode($result); exit;
	if (is_array($result) && array_key_exists('data', $result) && array_key_exists('status', $result['data']) && ($result['data']['status'] === 'success')) {
		$db = DB::getInstance();
		$data = json_decode(Input::get('data'), true);
		//print_r($data);
		//echo json_encode($data); exit;

		if (Input::get('req')) {
			switch (Input::get('req')) {
				case "pay":
					try {
						$amount = $result['data']['amount'] / 100;
						$user = new User();

						$user->update(array(
							'paid' => 1,
						), $user->data()->id);

						// message
						if (empty($errors)) {
							Session::flash("success", "Congratulation! Payment made successfully.<br/>");
							echo json_encode(array('success' => true, 'message' => "Congratulation! Payment made successfully, your transaction reference is: {$data['ref']}. Thank you."));
							exit;
						}
					} catch (Exception $e) {
						$errors[] = $e->getMessage();
					}

					break;
			}
		}


		/*if (is_array($data) && isset($data['ttype']) && $data['ttype'] == "coursesub") {
			$user = new User($data['owner']); // course owner
			$owner_wallet = new Wallet($data['owner']); // course owner wallet
			$course = new Courses(); // course
			$course->find($data['course_id']);
			$csub = new Csubscribers();
			
			try {
				// update the wallet
				$owner_wallet->update(array(
					'bal' => (double)($owner_wallet->data()->bal + (float)$data['amount'])
				), $owner_wallet->data()->id);

				// update the subscription
				$course->update(array(
					'subscribers' => ($course->data()->subscribers + 1)
				), $course->data()->id);

				// add user to subscriber
				$csub->create(array(
					'course_id' => $course->data()->id,
					'user_id' => $data['sid']
				));

			} catch (Exception $e) {
				$errors[] = $e->getMessage();
			}

			// message
			if (empty($errors)) {
				echo json_encode(array('success' => true, 'message' => "Enjoy your course, subscription successful, your transaction reference is: {$data['ref']}. Thank you."));
				exit;
			}

		} elseif(isset($data["package_name"]) && isset($data["package_price"])) {
			$student_wallet = new Wallet($data['student_id']);
			if (isset($data['sponsor_id']) && !empty($data['sponsor_id']) && $data['sponsor_id']) {
				$sponsor_wallet = new Wallet($data['sponsor_id']);
			}

			$user = new User($data['student_id']);
			// try information
			try {
				// update transaction table
				$db->insert('transactions', array(
					't_type' => 'subscription',
					'amount' => (double)((double)$result['data']['amount'] / 100),
					'user_id' => $data['student_id'],
					'ref' => Input::get('ref'),
					'date_added' => date('Y-m-d H:i:s', time())
				));
				// get subpackages
				$old_packs = $user->data()->sub_packages;
				$check = $old_packs ? json_decode($old_packs, true) : array();

				$new = json_encode(array_merge($check, array($data['package_name'] => array('amount' => $data['package_price']))));

				$user->update(array(
					'sub_packages' => $new
				), $data['student_id']);

				if (isset($sponsor_wallet)) {
					// settings
					$settings = new Settings();
					$sponsor_wallet->update(array(
						'bal' => (double)($sponsor_wallet->data()->bal + (((intval($settings->getRefPercentage())) / 100) * (float)$data['package_price']))
					), $sponsor_wallet->data()->id);
				}

				// send user message
				$me = "Thank you for subscribing to this package {$data['package_name']}, You may proceed to your dashboard";
				//Messages::sendText($user->data()->email, 'Successful Order', $me);
				//Messages::send("New Order logged by Customer. Order Id: {$data['ref']}", "New Customer Order ".date('d-M-Y h:i:s', time()));
			} catch (Exception $e) {
				$errors[] = $e->getMessage();
			}
			// send message

			if (empty($errors)) {
				echo json_encode(array('success' => true, 'message' => "Subscription Successful, your transaction reference is: {$data['ref']}. Thank you."));
				exit;
			}
		}
		*/
	} else {
		$errors[] = "Transaction was unsuccessful";
	}
} else {
	$errors[] = "No transaction reference provided";
}
echo json_encode($errors);
exit;
