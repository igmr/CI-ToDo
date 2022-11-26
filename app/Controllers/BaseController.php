<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use CodeIgniter\API\ResponseTrait;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
	/**
	 * Instance of the main Request object.
	 *
	 * @var CLIRequest|IncomingRequest
	 */
	protected $request;

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = [];

	/**
	 * Constructor.
	 */
	public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		// Preload any models, libraries, etc, here.

		// E.g.: $this->session = \Config\Services::session();
	}

	//*	****************************************************************************
	//*	Obtener información de usuario JWT
	//*	****************************************************************************
	public function getInfoUserFromJWT()
	{
		try {
			//*	Recuperar información del usuario
			$user = $this->request->user;
			//*	Validar información del usuario
			if(is_null($user))
			{
				$this->getResponseError([['general' => 'Error al recuperar información']]
				, 'Error de autorización', ResponseInterface::HTTP_UNAUTHORIZED);
			}
			return $user;
		} catch (\Exception $e) {
			$except = [['general' => $e->getMessage()]];
			$this->getResponseException($except);
		}
	}
	//*	****************************************************************
	//*	Response API REST
	//*	****************************************************************
	use ResponseTrait;
	//*	****************************************************************************
	//*	Respuestas exitosas
	//*	****************************************************************************
	public function getResponseSuccess(array $data, string $message = 'Operación exitosa'
		, int $codeHttp = ResponseInterface::HTTP_OK)
	{
		//*	Información de respuesta
		$payload = [
			'status'	=> 'success',
			'code'		=> $codeHttp,
			'message'	=> $message,
			'error'		=> [],
			'data'		=>	$data,
		];
		//*	Respuesta JSON
		return $this
			->response
			->setStatusCode($codeHttp)
			->setJSON($payload);
	}
	//*	****************************************************************************
	//*	Respuesta de errores
	//*	****************************************************************************
	public function getResponseError(array $error, string $message = 'Operación fallida'
		, int $codeHttp = ResponseInterface::HTTP_BAD_REQUEST)
	{
		//*	Información de respuesta
		$payload = [
			'status'	=> 'code',
			'code'		=> $codeHttp,
			'message'	=> $message,
			'error'		=> $error,
			'data'		=> [],
		];
		//*	Respuesta JSON
		return $this
			->response
			->setStatusCode($codeHttp)
			->setJSON($payload);
	}
	//*	****************************************************************************
	//*	Respuesta de errores por excepciones no controladas
	//*	****************************************************************************
	public function getResponseException(array $except, string $message = 'Excepción no controlada'
		, int $codeHttp = ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
	{
		//*	Información de respuesta
		$payload = [
			'status'	=>	'exception',
			'code'		=>	$codeHttp,
			'message'	=>	$message,
			'error'		=>	$except,
			'data'		=>	[],
		];
		//*	Respuesta JSON
		return $this
			->response
			->setStatusCode($codeHttp)
			->setJSON($payload);
	}
}
