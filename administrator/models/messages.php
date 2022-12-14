<?php
class Messages
{
	//message constants
	const SITE_URL = 'https://oniontabs.com/demo/marketingpros/',
		ADMIN_EMAIL = 'info@marketingpros.ng';

	private static $_msg_template = "<!DOCTYPE html>
	<html lang='en'>
	
	<head>
		<meta charset='utf-8'>
		<meta name='viewport' content='width=device-width, initial-scale=1'>
		<style>.container{position:relative;width:100%;max-width:960px;margin:0 auto;padding:0 20px;box-sizing:border-box}.column,.columns{width:100%;float:left;box-sizing:border-box}@media (min-width:400px){.container{width:85%;padding:0}}@media (min-width:550px){.container{width:80%}.column,.columns{margin-left:4%}.column:first-child,.columns:first-child{margin-left:0}.one.column,.one.columns{width:4.66666666667%}.two.columns{width:13.3333333333%}.three.columns{width:22%}.four.columns{width:30.6666666667%}.five.columns{width:39.3333333333%}.six.columns{width:48%}.seven.columns{width:56.6666666667%}.eight.columns{width:65.3333333333%}.nine.columns{width:74%}.ten.columns{width:82.6666666667%}.eleven.columns{width:91.3333333333%}.twelve.columns{width:100%;margin-left:0}.one-third.column{width:30.6666666667%}.two-thirds.column{width:65.3333333333%}.one-half.column{width:48%}.offset-by-one.column,.offset-by-one.columns{margin-left:8.66666666667%}.offset-by-two.column,.offset-by-two.columns{margin-left:17.3333333333%}.offset-by-three.column,.offset-by-three.columns{margin-left:26%}.offset-by-four.column,.offset-by-four.columns{margin-left:34.6666666667%}.offset-by-five.column,.offset-by-five.columns{margin-left:43.3333333333%}.offset-by-six.column,.offset-by-six.columns{margin-left:52%}.offset-by-seven.column,.offset-by-seven.columns{margin-left:60.6666666667%}.offset-by-eight.column,.offset-by-eight.columns{margin-left:69.3333333333%}.offset-by-nine.column,.offset-by-nine.columns{margin-left:78%}.offset-by-ten.column,.offset-by-ten.columns{margin-left:86.6666666667%}.offset-by-eleven.column,.offset-by-eleven.columns{margin-left:95.3333333333%}.offset-by-one-third.column,.offset-by-one-third.columns{margin-left:34.6666666667%}.offset-by-two-thirds.column,.offset-by-two-thirds.columns{margin-left:69.3333333333%}.offset-by-one-half.column,.offset-by-one-half.columns{margin-left:52%}}html{font-size:62.5%}body{font-size:1.5em;line-height:1.6;font-weight:400;font-family:Raleway,HelveticaNeue,'Helvetica Neue',Helvetica,Arial,sans-serif;color:#222}h1,h2,h3,h4,h5,h6{margin-top:0;margin-bottom:2rem;font-weight:300}h1{font-size:4rem;line-height:1.2;letter-spacing:-.1rem}h2{font-size:3.6rem;line-height:1.25;letter-spacing:-.1rem}h3{font-size:3rem;line-height:1.3;letter-spacing:-.1rem}h4{font-size:2.4rem;line-height:1.35;letter-spacing:-.08rem}h5{font-size:1.8rem;line-height:1.5;letter-spacing:-.05rem}h6{font-size:1.5rem;line-height:1.6;letter-spacing:0}@media (min-width:550px){h1{font-size:5rem}h2{font-size:4.2rem}h3{font-size:3.6rem}h4{font-size:3rem}h5{font-size:2.4rem}h6{font-size:1.5rem}}p{margin-top:0}a{color:#1eaedb}a:hover{color:#0fa0ce}ul{list-style:circle inside}ol{list-style:decimal inside}ol,ul{padding-left:0;margin-top:0}ol ol,ol ul,ul ol,ul ul{margin:1.5rem 0 1.5rem 3rem;font-size:90%}li{margin-bottom:1rem}code{padding:.2rem .5rem;margin:0 .2rem;font-size:90%;white-space:nowrap;background:#f1f1f1;border:1px solid #e1e1e1;border-radius:4px}pre>code{display:block;padding:1rem 1.5rem;white-space:pre}td,th{padding:12px 15px;text-align:left;border-bottom:1px solid #e1e1e1}td:first-child,th:first-child{padding-left:0}td:last-child,th:last-child{padding-right:0}.button,button{margin-bottom:1rem}fieldset,input,select,textarea{margin-bottom:1.5rem}blockquote,dl,figure,form,ol,p,pre,table,ul{margin-bottom:2.5rem}.u-full-width{width:100%;box-sizing:border-box}.u-max-full-width{max-width:100%;box-sizing:border-box}.u-pull-right{float:right}.u-pull-left{float:left}hr{margin-top:3rem;margin-bottom:3.5rem;border-width:0;border-top:1px solid #e1e1e1}.container:after,.row:after,.u-cf{content:'';display:table;clear:both}.fw-bold{font-weight:700}.text-accent{color:#00ffd6}.text-primary{color:#ffad23}.text-green{color:green}.text-center{text-align:center}.mb-1rem{margin-bottom:1rem}.mb-0{margin-bottom:0}p{margin-bottom:1rem}</style>
	</head>
	
	<body>
		<div class='container'>
			<div class='row'>
				<div class='offset-by-two eight columns' style='margin-top: 5%;'>
					<div class='card' style='background:#fff;border-radius:.8rem;border:0;'>
						<div class='card-body' style='padding:2rem 1.6rem;'>
							<img src='https://oniontabs.com/demo/marketingpros/media/images/marketing-pros-logo.png' class='card-cover' style='max-width:150px'>
							<br>
							<br>
							<br>
							<h5 class=''>Hello <span  style='font-weight: bold;'>[name]</span>,</h5>
							<br>
							    [message]
							<br>
							<br>
							<p class='lead mb-0'>Cheers,</p>
							<p class=''>Marketingpros???s Customer Service Team</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
	
	</html>";

	// variables static		
	private static 	$_headers,
		$_to,
		$_message,
		$_subject,
		$_table_name = 'message',
		$_table_id = 'id';
	private $_db, $_data;

	// constructor
	public function __construct()
	{
		$this->_db = DB::getInstance();
	}

	//create
	// working
	public function create($fields = array())
	{
		if (!$this->_db->insert(self::$_table_name, $fields)) {
			throw new Exception('There was a problem saving your plan.');
		}
	}
	// update
	public function update($fields = array(), $id = null)
	{
		if ($id && is_numeric($id)) {
			$id = (int) $id;
			if (!$this->_db->update(self::$_table_name, $id, $fields)) {
				throw new Exception('There was a problem saving your information...');
			}
			return true;
		}
		return false;
	}
	// logically improper
	public function find($id)
	{
		$result = $this->_db->get(self::$_table_name, array('id', '=', $id));
		if ($result->count()) {
			$this->_data = $result->first();
			return true;
		}
		return false;
	}

	public function getMessages($per_page, $off_set, $where = null)
	{
		return $this->_db->getPerPage($per_page, $off_set, self::$_table_name, $where, "ORDER BY id DESC");
	}

	public static function send_with_temp($message, $subject, $to = null)
	{
		if (!$to) {
			self::$_to = self::ADMIN_EMAIL;
		} else {
			self::$_to = $to;
		}
		// spacify boundary
		//$boundary = uniqid('nsuk');

		self::$_subject = $subject . ' : ';
		self::$_message = $message;
		self::$_message = wordwrap($message, 70);

		$from = "marketingpros.ng Team  <hello@marketingpros.ng>";
		self::$_headers = "From: {$from}\n";
		self::$_headers .= "Reply-To: {$from}\n";
		self::$_headers .= "MIME-Version: 1.0\n";
		self::$_headers .= "Content-Type: text/html; charset=UTF-8";

		$msgtext = str_replace("[message]", self::$_message, self::$_msg_template);
		// print_r($msgtext);
		if ($result = @mail(self::$_to, self::$_subject, $msgtext, self::$_headers)) {
			return True;
		} else {
			return False;
		}
	}

	public static function send($message, $subject, $to = null, $name = null, $temp = false, $cover = false)
	{
		if (!$to) {
			self::$_to = self::ADMIN_EMAIL;
		} else {
			self::$_to = $to;
		}
		// spacify boundary
		//$boundary = uniqid('nsuk');

		self::$_subject = $subject . ' : ';
		self::$_message = $message;
		// self::$_message = wordwrap($message, 70);

		$from = "marketingpros.ng Team  <hello@marketingpros.ng>";
		self::$_headers = "From: {$from}\n";
		self::$_headers .= "Reply-To: {$from}\n";
		self::$_headers .= "MIME-Version: 1.0\n";
		self::$_headers .= "Content-Type: text/html; charset=UTF-8";

		if ($temp) {
			$msgtext = str_replace(array("[message]", "[name]", "[img]"), array(self::$_message, $name,  $cover), self::$_msg_template);
		} else {
			$msgtext = $message;
		}

		if ($result = @mail(self::$_to, self::$_subject, $msgtext, self::$_headers)) {
			return True;
		} else {
			return False;
		}
	}

	// Send without html 
	public static function sendText($to, $subject, $msg)
	{

		// create a boundary for the email. This 
		$boundary = uniqid('np');

		// Define your from and header

		$from =  "marketingpros.ng Team  <hello@marketingpros.ng>";
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "From: {$from} \r\n";
		$headers .= "Reply-To: " . $from . "\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8";

		$msgtext = "<div style='width: 400px; font-size: 1.3em; font-weight: 400;border: 1px solid #eee; margin: 0 auto; padding: 20px;'>";
		$msgtext .= "<h2 style='text-align: center; margin-bottom: 25px; padding: 15px;'><img src='https://farm5.staticflickr.com/4525/37865424155_40d9bbc34a_m.jpg'></h2>";
		$msgtext .= $msg;
		$msgtext .= "<div style='background: #eee; text-align: center; padding: 15px; margin-top: 35px; min-height: 100px; font-size: 1.2em; border-top: 1px solid #ccc; font-weight: 600;'><a href='#'>Login to Landeus</a><br /><br />
            <a href='#'>marketingpros.ng</a><br /><br />
            <p style='font-size: .9em; text-align: center;'>&copy; marketingpros.ng</p>
            </div>";
		$msgtext .= "</div>";

		if (filter_var($to, FILTER_VALIDATE_EMAIL) && (strlen($subject) > 5) && (strlen($msg) > 10)) {
			if (mail($to, $subject, $msgtext, $headers)) {
				return True;
			}
		}

		return false;
	}


	// Student send method
	public static function sendToUser($to, $subject, $msg)
	{
		$to = $to;
		$subject = $subject;
		//create a boundary for the email. This 
		$boundary = uniqid('np');

		//headers - specify your from email address and name here
		//and specify the boundary for the email
		$from =  "marketingpros.ng Team  <hello@marketingpros.ng>";
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "From: {$from} \r\n";
		$headers .= "Reply-To: " . $from . "\r\n";
		$headers .= "Content-Type: multipart/alternative;boundary=" . $boundary . "\r\n";

		// check if message contain html or style attributes
		if ($msg  !== strip_tags($msg)) {
			// No html
			$html = $msg;
		} else {
			// contain html 
			$text = $msg;
		}
		//echo $html || $text; die();
		$text = isset($text) ? $text : '';
		//here is the content body
		$message = $text;
		$message .= "\r\n\r\n--" . $boundary . "\r\n";
		$message .= "Content-type: text/plain;charset=utf-8\r\n\r\n";

		// Plain Text Message
		$message .= $text;
		$message .= "\r\n\r\n--" . $boundary . "\r\n";
		$message .= "Content-type: text/html;charset=utf-8\r\n\r\n";

		// html 
		$message .= isset($html) ? $html : '';
		$message .= "\r\n\r\n--" . $boundary . "--";

		// Try Sending Message
		$message = (strlen($message) > 10) ? $message : $msg;

		if (mail($to, $subject, $message, $headers)) {
			return True;
		}
		return False;
	}

	// 1. User Account Creation
	public static function newAccount($name, $email = null)
	{
		$message = "<p class='lead'>Were sending this email to confirm that your account at Marketingpros was created successfully.</p>
                    <p class='lead'>Click this button to log in to your account and start shopping:</p><br><br>
                    <a class='button' href='{$url}' style='background:#ff1a00;border: none;color: white;padding: 0.8rem 2rem;text-align: center;text-decoration: none;display: inline-block;'>Go to my account</a><br><br>
                    <p class='lead'>We hope you will be happy with products offered by marketingpros.ng and that you will shop with us again
                        and
                        again.</p>
                    <p class='lead'>Our goal is to offer the widest range of products at the highest quality. If you think we should add any
                        items to
                        our marketplace, don???t hesitate to contact us and share your feedback.</p>
                    <p class='lead mb-4'>Until then, enjoy your shopping!</p>";

		$subject = "Account Created Successfully.";
		if (self::send($message, $subject, $email, $name, true)) {
			return True;
		}
		return False;
	}


	// 2. User Verification Email
	public static function verifyEmail($token, $name, $email = null)
	{
		$url = self::SITE_URL . "controllers/profile.php?rq=verify&token={$token}";
		$link = "<a href='{$url}'>{$token}</a>";
		$message = "<p class='lead'>You registered an account on marketingpros, before being able to make a purchase you need to verify that this is your email address by clicking here: {$link}</p><br><br>
            		<a class='button' href='{$url}' style='background:#ff1a00;border: none;color: white;padding: 0.8rem 2rem;text-align: center;text-decoration: none;display: inline-block;'>Verify my email</a><br><br>
            		<p class='lead mb-0'>Cheers,</p>";
		$subject = "Please verify your email address";
		if (self::send($message, $subject, $email, $name, true)) {
			return True;
		}
		return False;
	}

	// 2.1 User Verification Email
	public static function verifySellerEmail($token, $name, $email = null)
	{
		$url = self::SITE_URL . "controllers/profile.php?rq=verify&token={$token}";
		$link = "<a href='{$url}'>{$token}</a>";
		$message = "<p class='lead'>Your Seller Center account has been successfully created.</p>
                    <p class='lead'>You registered as a Seller at Marketingpros Seller Center with the following account data: </p>
                    <p class='lead'>Please click on the link to confirm your registration: {$link}</p><br><br>
                    <a class='button' href='{$url}' style='background:#ff1a00;border: none;color: white;padding: 0.8rem 2rem;text-align: center;text-decoration: none;display: inline-block;'>Confirm my registration</a><br><br>
                    <p class='lead'>After logging in, you can edit information on your seller profile previously provided (store name, bank
                        details, contact details and address).</p>
                    <p class='lead'>If you have any questions, concerns regarding registration please call our support lines on 07032785229 between 9 am and 5 pm from Monday to Friday. We are always available to take your calls :)</p>";
		$subject = "Verify your email and finish your registration on the Seller Center!";
		
		
		if (self::send($message, $subject, $email, $name, true)) {
			return True;
		}
		return False;
	}

