<?php

namespace App\Http\Controllers;

use App\Helpers\HelperSSO;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Support\Carbon;
use App\Classes\Temas;
use App\Models\Cases;
use App\Models\CategoryTicketCase;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponser;
//use Predis\Client;
use GuzzleHttp\Client;
use GuzzleHttp\Client as GuzzleClient;

class PlanActualController extends Controller
{
    use ApiResponser;

    private function replaceAllNull($str)
    {
        return (str_replace("'null'", "null", $str));
    }

    public function getAllPlanActual(Request $request)
    {
        try{
        // verify token sso
        $verify = HelperSSO::verifyTokenHeader($request->user, Controller::getBearerToken());

        if (isset($verify['code'])) {
            return response()->json(json_decode($verify), 400);
        }

        $queryString = "SELECT voy_number, etd, eta, etb, ta, td, tb
        FROM tms_voyage_plan_actual;";
        $all_voyage_data = app('db')->select($queryString);

        return $this->successResponse($all_voyage_data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function insertPlanAndActual(Request $request)
    {
        $this->validate($request, [
            'user' => 'required',
            'voy_number' => 'required',
         
        ], [
            'user.required' => 'user Harus diisi',
            'voy_number.required' => 'voy_number Harus diisi',
            
        ]);

        // verify token sso
        // $verify = HelperSSO::verifyTokenHeader($request->user, Controller::getBearerToken());

        // if (isset($verify['code'])) {
        //     return response()->json(json_decode($verify), 400);
        // }

        $user = $request->input('user');
        $voy_number = $request->input('voy_number');
        $etd = $request->input('etd');
        $eta = $request->input('eta');
        $etb = $request->input('etb');
        $ta = $request->input('ta');
        $td = $request->input('td');
        $tb = $request->input('tb');

        $insert = '';
        $column = '';
        $update = '';

        if (!is_null($voy_number)) {
            $column .= "voy_number,";
            $insert .= "'" . $voy_number . "',";
        }
        if (!is_null($etd)) {
            $column .= "etd,";
            $insert .= "'" . $etd . "',";
            $update .= "etd = '" . $etd . "',";
        }
        
        if (!is_null($eta)) {
            $column .= "eta,";
            $insert .= "'" . $eta . "',";
            $update .= "eta = '" . $eta . "',";
        }
        if (!is_null($etb)) {
            $column .= "etb,";
            $insert .= "'" . $etb . "',";
            $update .= "etb = '" . $etb . "',";
        }
        if (!is_null($ta)) {
            $column .= "ta,";
            $insert .= "'" . $ta . "',";
            $update .= "ta = case
                        when tms_voyage_plan_actual.ta < '" . $ta . "' then tms_voyage_plan_actual.ta
                        else '" . $ta . "'
                        end ,";
        }
        if (!is_null($td)) {
            $column .= "td,";
            $insert .= "'" . $td . "',";
            $update .= "td = '" . $td . "',";
        }
        if (!is_null($tb)) {
            $column .= "tb,";
            $insert .= "'" . $tb . "',";
            $update .= "tb = case
                            when tms_voyage_plan_actual.tb < '" . $tb . "' then tms_voyage_plan_actual.tb
                            else '" . $tb . "'
                            end ,";
        }
        


        //$insert .= "('" . $idVessel . "','" . $tank["tank_name"] . "','" . $tank["tank_type"] . "','" . $tank["capacity"] . "','" . $tank["remarks"] . "', NOW(), '" . $user . "'),";
        $insertedcolumn = rtrim($column, ",");
        $insertedValues = rtrim($insert, ",");
        
        if ($update != ''){
            $updatedValues = rtrim($update, ",");
        }else{
            $updatedValues = 'etd = null, eta = null, etb = null, td = null, ta = null, tb = null';
        }
        
        $queryString = "INSERT
        INTO
        public.tms_voyage_plan_actual
    (".$insertedcolumn.")
    values(".$insertedValues.")

    on
    conflict (voy_number)
    do

    UPDATE
    set
        ".$updatedValues."
    where
    tms_voyage_plan_actual.voy_number = '" . $voy_number . "';
    ";
        $queryString = $this->replaceAllNull($queryString);
        $data = app('db')->affectingStatement($queryString);

        return $this->successResponse($data);
    }

    

}
