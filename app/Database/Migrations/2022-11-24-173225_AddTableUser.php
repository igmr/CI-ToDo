<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class AddTableUser extends Migration
{
	//*	****************************************************************************
	//*	MIGRATE
	//*	****************************************************************************
	public function up()
	{
		//*	Definición de campos
		$fields = [
			'id'			=>	[
				'type'				=> 'INT',
				'constraint'		=> 15,
				'unique'			=> true,
				'null'				=> false,
				'auto_increment'	=> true,
				/*'default'			=> '',*/
				'comment'			=> '',
			],
			'full_name'		=>	[
				'type'				=> 'VARCHAR',
				'constraint'		=> 65,
				'unique'			=> false,
				'null'				=> false,
				/*'auto_increment'	=> true,*/
				'default'			=> '',
				'comment'			=> '',
			],
			'user_name'		=>	[
				'type'				=> 'VARCHAR',
				'constraint'		=> 65,
				'unique'			=> true,
				'null'				=> false,
				/*'auto_increment'	=> true,*/
				'default'			=> '',
				'comment'			=> '',
			],
			'email'			=>	[
				'type'				=> 'VARCHAR',
				'constraint'		=> 65,
				'unique'			=> true,
				'null'				=> false,
				/*'auto_increment'	=> true,*/
				'default'			=> '',
				'comment'			=> '',
			],
			'password'		=>	[
				'type'				=> 'VARCHAR',
				'constraint'		=> 512,
				'unique'			=> false,
				'null'				=> false,
				/*'auto_increment'	=> true,*/
				'default'			=> '',
				'comment'			=> '',
			],
			'created_at'	=>	[
				'type'				=> 'TIMESTAMP',
				/*'constraint'		=> 0,*/
				'unique'			=> false,
				'null'				=> false,
				/*'auto_increment'	=> true,*/
				'default'			=> new RawSql('CURRENT_TIMESTAMP'),
				'comment'			=> '',
			],
			'updated_at'	=>	[
				'type'				=> 'TIMESTAMP',
				/*'constraint'		=> 0,*/
				'unique'			=> false,
				'null'				=> true,
				/*'auto_increment'	=> true,*/
				'default'			=> null,
				'comment'			=> '',
			],
			'deleted_at'	=>	[
				'type'				=> 'TIMESTAMP',
				/*'constraint'		=> 0,*/
				'unique'			=> false,
				'null'				=> true,
				/*'auto_increment'	=> true,*/
				'default'			=> null,
				'comment'			=> '',
			],
		];
		//*	Agregar campos
		$this->forge->addField($fields);
		//*	Definir clave primaria
		$this->forge->addPrimaryKey('id');
		//*	Agregar atributos adicionales
		$attributes = ['ENGINE', 'InnoDB'];
		//* Crear tabla
		$this->forge->createTable('user', true, $attributes);
	}
	//*	****************************************************************************
	//*	ROLLBACK
	//*	****************************************************************************
	public function down()
	{
		$this->forge->dropTable('user');
	}
}
