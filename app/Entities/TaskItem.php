<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class TaskItem extends Entity
{
	protected $attributes	=	[];
	protected $datamap		=	[];
	protected $dates		=	['created_at', 'updated_at'];
	protected $casts		=	[
		'id'			=>	'integer',
		'task_id'		=>	'integer',
		'description'	=>	'string',
		'completed'		=>	'boolean',
		'created_by'	=>	'integer',
		'created_at'	=>	'timestamp',
		'updated_at'	=>	'?timestamp',
	];
	public function setCreatedAt(string $createdAt)
	{
		$this->attributes['created_at']= new Time($createdAt, 'UTC');
		return $this;
	}
	public function setUpdatedAt(string $updatedAt)
	{
		$this->attributes['updated_at'] = new Time($updatedAt, 'UTC');
		return $this;
	}
}