	// 3. Thank you  Message for first Order Placement.
	public static function firstOrder($name, $email = null)
	{
		$message = "<p class='lead'>Thank you for visiting marketingpros and placing your first order! </p>
                    <p class='lead'>We are glad that you found the product(s) you were looking for. </p>
                    <p class='lead'>Our goal is for ourcustomers to always be satisfied, so let us know how your experience with us was
                        during your first time shopping here.</p>
                    <p class='lead'>We look forward to seeing you around again soon.</p>";
		$subject = "Thank you for shopping with Marketing Pros";
		if (self::send($message, $subject, $email, $name, true)) {
			return True;
		}
		return False;
	}

	// 4. Order Confirmation
	public static function orderConfirmation($name, $email, $order, $date, $price, $address, $items)
	{

		$message = "<p class='lead'>Woo hoo! Your order is on its way. Your order details can be found below.</p>
                    <p class='lead mb-1 fw-bold'>ORDER SUMMARY: </p>
                    <p class='lead mb-0'>Order #: {$order}</p>
                    <p class='lead mb-0'>Order Date: {$date}</p>
                    <p class='lead mb-1'>Order Total: {$price}</p>
                    <p class='lead'>SHIPPING ADDRESS: {$address}</p>
                    
                    <table class='u-full-width table mt-3 mb-4'>
                        <thead>
                            <tr>
                                <th scope='col'>ITEMS SHIPPED</th>
                                <th scope='col'>QTY</th>
                                <th scope='col'>PRICE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- format <tr>
                    				<td>Mark</td>
                    				<td>Otto</td>
                    				<td>@mdo</td>
                    				</tr> -->
                            {$items}
                        </tbody>
                    </table>
                    
                    <p class='lead'>We hope you enjoyed your shopping experience with us and that you will visit us again soon.</p>";
                    
		$subject = "Thank you! Your order is confirmed.";
		if (self::send($message, $subject, $email, $name, true)) {
			return True;
		}
		return False;
	}

