<?php

namespace App\Controllers\API\V1;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;
use App\Entities\User;

class AuthController extends BaseController
{
	protected $userModel;
	public function __construct()
	{
		$this->userModel = new UserModel();
	}
	//*	******************************************************
	//*	Methods HTTP
	//*	******************************************************
	public function index()
	{
		try
		{
			//*	****************************************************************************
			//*	Datos de usuario
			//*	****************************************************************************
			$req = $this->request->getVar();
			//*	Encontrar y validar existencia de usuario
			$data = $this->findUserByEmail($req->email)?:null;
			if(is_null($data))
			{
				throw new \Exception('Correo y/o contraseña son inválidos (1)');
			}
			//*	Encriptar y validar contraseña
			$passwordEncrypt = hash("sha512", $req->password);
			if($passwordEncrypt !== $data->password)
			{
				throw new \Exception('Correo y/o contraseña son inválidos (2)');
			}
			//*	****************************************************************************
			//*	JWT: token
			//*	****************************************************************************
			helper('jwt');
			$token = getSignedJwtUser($data->id);
			$payload = [
				'message'	=>	'Bienvenido '. $data->full_name,
				'token'		=>	$token,
			];
			return $this->getResponseSuccess([$payload], 'Inicio de sesión exitosa');
		}
		catch(\Exception $e)
		{
			$except = [['general'	=>	$e->getMessage()]];
			return $this->getResponseException($except, 'Excepción no controlada al inicio de sesión');
		}
	}
	public function register()
	{
		try{
			//*	*****************************************************
			//*	Datos de usuario
			//*	*****************************************************
			$req = $this->request->getVar();
			$user = new User((array) $req);
			return $this->attach($user);
		}catch(\Exception $e)
		{
			$except = [['general'	=>	$e->getMessage()]];
			return $this->getResponseException($except, 'Excepción no controlada en registro de usuario');
		}
	}
	//*	******************************************************
	//*	Method Queries
	//*	******************************************************
	//*	GET
	private function findUserByEmail(string $email)
	{
		return $this->userModel
			->where('email', $email)
			->first();
	}
	//*	CREATED
	private function attach(User $data)
	{
		$created = $this->userModel->insert($data);
		if($created === false)
		{
			return $this->getResponseError($this->userModel->errors()
				, 'Errores de validación');
		}
		return $this->getResponseSuccess([['general' => 'Registro exitoso']]
			, 'Registro exitoso'
			, ResponseInterface::HTTP_CREATED);
	}
}
