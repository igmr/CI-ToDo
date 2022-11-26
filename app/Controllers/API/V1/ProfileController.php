<?php

namespace App\Controllers\API\V1;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;
use App\Entities\User;

class ProfileController extends BaseController
{
	protected $userModel;
	public function __construct()
	{
		$this->userModel = new UserModel();
	}
	//*	****************************************************************************
	//*	Methods HTTP
	//*	****************************************************************************
	public function index()
	{
		try {
			//*	****************************************************************************
			//*	Recuperar información de usuario por el token JWT
			//*	****************************************************************************
			$info = $this->getInfoUserFromJWT();
			if(is_null($info))
			{
				throw new \Exception('Usuario no localizado');
			}
			$userID = $info->id;
			//*	****************************************************************************
			//*	Recuperar información de usuario
			//*	****************************************************************************
			$data = $this->findUserById($userID)?:null;
			if(is_null($data))
			{
				throw new \Exception('No pudo ser recuperado, la información del usuario');
			}
			return $this->getResponseSuccess([$data]
				, 'Información de perfil');
		} catch (\Exception $e) {
			$except = [['general' => $e->getMessage()]];
			return $this->getResponseException($except
				, 'Excepción no controlado al obtener información del usuario');
		}
	}
	public function edit()
	{
		try {
			//*	****************************************************************************
			//*	Recuperar información de usuario por el token JWT
			//*	****************************************************************************
			$info = $this->getInfoUserFromJWT();
			if(is_null($info))
			{
				throw new \Exception('Usuario no localizado');
			}
			$userID = $info->id;
			//*	******************************************************
			//*	Proceso de edición
			//*	******************************************************
			$req = $this->request->getVar();
			$user = new User((array) $req);
			return $this->rewrite($user, $userID);
		} catch (\Exception $e) {
			$except = [['general' => $e->getMessage()]];
			return $this->getResponseException($except
				, 'Excepción no controlada en la edición de información del usuario');
		}
	}
	//*	****************************************************************************
	//*	Methods Queries
	//*	****************************************************************************
	//*	GET
	private function findUserById(string $userId)
	{
		return $this->userModel
			->select(['id', 'full_name as name', 'user_name as user', 'email'])
			->where('id', $userId)
			->first();
	}
	//*	UPDATED
	private function rewrite(User $user, string $userId)
	{
		$edited = $this->userModel
			->update($userId, $user);
		if($edited === false)
		{
			return $this->getResponseError([$this->userModel->errors()]
				, 'Error de actualización de perfil');
		}
		return $this->getResponseSuccess([['general' => 'Perfil actualizado']]
			, 'Actualización de perfil exitoso');
	}
}
