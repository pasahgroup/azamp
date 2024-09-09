<?php 

class azampay

{   //Options are sandbox and production
  private static $environment = "sandbox";
	private static $appName = "pasah car hiring system";
	private static $clientId = "873ccd3b-e154-4d9a-9d3d-68f28b0baf7a";
	private static $clientSecret = "KzuPLIgpIqMVivZu7DiHI3JiSAx0sQUOWO6foM5ttROFIFWkP5XgGhHzmynBZ33FHyenNCVw2pVLakrFYpu7xuUPaBChHT/DPD4dqlVm4XS5L/J13brswJOLTyzWuAQMe/PY16AhjQ2uA8Wp2CxpjYCgeTiwAnSRs6JnwoQG6RtCNN0q0UTXEosS19hIlngqdlE+km688Q4iuepEY4zTos1MpsQqLFiQZ1OP1ZVmftWdBxiWr86t9Opy2ZEroSN7BOhkvRGROdCsUCMF1LEQjGkBu8Jp62BvQzJwBcSxOKnE0sdXlnLvRzInGED6Mz5HCanwPi2zhZa3RnRbAvQXLIysn1y3/fm4PtkMOgtpiktMlQuQIro/NaPHeLyFFKdqn5MV3NgPWbGiWeT9UFeg07hncPHXlLdajZq7yjLPl7loak1GZqc5x+30vRt06kTTPZTfFeVH4d2s0fHrcJvnimEooOEjSbWlm9OD7fZBt+fYvuzCv6gYpF/prc1LIpWttx6K003ZpeHr1TrqCoENYjR0xho+1v6Lh0Qjg17BrVAIN9hMx1q/q/numQjJrEYbaUXuRuN67HwzmXm/XUdT7h/cZ4/gKM1z7wYiBhh/B4tzKS76I534GeVNzk6FZ1HuXzppcYzEoUc4MB0hJ9x5H6+tzLaTWTAjH4rqW4ooPfo=";

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

