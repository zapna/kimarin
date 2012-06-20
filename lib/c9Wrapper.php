<?php



//    $test = new c9Wrapper();
//    $result = $test->balanceEmail(22,'20717786');
//        var_dump($result);



class c9Wrapper
{
	
/*	
	URL 
 http://api.wire9.com/api.cgi
 
TRANSACTION_ID 
 The transaction ID your system assigned to the CHECK USER BALANCE request. 
 
REQUEST_TYPE 
 This variable must be set to check_user_balance. 
 
CARRIER_ID 
 This variable must contain the carrier ID that has been assigned to you for use with our API. Please contact support@cloud9-mobile.co.uk if you do not have your API carrier ID. 
 
PASSWORD 
 This variable must contain the password that has been assigned to you for use with our API. Please contact support@cloud9-mobile.co.uk if you do not have your API password. 
 
TIMESTAMP 
 This variable is set by your system. It should contain a valid date and time in yyyy-mm-dd HH:mm:ss format. This time stamp is used for the API CDR records. 
 
QUERY 
 This variable must contain the MSISDN or IMSI of the subscriber of who's balance you wish to query. If passing a MSISDN may use the following formats: 07xxx, 447xxx or +447xxx.
 
*/
	public static function getBalance($trasactionId, $msisdn){
	
	/*$recharge_data_file = sfConfig::get('sf_data_dir').'/recharge.txt';
    $recharge = "\r\n inside c9Wrapper get balance\r\n";
	$recharge .= "trasactionId = {$trasactionId} \r\n";
	$recharge .= "msisdn ={$msisdn}\r\n";
	file_put_contents($recharge_data_file, $recharge, FILE_APPEND);*/

		$carrierId='24';
		$password='Denmark';
		$timeStamp = date('Y-m-d H:m:s');

		$data = array(
		      'transaction_id' => $trasactionId,
		      'request_type'=>'check_user_balance',
                      'carrier_id'=>$carrierId,
                      'password'=>$password,
                      'timestamp' => $timeStamp,
			  'query'=>$msisdn
			  );

		
		$queryString = http_build_query($data,'', '&');

		//echo $queryString;
		$res =  file_get_contents('http://api.wire9.com/api.cgi?'.$queryString);	

                
                $xml = self::getXmlFromC9Response($res);
                return self::getParameterFromC9Xml('balance', $xml);
                
                //return $res;
	}
	/*
	URL  http://api.wire9.com/api.cgi

	TRANSACTION_ID: The transaction ID your system assigned to the UPDATE USER BALANCE request.

	REQUEST_TYPE: This variable must be set to update_user_balance.

	CARRIER_ID:  This variable must contain the carrier ID that has been assigned to you for use with our API. Please contact support@cloud9-mobile.co.uk if you do not have your API carrier ID.

	PASSWORD: This variable must contain the password that has been assigned to you for use with our API. Please contact support@cloud9-mobile.co.uk if you do not have your API password.

	TIMESTAMP: This variable is set by your system. It should contain a valid date and time in yyyy-mm-dd HH:mm:ss format. This time stamp is used for the API CDR records.

	QUERY: This variable must contain the MSISDN or IMSI of the subscriber of who's balance you wish to query. If passing a MSISDN may use the following formats: 07xxx, 447xxx or +447xxx.

	TOPUP_AMOUNT: This variable must contain the amount to top the subscribers account up by. The amount must be supplied in 0.00 format. The amount can be a positive value or a negative value. If you supply a negative value then the account balance will be deducted from.
	*/

	
	public static function updateBalance($trasactionId, $imsi, $topupAmount ){
        

	$recharge_data_file = sfConfig::get('sf_data_dir').'/recharge.txt';
    $recharge = "\r\n inside c9Wrapper update balance\r\n";
	$recharge .= "trasactionId = {$trasactionId} \r\n";
	$recharge .= "imsi ={$imsi}\r\n";
	$recharge .= "topup Amount = {$topupAmount} \r\n";
	file_put_contents($recharge_data_file, $recharge, FILE_APPEND);
	


		$newUrl = array();
		$carrierId='24';
		$password='Denmark';
		//$trasactionId='1010';
		//$imsi = '+447924506169';
		//$topupamount = '997.5';
		$url = 'http://api.wire9.com/api.cgi?';
		$requestType = 'update_user_balance';

		$timeStamp = date('Y-m-d H:m:s');
		//echo date('Y-m-d H:m:s');
		//echo '\n';
		//echo $trasactionId;
		//echo '\n';

		$data = array(
		      'transaction_id' => $trasactionId,
		      'request_type'=>'update_user_balance',
              'carrier_id'=>$carrierId,
              'password'=>$password,
              'timestamp' => $timeStamp,
			  'query'=>$imsi,
			  'topup_amount'=>$topupAmount 
			  );

		
		$queryString = http_build_query($data,'', '&');
		$recharge .= "\n\r query built \n\r";
		$recharge .= " c9 query value = {$queryString}";
		file_put_contents($recharge_data_file, $recharge, FILE_APPEND);
	/*	$targetURL = http_build_url($url,
    	array(
        "scheme" => "http",
        "host" => "api.wire9.com",
        "path" => "api.cgi",
        "query" => $queryString),
		HTTP_URL_STRIP_AUTH | HTTP_URL_JOIN_PATH | HTTP_URL_JOIN_QUERY | HTTP_URL_STRIP_FRAGMEN, $newUrl );*/

		//echo $queryString;		
		$new_bal = sfConfig::get('sf_data_dir').'/newbal.txt';
		//$new_bal_value= "updating c9";
		$res =  file_get_contents('http://api.wire9.com/api.cgi?'.$queryString);	
		//$new_bal_value .= "updated c9";
		file_put_contents($new_bal, $res, FILE_APPEND);		

				$xml = self::getXmlFromC9Response($res);
				return self::getParameterFromC9Xml('new_balance', $xml);
		
		

                //return $res;

		
	}
	
