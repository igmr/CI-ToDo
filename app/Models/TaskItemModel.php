<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskItemModel extends Model
{
	protected $DBGroup				=	'default';
	protected $table				=	'task_item';
	protected $primaryKey			=	'id';
	protected $useAutoIncrement		=	true;
	protected $insertID				=	0;
	protected $returnType			=	\App\Entities\TaskItem::class;
	protected $useSoftDeletes		=	false;
	protected $protectFields		=	true;
	protected $allowedFields		=	['id', 'task_id', 'description', 'completed', 'created_by'];

	//*	****************************************************************************
	//*	Dates
	//*	****************************************************************************
	protected $useTimestamps		=	false;
	protected $dateFormat			=	'datetime';
	protected $createdField			=	'created_at';
	protected $updatedField			=	'updated_at';
	/*protected $deletedField		=	'deleted_at';*/

	//*	****************************************************************************
	//*	Validation
	//*	****************************************************************************
	protected $validationRules		=	[
		'task_id'		=>	'required|numeric',
		'description'	=>	'required',
	];
	protected $validationMessages	=	[
		'task_id'		=>	[
								'required'	=>	'Es requerido',
								'numeric'	=>	'Formato inválido',
							],
		'description'	=>	[
								'required'	=>	'Es requerido',
							],
	];
	protected $skipValidation		=	false;
	protected $cleanValidationRules	=	true;

	//*	****************************************************************************
	//*	Callbacks
	//*	****************************************************************************
	protected $allowCallbacks		=	true;
	protected $beforeInsert			=	[];
	protected $afterInsert			=	[];
	protected $beforeUpdate			=	[];
	protected $afterUpdate			=	[];
	protected $beforeFind			=	[];
	protected $afterFind			=	[];
	protected $beforeDelete			=	[];
	protected $afterDelete			=	[];
}
