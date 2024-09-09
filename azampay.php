<?php 

class azampay

{   //Options are sandbox and production
  private static $environment = "sandbox";
	private static $appName = "pasah car hiring system";
	private static $clientId = "873ccd3b-e154-4d9a-9d3d-68f28b0baf7a";
	private static $clientSecret = "Rr1FZCew2ulyScaFW6Ji9BWsSCy/VtC+TVm8SZVYgRzM+hS4nPIDpI2usKCklrt2vVRZ7UEUSabYjmpx5T+VDeNHnXkvBYnduHkEW39pYP1q1sG7rD+PLdtljKKCS9iMwhIgjDCWhiAB6mqMb0mxUq+aZrZIO9rjEaFNZGqOQQLiE9SldwgKOXmnDD6eUvYu4avnqYS3jjmOtxd8zvMpbwxbQkhDWmWoJD0x7ZtDgg9wbE6ZGv23U6s1hEwD/UBAQOoNwP8o54xG9KxP4eCafEcbBUhDHdPekcOITjGF9CTl7MZbI4FSoVj6MPgxmcG7+aK4zNh9b7yvZt+c33uW8vFpA6oI1ffs4OxRh+f9gz3WG56a+nLl5u2JnfEPw1EwahiYtq8y/zXk6KkJPFw+aU8ue94Axu9Ab4XiD/OgmhRTEDims4nIEe14x1G4HdzLX1BxHniVe3KwPavEU27c2ITXrtmZStzXrgGJVNqpL2TK4sRW6QHBDRdv55T9x/A5283hZDN9QG5ZR3U1u+juQJ8G8AwoFgp9twwSIGko8T6Xd5F/VpFm9DpkUmvSIw/MgAyJyT1LLdtW5z4E5PhlPlJH1eRdTvDb7h/9O1HDelQFv5iSQXAxY5ry2/ZzGl0RDAPdEoHA4rZojGgGRbo4knRk6t5EhDSxRjBEW7jl09A=";

	//Environment URLS
	public static function envUrls()
	{
		$auth_url = "https://authenticator-sandbox.azampay.co.tz";
		$checkout_url = "https://sandbox.azampay.co.tz";
        
        //Base URLs for production
		if (azampay::$environment == "production") {
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
              "appName": "'.azampay::$appName.'",
              "clientId": "'.azampay::$clientId.'",
              "clientSecret": "'.azampay::$clientSecret.'"
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
                CURLOPT_URL => ''.azampay::envUrls()["checkout_url"].'/azampay/mno/checkout',
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
                  "property1": 878346737777,
                  "property2": 878346737777
                },
                "amount": "'.$amount.'",
                "currency": "'.$currency.'",
                "externalId": "'.$externalId.'",
                "provider": "'.$provider.'"
              }',
                CURLOPT_HTTPHEADER => array(
                  'Authorization: Bearer '.azampay::authtoken()->data->accessToken.'',
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

