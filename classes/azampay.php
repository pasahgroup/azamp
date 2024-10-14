<?php 

class azampay

{   //Options are sandbox and production
  private static $environment = "sandbox";
	private static $appName = "pasah car hiring system";
	private static $clientId = "873ccd3b-e154-4d9a-9d3d-68f28b0baf7a";
	private static $clientSecret = "V4ttBnz9yjvFPUQkMXWYLc4YUoRqQSD9vFSPCIhCIyN5gFsn95tG8GMcgWt4ZVIy0CyzKtXUcxlFHqRiyc6Kv7TxcJDnfe7upm06BsGGrAtxHXOF7HiUQg174rdmM+atKAkemg9U0R/FMHOXwkLx2jKFNqvfgWLdnQRfB6dQKTkGqq3d5IZGIM5KD/cDYQwViKeSRs31PupCz5rfb/IoiBWO5Se2WNUntSTDjuMWu9njwUoHXdjb1we3fQQvW+wyd+C4kv8JzKNoeRboz17frZIHXEZ97k78kdX5qqOwGr4WAAov0nkXiCgLPu1JoquYd5x7S9KFEIj0IViBgNToOLbY1oGU6MXnIz6v19spraB1ZDLS7rtDlEtfZ6riYsPDPZI4sM3tvaBN1w4Y7+rtWypygrsDd7wr7S8BtSxBmpzUQfzMd98EztTbJU/HdFb1lXK3HdeY9Prr2pwfqPRftralZTGHGkp032QlNI2FLd+KDlB2Tuw/gmps1sbhhVRHQZeFH9UWPzXQfPTG4eHqvd9DkvrSK6p7E7+AuVHtKEbIkaqUrYcICgx8y3biqvrfs2Tn5elcWGbILUwlXL076ruNL5eZmcFiYtbK/R+gBgihPpHGpLaiAA5gzCwv9K9a16E4Nszz691inzwqBHViWRGV8xO4/4DMbRcV3E+3H0Y=";

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
              CURLOPT_URL => 'https://authenticator.azampay.co.tz/AppRegistration/GenerateToken',
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
