<?php

namespace App\Controllers\API\V1;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ListModel;
use App\Entities\Lists;

class ListController extends BaseController
{
	protected $listModel;
	public function __construct()
	{
		$this->listModel = new ListModel();
	}
	//*	****************************************************************************
	//*	Methods HTTP
	//*	****************************************************************************
	public function index()
	{
		try
		{
			//*	****************************************************************************
			//*	Recuperar información de usuario por el token JWT
			//*	****************************************************************************
			$info = $this->getInfoUserFromJWT();
			$userID = $info->id;
			//*	****************************************************************************
			//*	Proceso de consulta
			//*	****************************************************************************
			$data = $this->findAllListByUserId($userID)?:null;
			if(is_null($data))
			{
				return $this->getResponseSuccess([]
					,'Listas');
			}
			return $this->getResponseSuccess([$data]
				, 'Listas');
		}catch(\Exception $e)
		{
			$except = [['general' => $e->getMessage()]];
			return $this->getResponseException($except
				, 'Excepción no controlada al obtener registros de lista');
		}
	}
	public function show($id)
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
			//*	Proceso de consulta
			//*	****************************************************************************
			$data = $this->findListByIdAndUserId($id, $userID)?:null;
			if(is_null($data))
			{
				return $this->getResponseSuccess([]
					, 'Lista');
			}
			return $this->getResponseSuccess([$data]
				, 'Lista');
		} catch (\exception $e) {
			$except = [['general' => $e->getMessage()]];
			$this->getResponseException($except
				, 'Excepción no controlada al obtener lista');
		}
	}
	public function store()
	{
		try {
			//*	****************************************************************************
			//*	Recuperar información de usuario por el token JWT
			//*	****************************************************************************
			$info = $this->getInfoUserFromJWT();
			$userID = $info->id;
			//*	****************************************************************************
			//*	Proceso de registro
			//*	****************************************************************************
			$req = $this->request->getVar();
			//*	Validar si existe lista
			$data = $this->findListByNameAndUserId($req->name, $userID)?: null;
			if(!is_null($data))
			{
				return $this->getResponseError([['name'	=> 'Inválido']]
					, 'Error de validaciones');
			}
			$lists = new Lists((array) $req);
			$lists->created_by = $userID;
			return $this->attach($lists);
		} catch (\Exception $e) {
			$except = [['general' => $e->getMessage()]];
			$this->getResponseException($except
				, 'Excepción no controlada al crear registro');
		}
	}
	public function edit($id)
	{
		try {
			//*	****************************************************************************
			//*	Recuperar información de usuario por el token JWT
			//*	****************************************************************************
			$info = $this->getInfoUserFromJWT();
			$userID = $info->id;
			//*	****************************************************************************
			//*	Proceso de edición
			//*	****************************************************************************
			$req = $this->request->getVar();
			$validList = $this->findListByIdAndUserId($id, $userID)?:null;
			if(is_null($validList))
			{
				throw new \Exception('Lista no localizada');
			}
			//*	comprobar nombre de lista
			$validListName = $this->findListByNameAndIdAndUserId($req->name, $id, $userID)?:null;
			if(!is_null($validListName))
			{
				return $this->getResponseError([['name' => 'Ya existe']]
					, 'Error de validaciones');
			}
			$lists = new Lists((array) $req);
			return $this->rewrite($lists, $id);
		} catch (\Exception $e) {
			$except = [['general' => $e->getMessage()]];
			$this->getResponseException($except
				, 'Excepción no controlada al editar registro');
		}
	}
	public function remove($id)
	{
		try {
			//*	******************************************************
			//*	Datos Jwt
			//*	******************************************************
			$info = $this->getInfoUserFromJWT();
			$userID = $info->id;
			//*	******************************************************
			//*	Datos de lista
			//*	******************************************************
			//*	Eliminar lista
			$deleted = $this->listModel->delete($id);
			if($deleted !== true)
			{
				throw new \Exception('Algo salio mal');
			}
			return $this->getResponseSuccess([['general' => 'Lista eliminada']]
				, 'Proceso de eliminación exitosa');
		} catch (\Exception $e) {
			$except = [['general' => $e->getMessage()]];
			$this->getResponseException($except
				, 'Excepción no controlada al eliminar registro');
		}
	}
	//*	****************************************************************************
	//*	Methods Queries
	//*	****************************************************************************
	//*	GET
	private function findAllListByUserId(string $userId)
	{
		return $this->listModel
			->select(['id', 'name'])
			->where('created_by', $userId)
			->findAll();
	}
	private function findListByIdAndUserId(string $id, string $userId)
	{
		return $this->listModel
			->select(['id', 'name'])
			->where('created_by', $userId)
			->where('id', $id)
			->first();
	}
	private function findListByNameAndUserId(string $name, string $userId)
	{
		return $this->listModel
			->where('name', $name)
			->where('created_by', $userId)
			->first();
	}
	private function findListByNameAndIdAndUserId(string $name, string $id, string $userId)
	{
		return $this->listModel
			->where('name', $name)
			->where('created_by', $userId)
			->whereNotIn('id',[$id])
			->findAll();
	}
	//*	CREATED
	private function attach(Lists $data)
	{
		$created = $this->listModel->insert($data);
		if($created === false)
		{
			return $this->getResponseError([$this->listModel->errors()]
				, 'Error de validación');
		}
		return $this->getResponseSuccess([['general' => 'Lista registrada']]
			, 'Registro exitoso'
			, ResponseInterface::HTTP_CREATED);
	}
	//*	UPDATED
	private function rewrite(lists $data, string $id)
	{
		$edited = $this->listModel->update($id, $data);
		if($edited === false)
		{
			return $this->getResponseError([$this->listModel->errors()]
				, 'Error de validación');
		}
		return $this->getResponseSuccess([['general'=> 'Lista editada']]
			, 'Actualización exitoso');
	}
}
