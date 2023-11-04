<?php

class Mail{

		public $fromEmail = 'info@el-lugar.com';
		public $fromName = 'El Lugar Cockpit';
		public $apiKey = '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';
		public $templateID = '';
		public $personalizations = '';



		// Function to confirm the email
		public function confirmEmail($person){
			$this->templateID = 'd-01234181e5b743108f60e9db11d49726';
			$this->personalizations = '{
					 "to":[
							{
								 "email":"'.$person['main_email'].'"
							}
					 ],
					 "dynamic_template_data":{
							"name":"'.$person['name'].'",
							"lastname":"'.$person['lastname'].'",
							"confirm_email_url":"https://el-lugar.com/cockpit/actions/confirmEmail/?otp='.$person['salt'].'&stat='.$person['id_person'].'"
						}
			}';
			$this->send();
		}






		// Function to recover the password.
		public function resetPassword($person){
			$this->templateID = 'd-fb39c1c3665a43f8893b70a14b83828f';
			$this->personalizations = '{
					 "to":[
							{
								 "email":"'.$person['main_email'].'"
							}
					 ],
					 "dynamic_template_data":{
							"name":"'.$person['name'].'",
							"lastname":"'.$person['lastname'].'",
							"reset_link":"https://el-lugar.com/cockpit/actions/passwordRecovery/?otp='.$person['salt'].'&stat='.$person['id_person'].'"
						}
			}';
			$this->send();
		}



		public function SendLoginInfoInvestorsMembers($person){
			$this->template = 'd-9b88fdac2cbb4b979d68f0c2b2e9c3b6';
			$this->personalizations = '{
					 "to":[
							{
								 "email":"'.$order['main_email'].'"
							}
					 ],
					 "dynamic_template_data":{
						 	"name":"'.$order['name'].'",
						 	"lastname":"'.$order['lastname'].'",
					    "email":"'.$person['main_email'].'",
							"password":"'.$person['tmp_password'].'"
					 }
			}';
			$this->send();
		}



		public function LoanBondPurchased($order){
			$this->templateID = 'd-08212aa674264dd7a4d627858e93d545';
			$this->personalizations = '{
					 "to":[
							{
								 "email":"'.$order['main_email'].'"
							}
					 ],
					 "dynamic_template_data":{
						 	"name":"'.$order['name'].'",
						 	"lastname":"'.$order['lastname'].'",
					    "loan_bond_type":"'.$order['type'].'",
					    "unit_price": "'.number_format($order['unit_price'],2,".",",").'",
					    "quantity":"'.$order['quantity'].'",
					    "order_total":"'.number_format($order['order_total'],2,".",",").'"
					 }
			}';
			$this->send();
		}



		public function TicketPurchased(){
			$this->templateID = 'd-fb39c1c3665a43f8893b70a14b83828f';
			$this->personalizations = '{
					 "to":[
							{
								 "email":"'.$person['main_email'].'"
							}
					 ],
					 "dynamic_template_data":{
							"name":"'.$person['name'].'",
							"lastname":"'.$person['lastname'].'",
							"reset_link":"https://el-lugar.com/cockpit/actions/passwordRecovery/?otp='.$person['salt'].'&stat='.$person['id_person'].'"
						}
			}';
			$this->send();
		}




		public function InvestorPayment(){
			$this->templateID = 'd-fb39c1c3665a43f8893b70a14b83828f';
			$this->personalizations = '{
					 "to":[
							{
								 "email":"'.$person['main_email'].'"
							}
					 ],
					 "dynamic_template_data":{
							"name":"'.$person['name'].'",
							"lastname":"'.$person['lastname'].'",
							"reset_link":"https://el-lugar.com/cockpit/actions/passwordRecovery/?otp='.$person['salt'].'&stat='.$person['id_person'].'"
						}
			}';
			$this->send();
		}



		public function InvestorPaymentBE($mail_data){
			$mail_data = json_decode(json_encode($mail_data), true);
			$this->templateID = 'd-a2fe443154e04c39b802d65c47874f03';
			$this->personalizations = '{
					 "to":[
							{
								 "email":"'.$mail_data['main_email'].'"
							}
					 ],
					 "dynamic_template_data":{
							"agio": "'.$mail_data['agio'].'",
							"amount": "'.$mail_data['amount'].'",
							"name": "'.$mail_data['name'].'",
							"outstanding": "'.$mail_data['outstanding'].'",
							"purchase_price": "'.$mail_data['purchase_price'].'",
							"share": "'.$mail_data['share'].'",
							"total_paid": "'.$mail_data['total_paid'].'",
							"total_to_pay": "'.$mail_data['total_to_pay'].'",
							"upgrades": "'.$mail_data['upgrades'].'"
						}
			}';
			$this->send();
		}




		//reminder for 24 hour prior to the meeting
		function reminder($person){
			$this->templateID = 'd-95405c8d9d6a4822bb3caaee861bd68f';
			$this->personalizations = '{
					 "to":[
							{
								 "email":"'.$person['main_email'].'"
							}
					 ],
					 "dynamic_template_data":{
							"name":"'.$person['name'].'",
							"lastname":"'.$person['lastname'].'"
						}
			}';
			return $this->send();
		}












		// function in charge to send the request to SendGrid
		public function send(){
			// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, 'https://api.sendgrid.com/v3/mail/send');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, '{
			   "from":{
			      "email":"'.$this->fromEmail.'",
						"name":"'.$this->fromName.'"
			   },
			   "personalizations":[
			      '.$this->personalizations.'
			   ],
			   "template_id":"'.$this->templateID.'"
			};');


			//set the headers to send the CURL request
			$headers = array();
			$headers[] = 'Authorization: Bearer '.$this->apiKey;
			$headers[] = 'Content-Type: application/json';
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$result = curl_exec($ch);
			if (curl_errno($ch)) {
			    $result['error'] = 'Error:' . curl_error($ch);
			}
			curl_close($ch);

			return $result;
		}



}


?>