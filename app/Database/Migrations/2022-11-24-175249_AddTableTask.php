<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class AddTableTask extends Migration
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
			'list_id'		=> [
				'type'				=> 'INT',
				'constraint'		=> 15,
				'unique'			=> false,
				'null'				=> false,
				/*'auto_increment'	=> true,*/
				'default'			=> 1,
				'comment'			=> '',
			],
			'tittle'		=> [
				'type'				=> 'VARCHAR',
				'constraint'		=> 65,
				'unique'			=> false,
				'null'				=> false,
				/*'auto_increment'	=> true,*/
				'default'			=> '',
				'comment'			=> '',
			],
			'note'			=> [
				'type'				=> 'TEXT',
				/*'constraint'		=> 0,*/
				'unique'			=> false,
				'null'				=> false,
				/*'auto_increment'	=> true,*/
				/*'default'			=> '',*/
				'comment'			=> '',
			],
			'today'			=> [
				'type'				=> 'BOOL',
				/*'constraint'		=> 0,*/
				'unique'			=> false,
				'null'				=> false,
				/*'auto_increment'	=> true,*/
				'default'			=> false,
				'comment'			=> '',
			],
			'important'		=> [
				'type'				=> 'BOOL',
				/*'constraint'		=> 0,*/
				'unique'			=> false,
				'null'				=> false,
				/*'auto_increment'	=> true,*/
				'default'			=> false,
				'comment'			=> '',
			],
			'completed'		=> [
				'type'				=> 'BOOL',
				/*'constraint'		=> 0,*/
				'unique'			=> false,
				'null'				=> false,
				/*'auto_increment'	=> true,*/
				'default'			=> false,
				'comment'			=> '',
			],
			'created_by'	=> [
				'type'				=> 'INT',
				'constraint'		=> 15,
				'unique'			=> false,
				'null'				=> false,
				/*'auto_increment'	=> true,*/
				'default'			=> 1,
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
		//* Agregar campos
		$this->forge->addField($fields);
		//*	Definir clave primaria
		$this->forge->addPrimaryKey('id');
		//*	Definir claves foráneas
		$this->forge->addForeignKey('list_id', 'list', 'id');
		//*	Agregar atributos adicionales
		$attributes = ['ENGINE', 'InnoDB'];
		//*	Crear tabla
		$this->forge->createTable('task', true, $attributes);
	}
	//*	****************************************************************************
	//*	ROLLBACK
	//*	****************************************************************************
	public function down()
	{
		$this->forge->dropTable('task');
	}
}
