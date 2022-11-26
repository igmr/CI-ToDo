<?php

namespace App\Controllers\API\V1;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\TaskModel;
use App\Entities\Task;

class TaskController extends BaseController
{
	protected $taskModel;
	public function __construct()
	{
		$this->taskModel = new TaskModel();
	}
	//*	****************************************************************************
	//*	Methods HTTP
	//*	****************************************************************************
	public function index($option)
	{
		try {
			//*	****************************************************************************
			//*	Recuperar información de usuario por el token JWT
			//*	****************************************************************************
			$info = $this->getInfoUserFromJWT();
			$userID = $info->id;
			//*	****************************************************************************
			//*	Proceso de consulta
			//*	****************************************************************************
			switch($option)
			{
				case 'all':
					$tittle = 'Todas la tareas';
					break;
				case 'today':
					$tittle = 'Tareas de hoy';
					break;
				case 'complete':
					$tittle = 'Tareas completadas';
					break;
				case 'important':
					$tittle = 'Tareas importantes';
					break;
			}
			$data = $this->findAllTaskByOption($option, $userID)?:null;
			if(is_null($data))
			{
				return $this->getResponseSuccess([], $tittle);
			}
			if(count($data) == 1)
			{
				return $this->getResponseSuccess([$data], $tittle);
			}
			return $this->getResponseSuccess($data, $tittle);
		} catch (\Exception $e) {
			$except = [['general' => $e->getMessage()]];
			$this->getResponseException($except
				, 'Excepción no controlada al obtener lista de tareas');
		}
	}
	public function show($id)
	{
		try {
			//*	****************************************************************************
			//*	Recuperar información de usuario por el token JWT
			//*	****************************************************************************
			$info = $this->getInfoUserFromJWT();
			$userID = $info->id;
			//*	****************************************************************************
			//*	Proceso de consulta
			//*	****************************************************************************
			$data= $this->findTaskByIdAndUserId($id, $userID);
			if(is_null($data))
			{
				return $this->getResponseError([], 'Tarea');
			}
			return $this->getResponseSuccess([$data], 'Tarea');
		} catch (\Exception $e) {
			$except = [['general' => $e->getMessage()]];
			$this->getResponseException($except
				, 'Excepción no controlada al obtener tarea');
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
			$task = new Task((array) $req);
			$task->created_by = $userID;
			if(!isset($task->list_id))
			{
				$task_id = 1;
			}
			if(!isset($task->completed))
			{
				$task->completed = false;
			}
			if(!isset($task->today))
			{
				$task->today = false;
			}
			if(!isset($task->important))
			{
				$task->important = false;
			}
			return $this->attach($task);
		} catch (\Exception $e) {
			$except = [['general' => $e->getMessage()]];
			$this->getResponseException($except
				, 'Excepción no controlada al crear registro');
		}
	}
	public function edit($option, $id)
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
			switch($option)
			{
				case 'all':
					$req = $this->request->getVar();
					//*	Buscar tarea
					$validTask = $this->findTaskByIdAndUserId($id, $userID)?:null;
					if(is_null($validTask))
					{
						return $this->getResponseError([['general' => 'Información inválido']]);
					}
					//*	Validar titulo y nota
					if(isset($req->tittle) || isset($req->note))
					{
						$task = new Task((array) $req);
						return $this->rewrite($task, $id);
					}
					return $this->getResponseError([['general' => 'Información inválido']]);
					break;
				case 'today':
					$data = $this->findTaskByIdAndUserId($id, $userID)?:null;
					if(is_null($data))
					{
						return $this->getResponseError([['general' => 'Información inválido']]);
					}
					$task = new Task();
					$task->today = !$data->today;
					$tittle = 'Tarea agregada ha hoy';
					if($task->today == false)
					{
						$tittle = 'Tarea eliminado de hoy';
					}
					return $this->rewrite($task, $id, $tittle);
					break;
				case 'complete':
					$data = $this->findTaskByIdAndUserId($id, $userID)?:null;
					if(is_null($data))
					{
						return $this->getResponseError([['general' => 'Información inválida']]);
					}
					$task = new Task();
					$task->completed= !$data->completed;
					$tittle = 'Tarea completada';
					if($task->completed === false)
					{
						$tittle= 'Tarea no completada';
					}
					return $this->rewrite($task, $id, $tittle);
					break;
				case 'important':
					$data = $this->findTaskByIdAndUserId($id, $userID)?:null;
					if(is_null($data))
					{
						return $this->getResponseError([['general' => 'Información inválida']]);
					}
					$task = new Task();
					$task->important = !$data->important;
					$tittle = 'Tarea importante completado';
					if($task->important === false)
					{
						$tittle = 'Tarea importante no completado';
					}
					return $this->rewrite($task, $id, $tittle);
					break;
			}
		} catch (\Exception $e) {
			$except = [['general' => $e->getMessage()]];
			$this->getResponseException($except
				, 'Excepción no controlada al editar registro');
		}
	}
	//*	****************************************************************************
	//*	Methods Queries
	//*	****************************************************************************
	//*	GET
	private function findAllTaskByOption(string $option, string $userId)
	{
		switch($option)
		{
			case 'all':
				return $this->taskModel
					->select(['id', 'list_id AS list', 'tittle', 'note', 'today','completed', 'important'])
					->where('created_by', $userId)
					->findAll();
				break;
			case 'today':
				return $this->taskModel
					->select(['id', 'list_id as list', 'tittle', 'note'])
					->where('created_by', $userId)
					->where('today', true)
					->findAll();
				break;
			case 'complete':
				return $this->taskModel
					->select(['id', 'list_id AS list', 'tittle', 'note'])
					->where('created_by', $userId)
					->where('completed', true)
					->findAll();
				break;
			case 'important':
				return $this->taskModel
					->select(['id', 'list_id AS list', 'tittle', 'note'])
					->where('created_by', $userId)
					->where('important', true)
					->findAll();
				break;
		}
	}
	private function findTaskByIdAndUserId(string $id, string $userId)
	{
		return $this->taskModel
			->select(['id', 'list_id as list', 'tittle', 'note', 'today', 'important', 'completed'])
			->where('created_by', $userId)
			->where('id', $id)
			->first();
	}
	//*	CREATED
	private function attach(Task $data)
	{
		$created = $this->taskModel->insert($data);
		if($created === false)
		{
			return $this->getResponseError([$this->taskModel->errors()]);
		}
		return $this->getResponseSuccess([['general' => 'Tarea registrada']]
			, 'Registro de tarea exitosa'
			,ResponseInterface::HTTP_CREATED);
	}
	//*	UPDATED
	private function rewrite(Task $data, string $id, string $tittle= 'Tarea editada')
	{
		$edited = $this->taskModel->update($id, $data);
		if($edited === false)
		{
			return $this->getResponseError([$this->taskModel->errors()]);
		}
		return $this->getResponseSuccess([['general' => $tittle]]
			, $tittle);
	}
}