	public static function equateBalanceBackup(Customer $customer){
		
		$equate_data_file = sfConfig::get('sf_data_dir').'/equate.txt';
        $equate = "\r\n inside c9Wrapper::equateBalance \r\n";
        file_put_contents($equate_data_file, $equate, FILE_APPEND);


	
            //get fonet balance
	
            $fonetBalance = Fonet::getBalance($customer);

            c9Wrapper::balanceAlert($fonetBalance , $customer->getMobileNumber());
            c9Wrapper::balanceEmail($fonetBalance , $customer->getMobileNumber());

        $call_data_file = sfConfig::get('sf_data_dir').'/calldata.txt';
        $call = "\r\n inside c9Wrapper::equateBalance \r\n";
		$call .="Fonet Balance:  {$fonetBalance} \r\n ";
        file_put_contents($call_data_file, $call, FILE_APPEND);

	}

public static function equateBalance(Customer $customer){
			$equate_data_file = sfConfig::get('sf_data_dir').'/equate.txt';
            $equate = "\r\n inside c9Wrapper::equateBalance ";
            file_put_contents($equate_data_file, $equate, FILE_APPEND);
        //get fonet balance
	
            $fonetBalance = Fonet::getBalance($customer);

        try{
        //get c9 balance in british pounds
        $amt_bpp = c9Wrapper::getBalance('12345', $customer->getC9CustomerNumber());


        //get Bpp to dkk conversion rate
        $conversion_rate = CurrencyConversionPeer::retrieveByPK(1);
        $bppDkk_rate = $conversion_rate->getBppDkk();

        //convert amount from Bpp to Dkk
        $c9Balance = $amt_bpp * $bppDkk_rate;




	$equate_data_file = sfConfig::get('sf_data_dir').'/equate.txt';
        $equate = "\r\n inside c9Wrapper::equateBalance \r\n";
	$equate .= "fonet balance = {$fonetBalance}\r\n";
	$equate .= " amt_bpp ={$amt_bpp}\r\n";
	$equate .= "bppDkk_rate= {$bppDkk_rate} \r\n";
	$equate .= " C9 Balance = {$c9Balance} \r\n";
	file_put_contents($equate_data_file, $equate, FILE_APPEND);



        if($fonetBalance != $c9Balance ){

            $diff = $fonetBalance - $c9Balance;

            //convert $diff to bpp
            $conversion_rate = CurrencyConversionPeer::retrieveByPK(1);
            $dkkBpp_rate = $conversion_rate->getDkkBpp();
            $amt_bpp = number_format($diff / $dkkBpp_rate,4);


			$equate .= " diff = {$diff} \r\n";
			$equate .= " amt_bpp = {$amt_bpp } \r\n";
			$equate .= " dkkBpp_rate = {$dkkBpp_rate} \r\n";




			//update c9 with the diff balance (-ve or +ve)
            c9Wrapper::updateBalance('12345', $customer->getC9CustomerNumber(), $amt_bpp );
       }


			$equate .= "end of equate balance \r\n";
             file_put_contents($equate_data_file, $equate, FILE_APPEND);

        }catch(Exception $e){

            $equate_data_file = sfConfig::get('sf_data_dir').'/equate.txt';
            $equate = "\r\n inside c9Wrapper::equateBalance Exception thrown \r\n".$e->getCode()."\r\n".$e->getMessage();
            file_put_contents($equate_data_file, $equate, FILE_APPEND);
        }

        c9Wrapper::balanceAlert($fonetBalance , $customer->getMobileNumber());
        c9Wrapper::balanceEmail($fonetBalance , $customer->getMobileNumber());

        
      
}//end equate balance backup

public static function balanceAlert($balance, $mobileNo)
  {
      $username= 'zerocall' ;
      $password= 'ok20717786';
      $response_text = NULL;
      
      $balance_data_file = sfConfig::get('sf_data_dir').'/balanceAlert.txt';
      $baltext = "";
      $baltext .= "Mobile No: {$mobileNo} , Balance: {$balance} \r\n";

      file_put_contents($balance_data_file, $baltext, FILE_APPEND);

          if($mobileNo)
          {
            if($balance < 25 && $balance > 10)
            {

               $baltext .= "balance < 25 && balance > 10";
                $data = array(
		      'username' => $username,
                      'password' => $password,
                      'mobile'=>$mobileNo,
                      'message'=>"You balance is below 25 dkk, Please refill your account. LandNCall AB - Support "
			  );
		$queryString = http_build_query($data,'', '&');
		$response_text =  file_get_contents('http://sms.gratisgateway.dk/send.php?'.$queryString);
            }
            else  if($balance< 10 && $balance>0)
            {

               $data = array(
		      'username' => $username,
                      'password' => $password,
                      'mobile'=>$mobileNo,
                      'message'=>"You balance is below 10 dkk, Please refill your account. LandNCall AB - Support"
			  );
		$queryString = http_build_query($data,'', '&');
		$response_text =  file_get_contents('http://sms.gratisgateway.dk/send.php?'.$queryString);
                $baltext .= "balance < 10 && balance > 0";

            }
            else if($balance<= 0)
            {


                    $data = array(
                      'username' => $username,
                      'password' => $password,
                      'mobile'=>$mobileNo,
                      'message'=>"You balance is 0 dkk, Please refill your account. LandNCall AB - Support "
			  );
                    $queryString = http_build_query($data,'', '&');
                    $response_text =  file_get_contents('http://sms.gratisgateway.dk/send.php?'.$queryString);
                    $baltext .= "balance 0";

            }
          }


      $baltext .= $response_text;
      file_put_contents($balance_data_file, $baltext, FILE_APPEND);          

  }

