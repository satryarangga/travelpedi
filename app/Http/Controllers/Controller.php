<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function apiSuccess($payload = null, $statusCode = 200,  $pagination = null) {
    	$data = [
    		'code'        => $statusCode,
    		'data'		  => $payload
    	];

        if($pagination != null) $data['pagination'] = $pagination;

    	return response($data, $statusCode)
                  ->header('Content-Type', 'application/json');
    }

    public function apiError($statusCode = 500, $internalMsg = "Something Wrong") {
    	$data['error'] = [
    		'code'			=> $statusCode,
    		'internalMsg'	=> $internalMsg
    	];

    	return response($data, $statusCode)
                  ->header('Content-Type', 'application/json');
    }
}