	// 5. Reset Password
	public static function resetPassword($token, $name, $email = null)
	{
		$url = self::SITE_URL . "password-reset?token={$token}";
		$link = "<a href='{$url}'>{$token}</a>";
		$message = "<p class='lead'>It seems like you forgot your password. If this is true, click the link below to reset your password.</p>
                    <p class='lead'>Reset my password {$link}</p><br><br>
                    <a class='button' href='{$url}' style='background:#ff1a00;border: none;color: white;padding: 0.8rem 2rem;text-align: center;text-decoration: none;display: inline-block;'>Reset my password</a><br><br>
                    <p class='lead'>If you did not forget your password, please disregard this email.</p>";
		$subject = "Your password reset link";
		if (self::send($message, $subject, $email, $name, true)) {
			return True;
		}
		return False;
	}
	
	// Pass number and Message
	public static function smsAPI($phone, $message, $sender = null)
	{
		if ($phone && $message) {

			$message = urlencode($message);

			$new_phone = (substr($phone, 0, 1) == '0') ? substr($phone, 1, 10) : ((substr($phone, 0, 3) == '234') ? substr($phone, 3, 10) : $phone);
			$new_phone = strlen($new_phone) <= 10 ? '234' . $new_phone : $new_phone;
			//print_r($message); exit();
			$by = $sender ? $sender : 'TheCapital';
			$url = "https://portal.nigeriabulksms.com/api/?username=Chriscapital@yahoo.com&password=Christopher&message={$message}&sender={$by}&mobiles={$new_phone}";

			//$url = "https://www.bulksmsnigeria.com/api/v1/sms/create?api_token=ajS9qECSiueEvNC2Y3uKQ8JwM6MnuyzdKnKP3gxngCpWewmCMctc2vcvDfiI&from={$by}&to={$new_phone}&body={$message}&dnd=4";

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_USERAGENT, "{$_SERVER['HTTP_USER_AGENT']}");
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			$output = curl_exec($ch);
			curl_close($ch);
			return  $output;
		}
		return false;
	}

	// Telegram function which you can call
	public static function telegramAPI($msg, $telegrambot = "1278884186:AAFXti9IirM4Ov01kRCDAdyyR8XUV9lblMM", $telegramchatid = "-1001460743555")
	{
		$url = 'https://api.telegram.org/bot' . $telegrambot . '/sendMessage';
		$data = array('chat_id' => $telegramchatid, 'text' => $msg);
		$options = array('http' => array('method' => 'POST', 'header' => "Content-Type:application/x-www-form-urlencoded\r\n", 'content' => http_build_query($data),),);
		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		return $result;
	}

	public function data()
	{
		return $this->_data;
	}
}