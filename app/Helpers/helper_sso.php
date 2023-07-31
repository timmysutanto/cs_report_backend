<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use App\Jobs\SendEmailJob;

class HelperSSO 
{
    public static function verifyTokenHeader($user, $token)
    {
        $url = getenv('SSO_BACKEND_URL') . "/verifyTokenHeader";
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        ])->post(
            $url,
            [
                "USER" => $user,
            ]
        );

        return $response;
    }


    public static function getUserProfileSSO($user_email)
    {
        $url = getenv('SSO_IDENTITY_PROVIDER_URL') . "/identity/getUserProfile";
        $response = Http::acceptJson()->get(
            $url,
            [
                "EMAIL" => $user_email
            ]
        );

        return $response["data"];
    }


    // get company data 
    public static function getCompanyById($id)
    {
        $url = getenv('SSO_IDENTITY_PROVIDER_URL') . "/company/getById";
        $response = Http::acceptJson()->get(
            $url,
            [
                "COMPANY_ID" => $id
            ]
        );

        return $response["data"];
    }
    //  end 

    // get company data 
    public static function getDepartmentById($id)
    {
        $url = getenv('SSO_IDENTITY_PROVIDER_URL') . "/directorate/getById";
        $response = Http::acceptJson()->get(
            $url,
            [
                "DIRECTORATE_ID" => $id
            ]
        );

        return $response["data"];
    }
    //  end 

    // send Email Dispatch
    public static function sendEmailDispatch($email, $username, $urNumber, ...$others){
        // var_dump($others[1]);
        // return $others[1];
        $details['email'] = $email;
        $details['username'] = $username;
        $details['ur_number'] = $urNumber;
        $details['user'] = $others[0]["user"];
        $details['created'] = $others[0]["created"]; 
        $details['priority'] = $others[0]["priority"]; 
        $details['description'] = $others[0]["description"];
        $details['text_one'] = $others[1]["text_one"]; 
        $details['text_two'] = $others[1]["text_two"];
        $details['subjects'] = $others[0]["subjects"];
        dispatch(new sendEmailJob($details));
    }
    // end

      // get username from sso
    public static function getUsernameFromSSO($username){ 
        $url = getenv('SSO_IDENTITY_PROVIDER_URL') . "/user/getByUsername";
        $response = Http::acceptJson()->get(
            $url,
            [
                "USERNAME" => $username
            ]
        );  


        if($response->getStatusCode() != 200){ 
            $format = [
                "code" => 401,
                "data" => [
                    "email" => "Not Found"
                ]
            ];
        }else { 
            $format = [
                "code" => $response->getStatusCode(),
                "data" => $response["data"]
            ];
        }

        return $format;

    } 
    // end 


    // get email by division and branch 
    public static function getEmailByDivisionBranch($divisionId, $branchId){ 
        $url = getenv('SSO_IDENTITY_PROVIDER_URL') . "/user/getEmailByDepartmentAndLocation";
        $response = Http::acceptJson()->get(
            $url,
            [
                "DIVISION_ID" => $divisionId,
                "LOCATION_ID" => $branchId
            ]
        );  


        if($response->getStatusCode() != 200){ 
            $format = [
                "code" => 401,
                "data" => [
                    "email" => "Not Found"
                ]
            ];
        }else { 
            $format = [
                "code" => $response->getStatusCode(),
                "data" => $response["data"]
            ];
        }

        return $format;

    } 
}
