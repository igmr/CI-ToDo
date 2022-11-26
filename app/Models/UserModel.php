<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
	protected $DBGroup			=	'default';
	protected $table			=	'user';
	protected $primaryKey		=	'id';
	protected $useAutoIncrement	=	true;
	protected $insertID				=	0;
	protected $returnType			=	\App\Entities\User::class;
	protected $useSoftDeletes		=	true;
	protected $protectFields		=	true;
	protected $allowedFields		=	['id', 'full_name', 'user_name', 'email', 'password'];

	//*	****************************************************************************
	//*	Dates
	//*	****************************************************************************
	protected $useTimestamps		=	true;
	protected $dateFormat			=	'datetime';
	protected $createdField			=	'created_at';
	protected $updatedField			=	'updated_at';
	protected $deletedField			=	'deleted_at';

	//*	****************************************************************************
	//*	Validation
	//*	****************************************************************************
	protected $validationRules		=	[
		'full_name'		=>	'required',
		'user_name'		=>	'required|alpha_numeric|is_unique[user.user_name]',
		'email'			=>	'required|valid_email|is_unique[user.email]',
		'password'		=>	'required|min_length[8]',
	];
	protected $validationMessages	=	[
		'full_name'		=>	[
								'required'		=>	'Es requerido (1)',
							],
		'user_name'		=>	[
								'required'		=>	'Es requerido (1)',
								'alpha_numeric'	=>	'Formato inválido',
								'is_unique'		=>	'Es requerido (2)',
							],
		'email'			=>	[
								'required'		=>	'Es requerido (1)',
								'valid_email'	=>	'Formato inválido',
								'is_unique'		=>	'Es requerido (2)',
							],
		'password'		=>	[
								'required'		=>	'Es requerido (1)',
								'min_length'	=>	'El número mínimo de caracteres son 8',
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
