<?php

/**
 * @param $message
 * @param $messages
 * @param $code
 * @return \Illuminate\Http\JsonResponse
 */
function send_response($message, $data){
    $response = [
        'status' => true,
        'message' => $message,
        'data' => $data
    ];
    return response()->json($response);
}

/**
 * @param $message
 * @param $messages
 * @param $code
 * @return \Illuminate\Http\JsonResponse
 */
function send_errors($message, $messages = [], $code = 404){
    $response = [
        'status' => false,
        'message' => $message,
    ];
    ! empty($messages) ? $response['errors'] = $messages : null;
    return response()->json($response, $code);
}
