<?php 
namespace App\Helpers;

use GuzzleHttp\Client;

/**
 * ZoomHelper
 * https://artisansweb.net/how-to-create-a-meeting-on-zoom-using-zoom-api-and-php/
 * 
 * @package MedicsBD
 * @author Saiful Alam <hi@msar.me>
 * @version 1.0.0
 */
class ZoomHelper
{
    protected $config;
    protected $redirect;
    protected $client;
    function __construct()
    {
        $this->redirect = route('admin.video.zoom.callback');
        $this->config = [
            'client_id' => config('services.zoom.client_id'),
            'client_secret' => config('services.zoom.secret_secret')
        ];
        $this->authClient = new Client(['base_uri' => 'https://zoom.us']);
        $this->apiClient = new Client(['base_uri' => 'https://api.zoom.us']);
    }

    public function authorize()
    {
        // https://zoom.us/oauth/authorize?response_type=code&client_id=clPiqXqMQJ2Q2wqKFD1OAw&redirect_uri=http%3A%2F%2Flocalhost%3A8000%2Fadmin%2Fvideo%2Fzoom%2Fcallback

        return $url = "https://zoom.us/oauth/authorize?response_type=code&client_id=".$this->config['client_id']."&redirect_uri=".$this->redirect;
    }

    public function callback($code)
    {
        try {
            $response = $this->authClient->request('POST', '/oauth/token', [
                "headers" => [
                    "Authorization" => "Basic ". base64_encode($this->config['client_id'].':'.$this->config['client_secret'])
                ],
                'form_params' => [
                    "grant_type" => "authorization_code",
                    "code" => $code,
                    "redirect_uri" => $this->redirect
                ],
            ]);
         
            $token = json_decode($response->getBody()->getContents(), true);
            return $token;
        } catch(Exception $e) {
            //
        }
        return false;
    }
//https://marketplace.zoom.us/docs/guides/auth/oauth#register-your-app
    public function me($access_token)
    {
        try {
            $response = $this->authClient->request('GET', 'https://api.zoom.us/v2/users/me', [
                "headers" => [
                    "Authorization" => "Bearer {$access_token}"
                ],
            ]);
        } catch (Exception $e) {
            
        }
    }

    function create_meeting() {
        $client = new Client(['base_uri' => 'https://api.zoom.us']);
     
        $db = new DB();
        $arr_token = $db->get_access_token();
        $accessToken = $arr_token->access_token;
     
        try {
            $response = $client->request('POST', '/v2/users/me/meetings', [
                "headers" => [
                    "Authorization" => "Bearer $accessToken"
                ],
                'json' => [
                    "topic" => "Let's learn Laravel",
                    "type" => 2,
                    "start_time" => "2020-05-05T20:30:00",
                    "duration" => "30", // 30 mins
                    "password" => "123456"
                ],
            ]);
     
            $data = json_decode($response->getBody());
            echo "Join URL: ". $data->join_url;
            echo "<br>";
            echo "Meeting Password: ". $data->password;
     
        } catch(Exception $e) {
            if( 401 == $e->getCode() ) {
                $refresh_token = $db->get_refersh_token();
     
                $client = new GuzzleHttp\Client(['base_uri' => 'https://zoom.us']);
                $response = $client->request('POST', '/oauth/token', [
                    "headers" => [
                        "Authorization" => "Basic ". base64_encode(CLIENT_ID.':'.CLIENT_SECRET)
                    ],
                    'form_params' => [
                        "grant_type" => "refresh_token",
                        "refresh_token" => $refresh_token
                    ],
                ]);
                $db->update_access_token($response->getBody());
     
                create_meeting();
            } else {
                echo $e->getMessage();
            }
        }
    }

    public function delete()
    {
        $client = new GuzzleHttp\Client(['base_uri' => 'https://api.zoom.us']);
         
        $db = new DB();
        $arr_token = $db->get_access_token();
        $accessToken = $arr_token->access_token;
         
        $response = $client->request('DELETE', '/v2/meetings/{meeting_id}', [
            "headers" => [
                "Authorization" => "Bearer $accessToken"
            ]
        ]);
    }

    public function refreshToken($credentials = [])
    {
        try {
            $response = $this->authClient->request('POST', '/oauth/token', [
                "headers" => [
                    "Authorization" => "Basic ". base64_encode($this->config['client_id'].':'.$this->config['client_secret'])
                ],
                'form_params' => [
                    "grant_type" => "refresh_token",
                    "refresh_token" => $credentials['refresh_token'],
                ],
            ]);
        } catch (Exception $e) {
            
        }
    }
}
