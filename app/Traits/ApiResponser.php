<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponser{
 
    private function sizeofvar($var) {
        $start_memory = memory_get_usage();
        $tmp = unserialize(serialize($var));
        return memory_get_usage() - $start_memory;
    }

    public function successResponse($data, $code = Response::HTTP_OK){
        return response()->json(['data' => $data], $code);
    }

    public function errorResponse($message, $code){
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

}