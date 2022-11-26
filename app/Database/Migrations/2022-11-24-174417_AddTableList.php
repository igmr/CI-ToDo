<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\DataBase\RawSql;

class AddTableList extends Migration
{
	//*	****************************************************************************
	//*	MIGRATE
	//*	****************************************************************************
	public function up()
	{
		//*	Definición de campos
		$fields = [
			'id'			=> [
				'type'				=> 'INT',
				'constraint'		=> 15,
				'unique'			=> true,
				'null'				=> false,
				'auto_increment'	=> true,
				/*'default'			=> '',*/
				'comment'			=> '',
			],
			'name'			=> [
				'type'				=> 'VARCHAR',
				'constraint'		=> 128,
				'unique'			=> true,
				'null'				=> false,
				/*'auto_increment'	=> true,*/
				'default'			=> '',
				'comment'			=> '',
			],
			'created_by'	=> [
				'type'				=> 'INT',
				'constraint'		=> 15,
				'unique'			=> false,
				'null'				=> false,
				/*'auto_increment'	=> true,*/
				'default'			=> 0,
				'comment'			=> '',
			],
			'created_at'	=> [
				'type'				=> 'TIMESTAMP',
				/*'constraint'		=> 0,*/
				'unique'			=> false,
				'null'				=> false,
				/*'auto_increment'	=> true,*/
				'default'			=> new RawSql('CURRENT_TIMESTAMP'),
				'comment'			=> '',
			],
			'updated_at'	=> [
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
		//*	Crear tabla
		$this->forge->createTable('list', true, $attributes);
	}
	//*	****************************************************************************
	//*	ROLLBACK
	//*	****************************************************************************
	public function down()
	{
		$this->forge->dropTable('list');
	}
}
