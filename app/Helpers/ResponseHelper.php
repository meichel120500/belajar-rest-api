<?php 

namespace App\Helpers;

class ResponseHelper{
    
    public static function success($data =null, $message = "Success", $status = 200) {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    public static function eror( $message = "Eror",$erors=null, $status = 400) {
        return response()->json([
            'status' => false,
            'message' => $message,
            'erors' => $erors,
        ], $status);
    }
}
?>