  public static function balanceEmail($balance, $mobileNo)
  {


//      $balance = $request->getParameter('balance');
//      $mobileNo = $request->getParameter('mobile');

      $email_data_file = sfConfig::get('sf_data_dir').'/EmailAlert.txt';
      $email_msg = "";
      $email_msg .= "Mobile No: {$mobileNo} , Balance: {$balance} \r\n";
	  file_put_contents($email_data_file, $email_msg, FILE_APPEND);

      //$fonet=new Fonet();
      //

      $c=new Criteria();
      $c->add(CustomerPeer::MOBILE_NUMBER,$mobileNo);
      $customers=CustomerPeer::doSelect($c);
      $recepient_name='';
      $recepient_email='';
      foreach($customers as $customer)
      {
        $recepient_name=$customer->getFirstName().' '.$customer->getLastName();
        $recepient_email=$customer->getEmail();
      }


      //$recepient_name=
      //foreach($customers as $customer)
      //{

     file_put_contents($email_data_file, $email_msg, FILE_APPEND);
     $message_body= "reset";
          if($mobileNo)
          {
            if($balance < 25 && $balance > 10)
            {
                $email_msg .= "\r\n balance < 25 && balance > 10";
                //echo 'mail sent to you';
               $subject= 'Test Email: Balance Email ' ;
               $message_body= "Test Email:  Your balance is below 25dkk , please refill otherwise your account will be closed. - LandNCall AB Support Company Contact Info";
			   if($recepient_email!=''){
				   $email = new EmailQueue($subject, $message_body, $recepient_name, $recepient_email);
				  // echo $email;
				  // $email->save();
			   }
            }
            else  if($balance< 10 && $balance>0)
            {

               $email_msg .= "\r\n balance < 10 && balance > 0";
               $subject= 'Test Email: Balance Email ' ;
               $message_body= "Test Email:  Your balance is below 25dkk , please refill otherwise your account will be closed. - LandNCall AB Support Company Contact Info";
			    if($recepient_email!=''){
				   $email = new EmailQueue($subject, $message_body, $recepient_name, $recepient_email);
				 //  $email->save();
				}
            }
            else if($balance<= 0)
            {
                $email_msg .= "\r\n balance < 10 && balance > 0";
                $subject= 'Test Email: Balance Email ' ;
                $message_body= "Test Email:  Your balance is below 25dkk , please refill otherwise your account will be closed. - LandNCall AB Support Company Contact Info";
				if($recepient_email!=''){
					$email = new EmailQueue($subject, $message_body, $recepient_name, $recepient_email);
					//$email->save();
				}
            }
          }


      $email_msg .= $message_body;
      $email_msg .= "\r\n Email Sent";
      file_put_contents($email_data_file, $email_msg, FILE_APPEND);
      

  }


	public static function getParameterFromC9Xml($parameter_name, $xml)
	{
		$q = '//'.$parameter_name;

		$xpath = new DOMXPath($xml);

		$parameter = $xpath->query($q)->item(0);

		if ($parameter)
			return $parameter->nodeValue;

	}

	public static function getXmlFromC9Response($response)
	{
		$xmlDoc = new DOMDocument();

		if (@$xmlDoc->loadHTML($response)==true)
		{
			$xpath = new DOMXPath($xmlDoc);


			$q = '//wire9_data';

			$xml = (string)simplexml_import_dom($xpath->query($q)->item(0))
					->asXML();

			$xmlDoc->loadXML($xml);

			return $xmlDoc;
		}
	}
	
	
	
	
}

?>