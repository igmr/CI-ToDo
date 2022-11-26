<?php

namespace App\Controllers;

class Home extends BaseController
{
	//*	****************************************************************************
	//*	Methods HTTP
	//*	****************************************************************************
	public function index()
	{
		$data = [[
			'general'	=>	'API REST - CodeIgniter 4',
			'base_api'	=>	'http://localhost:8080/api/v1'
		],];
		return $this->getResponseSuccess($data,'API REST');
	}
}
