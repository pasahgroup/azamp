<?php 

class azampay

{   //Options are sandbox and production
  private static $environment = "sandbox";
	private static $appName = "pasah car hiring system";
	private static $clientId = "873ccd3b-e154-4d9a-9d3d-68f28b0baf7a";
	private static $clientSecret = "SiA/tq9LIqYDsAwq+5PbIjKaNpOPFbUAv5Ip0oLicSJrfYOEtvsQ+jiY1vmQAcoUxZJHlNP226pTAAy+M802NmQ9Grj7qcyjxl+BBGOdhmhQ32DFz4bQb7MRiqOQCa1/5FJegoghoZBeRFerWxvrobUq1VXtHPKnv9ms2QYjQUPUkof2wprBuibk8BrZZCLByw5p2X6gKrCkAx9y2XmBPCy2JWe8xUl4F0AIGw6y9uDD/D8al0o502B+0ckd6dU1nGzDpx6ylD/ZNIBLnPbZ5NPoWB5SULdZU+I4CZLTh5hjpmquO5xaH7gC5aiNZpXewJXolD0mH2IN1YP1LvURMEXi/L1w/9adgt2PARq81p5Qzw7S5BWeud+ry9ucJImHUpxodf44/4k1gqcMCZYLHKocfF4sq2Eco765I0h0HRx56sYlgMPXhhYFfV6UEdSz1hb4wBEvkFK9jewlLj8c02BQmDj/QLsgblEz+ggxfC3ris8smGVINkGxheIN9oLTTrim/fePff6agG7EPV5Abpd9OKgWsxjqT/Cz5WpdUroPivfTYyrFKxK8LTnqiXnxh1nBMfj3Lmx5F0dnZk3A7xmwPobGemATJrjpFd4Ur9LTpF5t7Fr5S6Njz0oyedlnsbLIeFD8+OSjm6NkR5TJMLAOnucvcye/JnS64uKtzXU=";

	//Environment URLS
	public static function envUrls()
	{
		$auth_url = "https://authenticator-sandbox.azampay.co.tz";
		$checkout_url = "https://sandbox.azampay.co.tz";
        
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
                  "property1": 878346737777,
                  "property2": 878346737777
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
