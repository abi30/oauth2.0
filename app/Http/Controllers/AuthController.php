<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Client;

// ----
use kamermans\OAuth2\GrantType\ClientCredentials;
use kamermans\OAuth2\GrantType\AuthorizationCode;
use kamermans\OAuth2\OAuth2Middleware;
use GuzzleHttp\HandlerStack;
// ----

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function loginold(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        if (empty($email) || empty($password)) {
            return response()->json(['status' => 'error', 'message' => 'You must fill all fields']);
        }
        $client = new Client();
        try {
            return $client->post(config('service.passport.login_endpoint'), [
                "form_params" => [
                    "client_secret" => config('service.passport.client_secret'),
                    "grant_type" => "password",
                    "client_id" => config('service.passport.client_id'),
                    "username" => $request->email,
                    "password" => $request->password
                ]
            ]);
        } catch (BadResponseException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }





    public function login1()
    {
        $re_client = new Client(['base_uri' => config('service.ameise.login_endpoint')]);

        $reauth_config = [
            "client_id" => config('service.ameise.client_id'),
            "client_secret" => config('service.ameise.client_secret'),
            "redirect_uri" => "https://connect.arisecur.com/oauth/callback",
            "scope" => config('service.ameise.scope'),
            "state" => time(), // optional
            // "grant_type" => "client_credenticals",
        ];

        $grant_type = new ClientCredentials($re_client, $reauth_config);
        // $grant_type = new AuthorizationCode($re_client, $reauth_config);
        $oauth = new OAuth2Middleware($grant_type);

        $stack = HandlerStack::create();
        $stack->push($oauth);


        $client = new Client([
            'handler' => $stack,
            'auth'    => 'oauth',
        ]);


        $endpoint_url = 'https://www.maklerinfo.biz/maklerportal';
        $response = $client->get($endpoint_url);

        print_r($response);
        exit;
        // return json_decode((string) $response->getBody(), true);
        print_r(json_decode((string) $response->getBody(), true)['access_token']);
        // ['access_token'];


        // try {
        //     $response =  $client->post(config('service.ameise.login_endpoint'), [
        //         "form_params" => [
        //             "client_secret" => config('service.ameise.client_secret'),
        //             "grant_type" => "client_credenticals",
        //             "redirect_uri" => "https://connect.arisecur.com/oauth/callback",
        //             "client_id" => config('service.ameise.client_id'),
        //             "scope" => config('service.ameise.scope'),
        //             "state" => "abc123456"

        //         ]
        //         // client_credenticals
        //     ]);
        //     return json_decode((string) $response->getBody(), true)['access_token'];
        // } catch (BadResponseException $e) {
        //     return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        // }


    }
    public function login()
    {
        $access_token = $this->getAccessToken();
        return response()->json(json_decode($access_token));
    }

    private function getAccessToken()
    {
        //  $token_url, $client_id, $client_secret;
        $token_url = "https://auth.dionera.com/oauth2/token";
        $client_id = "805b2cf4-f4c7-441c-a45c-17f6a719eeed";
        $client_secret = "l7K3xVl_IeK7xnxBKihO04P8LR5saySx4tx2_kCI40JMxA0e1sFHFbf1VzrqAIcu3ZLdOjNaVKl16Ujt";
        // client_credentials
        $content = "grant_type=client_credentials&scope=ameise/mitarbeiterwebservice";
        $authorization = base64_encode("$client_id:$client_secret");

        $header = array("Authorization: Basic {$authorization}", "Content-Type:application/x-www-form-urlencoded");
        // x-www-form-urlencoded
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL            => $token_url,
            CURLOPT_HTTPHEADER     => $header,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $content,
        ));
        $response = curl_exec($curl);

        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        }

        return $response;
    }


    public function login32(Request $request)
    {

        // ['base_uri' => 'https://www.maklerinfo.biz']
        // config('service.ameise.login_endpoint')
        $client = new Client(['base_uri' => 'https://auth.dionera.com/oauth2/auth']);

        try {
            $response = $client->post('https://auth.dionera.com/oauth2/token', [
                'form_params' => [
                    'grant_type' => 'cleint_credentials',
                    'client_id' => '805b2cf4-f4c7-441c-a45c-17f6a719eeed',
                    'client_secret' => 'l7K3xVl_IeK7xnxBKihO04P8LR5saySx4tx2_kCI40JMxA0e1sFHFbf1VzrqAIcu3ZLdOjNaVKl16Ujt',
                    'redirect_uri' => 'https://connect.arisecur.com/oauth/callback',
                    "state" => "abc123456",
                    'code' => $request->code,
                    "scope" => config('service.ameise.scope'),
                ]
            ]);
            return json_decode((string) $response->getBody(), true)['access_token'];




            // $response =  $client->post('https://auth.dionera.com/oauth2/token', [
            //     "form_params" => [
            //         "client_id" => config('service.ameise.client_id'),
            //         "client_secret" => config('service.ameise.client_secret'),
            //         "grant_type" => "cleint_credentials",
            //         "redirect_uri" => "https://connect.arisecur.com/oauth/callback",
            //         "scope" => config('service.ameise.scope'),

            //         "state" => "abc123456",
            //     ]
            //     // client_credenticals
            // ]);
            // return json_decode((string) $response->getBody(), true);



        } catch (BadResponseException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}