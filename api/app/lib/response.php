<?php
namespace App\Lib;

class Response
{
	public $result     = null;
	public $response   = false;
	public $message    = 'Ocurrió un error inesperado.';
	public $href       = null;
	public $function   = null;
	
	public $filter     = null;
	
	public function SetResponse($response, $m = '')
	{
		$this->response = $response;
		$this->message = $m;

		if(!$response && $m = '') $this->response = 'Ocurrió un error inesperado';
	}
}