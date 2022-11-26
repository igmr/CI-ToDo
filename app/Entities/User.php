<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class User extends Entity
{
	protected $attributes	=	[];
	protected $datamap		=	[];
	protected $dates		=	['created_at', 'updated_at', 'deleted_at'];
	protected $casts		=	[
		'id'			=>	'integer',
		'full_name'		=>	'string',
		'user_name'		=>	'string',
		'email'			=>	'string',
		'password'		=>	'string',
		'created_at'	=>	'?timestamp',
		'updated_at'	=>	'?timestamp',
		'deleted_at'	=>	'?timestamp',
	];
	public function setPassword(string $password)
	{
		$this->attributes['password'] = hash('sha512', $password);
		return $this;
	}
	public function setCreatedAt(string $createdAt)
	{
		$this->attributes['created_at'] = new Time($createdAt, 'UTC');
		return $this;
	}
	public function setUpdatedAt(string $updatedAt)
	{
		$this->attributes['updated_at'] = new Time($updatedAt, 'UTC');
		return $this;
	}
	public function setDeletedAt(string $deletedAt)
	{
		$this->attributes['deleted_at'] = new Time($deletedAt, 'UTC');
		$this;
	}
}
