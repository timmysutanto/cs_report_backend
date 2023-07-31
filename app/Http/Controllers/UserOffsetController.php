<?php

namespace App\Http\Controllers;

use App\Helpers\HelperSSO;
use App\Models\SLATicket;
use App\Models\UserOffset;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class UserOffsetController extends Controller
{
    public function updateUserOffset(Request $request)
    {
        /*
            validation
        */
        $this->validate($request, [
            'user_email' => 'required',
            'user_name' => 'required',
            'topic' => 'required',
            'group' => 'required',
            'offset' => 'required'
        ], [
            'user_email.required' => 'User Harus diisi',
            'user_name.required' => 'Username harus diisi',
            'topic.required' => 'Topic harus diisi',
            'group.required' => 'Group harus diisi',
            'offset.required' => 'Offset harus diisi'
        ]);
        /*
            verify token dari sso & profile
        */
        $verify = HelperSSO::verifyTokenHeader($request->user_email, Controller::getBearerToken());
        if (isset($verify['code'])) {
            return response()->json(json_decode($verify), 400);
        }
        $profile = HelperSSO::getUserProfileSSO($request->user_email);

        /*
            apabila user offset sudah ada maka dilakukan update, jika belum ada maka insert row baru.
        */
        $check_offset = UserOffset::where('user_name', '=', $request->user_name)
        ->where('topic', '=', $request->topic)
        ->where('group', '=', $request->group)
        ->first();

        if (is_null($check_offset)) {
            try {
                UserOffset::create([
                    'user_name' => $request->user_name,
                    'topic' => $request->topic,
                    'group' => $request->group,
                    'offset' => 0,
                    'status' => 1,
                    "created_by" => $profile["username"],
                    "created_date" => Carbon::now(),
                    "updated_by" => $profile["username"],
                    "updated_date" => Carbon::now()
                ]);
            } catch (Exception $e) {
                return $e->getMessage();
            }
        } else {
            try {
                $check_offset->offset = $request->offset;
                $check_offset->updated_by = $profile["username"];
                $check_offset->updated_date = Carbon::now();
                $check_offset->save();
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }

        $format = [
            "status" => 200,
            "message" => "success",
            "data" => $check_offset
        ];

        return response()->json($format, 200);
    }


    public function getAllUserOffset(Request $request)
    {
        /*
            validation
        */
        $this->validate($request, [
            'user_email' => 'required',
        ], [
            'user_email.required' => 'User Harus diisi',
        ]);

        /*
            verify token dari sso & profile
        */
        $verify = HelperSSO::verifyTokenHeader($request->user_email, Controller::getBearerToken());
        if (isset($verify['code'])) {
            return response()->json(json_decode($verify), 400);
        }
        /*
            get all user offset
        */
        $all = UserOffset::all();
        $format = [
            "status" => 200,
            "message" => "success",
            "data" => $all
        ];

        return response()->json($format, 200);
    }


    public function getLastOffsetByUsername(Request $request)
    {
        /*
            verify token dari sso & profile
        */
        $verify = HelperSSO::verifyTokenHeader($request->user_email, Controller::getBearerToken());
        if (isset($verify['code'])) {
            return response()->json(json_decode($verify), 400);
        }
        $profile = HelperSSO::getUserProfileSSO($request->user_email);

        /*
            get last offset berdasarkan username
        */

        $last_offset_by_username = UserOffset::where('user_name', '=', $profile["username"])
        ->where('status', '=', '1')
        ->where('topic', '=', $request->topic)
        ->where('group', '=', $request->group)
        ->select('id', 'user_name', 'offset')
        ->firstOrFail();



        $format = [
            "status" => 200,
            "message" => "success",
            "data" => $last_offset_by_username
        ];

        return response()->json($format, 200);
    }


    public function getListOffsetByUsername(Request $request)
    {
        /*
            verify token dari sso & profile
        */
        $verify = HelperSSO::verifyTokenHeader($request->user_email, Controller::getBearerToken());
        if (isset($verify['code'])) {
            return response()->json(json_decode($verify), 400);
        }
        $profile = HelperSSO::getUserProfileSSO($request->user_email);

        /*
            get list offset berdasarkan username
        */

        $list_offset_by_username = UserOffset::where('user_name', '=', $profile["username"])
        ->where('status', '=', '1')
        ->select('id', 'user_name', 'offset')
        ->get();


        $format = [
            "status" => 200,
            "message" => "success",
            "data" => $list_offset_by_username
        ];

        return response()->json($format, 200);
    }
}
