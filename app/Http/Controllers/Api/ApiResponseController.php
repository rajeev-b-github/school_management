<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiResponseController extends Controller
{
    /**
     * Returns a generic success (200) JSON response.
     *
     * @param  string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function responseSuccess($message = 'Success.', $data = [], $success = true)
    {
        $res = [
            'status' => 200,
            'success' => $success,
            'message' => $message,
        ];

        if ($data) {
            $res['data'] = $data;
        }

        return response()->json($res, 200);
    }

    public static function responseFailed($message = 'Failed', $success = false)
    {
        $res = [
            'status' => 203,
            'success' => $success,
            'message' => $message,
        ];

        return response()->json($res, 203);
    }


    /**
     * Returns a resource updated success message (200) JSON response.
     *
     * @param  string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function responseResourceUpdated($message = 'Resource updated.')
    {
        return response()->json([
            'status' => 200,
            'message' => $message,
        ], 200);
    }

    /**
     * Returns a resource created (201) JSON response.
     *
     * @param  string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function responseResourceCreated($message = 'Resource created.')
    {
        return response()->json([
            'status' => 201,
            'message' => $message,
        ], 201);
    }

    /**
     * Returns a resource deleted (204) JSON response.
     *
     * @param  string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function responseResourceDeleted($message = 'Resource deleted.')
    {
        return response()->json([
            'status' => 204,
            'message' => $message,
        ], 204);
    }

    /**
     * Returns an unauthorized (401) JSON response.
     *
     * @param  array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public static function responseUnauthorized($errors = ['Unauthorized.'])
    {
        return response()->json([
            'status' => 401,
            'errors' => $errors,
        ], 401);
    }

    /**
     * Returns a unprocessable entity (422) JSON response.
     *
     * @param  array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public static function responseUnprocessable($errors)
    {
        return response()->json([
            'status' => 422,
            'errors' => $errors,
        ], 422);
    }

    /**
     * Returns a server error (500) JSON response.
     *
     * @param  array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public static function responseServerError($error = 'Server error.')
    {
        return response()->json([
            'status' => 500,
            'message' => $error
        ], 500);
    }

    /**
     * Returns a no data found (404) JSON response.
     *
     * @param  array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public static function responseNotFound($message = 'No data found.')
    {
        return response()->json([
            'status' => 404,
            'message' => $message
        ], 404);
    }
}
