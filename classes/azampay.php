<?php 

class azampay

{   //Options are sandbox and production
  private static $environment = "sandbox";
	private static $appName = "pasah car hiring system";
	private static $clientId = "82d1927f-f929-40a3-a953-799990ea0ae4";
	private static $clientSecret = "JmS/TD42lE6UxKI9dzCm4gXr5Bump91xfT0LnTAlLF1iB1YydSqpHX/mJ82NokE2Cv/Y20jRsp8L2xxn889sM5a3WUNQacOVXZ3S73i726CmNITh6BIfRE5MPVT7/rq2a3NALMjSA/bZs9uiSIYIYQ7D8C5raZrLsq2enlWtKr2OsEGFEpRJPiP8uAlrTrp+W+0fqW2eyTBzVLM+po5p0S7D5bB6MAp0+sBCs7t/ByrFgJu5VnJ8EtcJrVaYGaRoHAJstaEMj4XGe9PiTGRHT8uIHPRlDHUUcTTQ6rKmavrt7ZwazwuuZBWO0Jrw4o9FxHs4oVn7eosGshM/3Kfno/KkD64W7Ai5KrVbQBTicL4HRO4+gk4mOWpsDT/OcK1amqaYNXexfN1RQ6BTvayJoFW2KB78dO9C2Wu/MFTY2E2SVpsiZlohY428rLym3PUDhxEIYjasiKBKvifzvusRDASqD9DKN0W+efBCc5M/3mD5obV2vNRtFugfQ47OdJ4vzbC8WaDtYopdrrqoaC/55092VK0go+0OCTDStxdVDObF1vq877/murAjPTra2Rt89U1YSnp8SKkFe0K4hS4hYJxg7mADygBsv/6tkh/2T1I3CUOP0EtRht20dv2jawjTxtO4LpkC7Qxp7F5KWsPKXiC9shCSF8RWtemSkXuUoEc=";

	//Environment URLS
	public static function envUrls()
	{
		$auth_url = "https://authenticator-sandbox.azampay.co.tz";
		$checkout_url = "https://sandbox.azampay.co.tz";
        
    //       $auth_url = "https://authenticator.azampay.co.tz";
    // $checkout_url = "https://checkout.azampay.co.tz";
    
        //Base URLs for production
		if (AzamPay::$environment == "production") {
			$auth_url = "https://authenticator.azampay.co.tz";
		    $checkout_url = "https://checkout.azampay.co.tz";
			
		}

		return ["auth_url"=>$auth_url, "checkout_url"=>$checkout_url ];

	}
	//Authorisation token
	public static function authtoken()
	{

            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://authenticator-sandbox.azampay.co.tz/AppRegistration/GenerateToken',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>'{
              "appName": "'.AzamPay::$appName.'",
              "clientId": "'.AzamPay::$clientId.'",
              "clientSecret": "'.AzamPay::$clientSecret.'"
              }',
                CURLOPT_HTTPHEADER => array(
                  'Content-Type: application/json'
                ),
              ));

              $response = curl_exec($curl);
              $result = json_decode($response);

              curl_close($curl);
              return $result;

	}

	//MNO checkout
	public static function mnocheckout($accountNumber, $amount,  $currency,$provider)
	{         //UUID ID generator
             $externalId = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
		      $curl = curl_init();
              curl_setopt_array($curl, array(
                CURLOPT_URL => ''.AzamPay::envUrls()["checkout_url"].'/azampay/mno/checkout',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                "accountNumber": "'.$accountNumber.'",
                "additionalProperties": {
                  "property1": 0764706227,
                  "property2": 0764706227
                },
                "amount": "'.$amount.'",
                "currency": "'.$currency.'",
                "externalId": "'.$externalId.'",
                "provider": "'.$provider.'"
              }',
                CURLOPT_HTTPHEADER => array(
                  'Authorization: Bearer '.AzamPay::authtoken()->data->accessToken.'',
                  'Content-Type: application/json'
                ),
              ));

              $response = curl_exec($curl);
              $result = json_decode($response);
              curl_close($curl);
              
              //Return checkout link or json data
               return array($result, $externalId);
        }
	}
