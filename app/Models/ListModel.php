<?php

namespace App\Models;

use CodeIgniter\Model;

class ListModel extends Model
{
	protected $DBGroup				=	'default';
	protected $table				=	'list';
	protected $primaryKey			=	'id';
	protected $useAutoIncrement		=	true;
	protected $insertID				=	0;
	protected $returnType			=	\App\Entities\Lists::class;
	protected $useSoftDeletes		=	false;
	protected $protectFields		=	true;
	protected $allowedFields		=	['id', 'name', 'created_by'];

	//*	****************************************************************************
	//*	Dates
	//*	****************************************************************************
	protected $useTimestamps		=	false;
	protected $dateFormat			=	'datetime';
	protected $createdField			=	'created_at';
	protected $updatedField			=	'updated_at';
	/*protected $deletedField  = 'deleted_at';*/

	//*	****************************************************************************
	//*	Validation
	//*	****************************************************************************
	protected $validationRules		=	[
		'name'			=>	'required',
		'created_by'	=>	'required|numeric',
	];
	protected $validationMessages	=	[
		'name'			=>	[
								'required'	=>	'Es requerido',
							],
		'created_by'	=>	[
								'required'	=>	'Es requerido',
								'numeric'	=>	'Formato inválido'
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
