<?php

namespace App\Classes;

use App\Classes\DBConnection;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Temas
{

    public function __construct()
    {
    }

    public function writeInfoLog($proc, $info)
    {
        Log::info($proc . ":" . $info);
    }

    public function writeErrorLog($proc, $error)
    {
        Log::error($proc . ":" . $error);
    }

    public function writeDebugLog($proc, $debug)
    {
        Log::debug($proc . ":" . $debug);
    }

    public function generateHMAC(Request $request)
    {
        try {
            $auth = explode(" ", $request->header('Authorization'));
            $urlLength =  Str::length(url('/'));
            $relativeUrl = substr($request->fullUrl(), $urlLength);

            $secret = DB::connection('mysql')->table('oauth_clients')->where('api_key', $request->header('XTemasKey'))->pluck('api_secret');
            //echo $relativeUrl;

            $stringToSign = $request->method() . ':' . $relativeUrl . ':' . $auth[1] . ':' . strtolower(hash('sha256', $request->getContent())) . ':' . $request->header('XTemasTimestamp');
            //echo $stringToSign;

            $hash = hash_hmac("sha256", $stringToSign, $secret[0]);
            //echo $hash; exit;

            return ($hash);
        } catch (Exception $e) {
            $this->writeErrorLog("generateHMAC", $e->getMessage());
        }
    }

    private function getAccessToken()
    {
        try {
            $client = new Client();
            $response = $client->request('POST',  getenv("OAUTH_SERVER_URL") . '/api/oauth/token', [
                'json' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => getenv("CLIENT_ID"),
                    'client_secret' => getenv("CLIENT_SECRET")
                ]
            ]);
            $body = $response->getBody();
            $body_array = (json_decode($body));
            $accessToken = $body_array->access_token;

            return $accessToken;
        } catch (Exception $e) {
            $this->writeErrorLog("getAccessToken", $e->getMessage());
        }
    }

    public function sendRequest(string $HTTPMethod, string $domainURL, string $relativeURL, string $requestBody)
    {
        try {
            $this->writeInfoLog("sendRequest", "STARTED => HTTPMethod:" . $HTTPMethod . ";domainURL:" . $domainURL . ";relativeURL:" . $relativeURL . ";requestBody:" . $requestBody);
            $accessToken = $this->getAccessToken();

            date_default_timezone_set("Asia/Jakarta");
            $timeStamp = date(DATE_ISO8601);
            $stringToSign = $HTTPMethod . ":" . $relativeURL . ":" . $accessToken . ":" . strtolower(hash('sha256', $requestBody)) . ":" . $timeStamp;

            $secret = DB::connection('mysql')->table('oauth_clients')->where('api_key', getenv("API_KEY"))->pluck('api_secret');

            $hash = hash_hmac("sha256", $stringToSign, $secret[0]);
            // echo($hash);

            $client = new Client([
                'headers' => [
                    'XTemasKey' => getenv("API_KEY"),
                    'XTemasTimestamp' => $timeStamp,
                    'XTemasSignature' => $hash,
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json'
                ],
                // 'json' => [
                //     'grant_type' => 'client_credentials',
                //     'client_id' => getenv("CLIENT_ID"),
                //     'client_secret' => getenv("CLIENT_SECRET")
                // ]
                'json' => json_decode($requestBody)
            ]);

            // var_dump($HTTPMethod);
            // var_dump($domainURL);
            // var_dump($relativeURL);

            $response = $client->request($HTTPMethod, $domainURL . $relativeURL);
            $body = $response->getBody();
            $body_array = (json_decode($body));
            return $body_array;
        } catch (Exception $e) {
            $this->writeErrorLog("sendRequest", $e->getMessage());
        }
    }

    function verifyOAuthToken(Request $request)
    {
        try {
            $this->writeInfoLog("verifyOAuthToken", $request);
            $auth = explode(" ", $request->header('Authorization'));
            $urlLength =  Str::length(url('/'));
            $relativeUrl = substr($request->fullUrl(), $urlLength);

            $secret = DB::connection('mysql')->table('oauth_clients')->where('api_key', $request->header('XTemasKey'))->pluck('api_secret');
            //echo $relativeUrl;

            $stringToSign = $request->method() . ':' . $relativeUrl . ':' . $auth[1] . ':' . strtolower(hash('sha256', $request->getContent())) . ':' . $request->header('XTemasTimestamp');
            // var_dump($stringToSign);


            $hash = hash_hmac("sha256", $stringToSign, $secret[0]);
            // var_dump($hash);
            //echo $hash; exit;

            if ($hash == $request->header('XTemasSignature')) {
                $this->writeInfoLog("verifyOAuthToken", "Verification Succeed");
            } else {
                $this->writeInfoLog("verifyOAuthToken", "Verification Failed");
            }
            return ($hash == $request->header('XTemasSignature'));
        } catch (Exception $e) {
            $this->writeErrorLog("verifyOAuthToken", $e->getMessage());
        }
    }

    function SSOLogin($email, $password)
    {
        try {
            $body = "{\"USER\" : \"" . $email . "\", \"PASS\" : \"" . $password . "\" }";
            // var_dump($body);
            $response = $this->sendRequest('GET', getenv("SSO_SERVER_URL"), '/login', $body);
            // STORE TOKEN IN TEMP FILE
            // if (!file_exists(sys_get_temp_dir())) {
            //     mkdir(sys_get_temp_dir(), 0777, true);
            // }
            // file_put_contents(sys_get_temp_dir() . '\\tms.tmp', bzcompress(json_encode($response), 9));
            // var_dump($response);
            return $response->data->token;
        } catch (Exception $e) {
            $this->writeErrorLog("SSOLogin", $e->getMessage());
        }
    }

    function getLocalSSOToken()
    {
        try {
            if (file_exists(sys_get_temp_dir() . '\\tms.tmp')) {
                $tempData = file_get_contents(sys_get_temp_dir() . '\\tms.tmp');
                $tempData = json_decode(bzdecompress($tempData));
                return $tempData->data->token;
            } else {
                return null;
            }
        } catch (Exception $e) {
            $this->writeErrorLog("getLocalSSOToken", $e->getMessage());
        }
    }

    function produceKafkaMessage($topic, $key, $message)
    {
        //If you need to produce exactly once and want to keep the original produce order, uncomment the line below
        //$conf->set('enable.idempotence', 'true');
        $producer = new \RdKafka\Producer();
        $producer->setLogLevel(LOG_DEBUG);

        if ($producer->addBrokers(getenv('KAFKA_BROKER_URL')) < 1) {
            echo "Failed adding brokers\n";
            exit;
        }

        $topic = $producer->newTopic($topic);
        date_default_timezone_set('Asia/Jakarta');
        $date = date("Y-m-d\TH:i:sO");
        // @dd($producer);
        $data = array(
            // 'source' => $key,
            'timestamp' => $date,
            'data' => $message
        );

        $topic->produce(RD_KAFKA_PARTITION_UA, 0, json_encode($data));
        $producer->poll(0);

        $result = $producer->flush(10000);
        if (RD_KAFKA_RESP_ERR_NO_ERROR === $result) {
            // continue;
        }
        if (RD_KAFKA_RESP_ERR_NO_ERROR !== $result) {
            // @dd($result);
            throw new \RuntimeException('Was unable to flush, messages might be lost!');
        }

        return json_encode($data);
    }
}
