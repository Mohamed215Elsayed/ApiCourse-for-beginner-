<?php 
namespace App\Http\Controllers\API;
trait ApiResponsetrait {
    public function apiRespone($data = null, $message = null, $status = null){
        $array = [
            'data' => $data,
            'message'=>$message,
            'status' => $status
        ];
        return response($array,200);
    }
}