<?php
	/**
	 * Created by PhpStorm.
	 * User: bhim
	 * Date: 15/5/19
	 * Time: 1:14 PM
	 */

	namespace API\template;


	use API\curl\post;
	use API\functions\debug;

	class template
	{
		private $urlBase;
		private $url = '';

		public function __construct( $id )
		{
			$this->urlBase = ( $_SERVER[ 'SERVER_PORT' ] == 8888 ) ? '127.0.0.1:8888' : 'localhost';
			$this->url = 'http://' . $this->urlBase . '/local_api/public/template/' . $id->sms_default_id;
		}

		/**
		 * @param   int          $id The sms_default id
		 * @param   array|object $data
		 *
		 * @return string
		 * @throws \Exception
		 */
		public static function get( $id , $data )
		{

//			print_r($data); exit;

			$s = new self( $id );

//			print_r(json_encode( $data->sessions->session_client_name )); exit;

			$template = post::call( $s->url , [ 'data' => json_encode( $data ) ] );
//			debug::log( $template );

			$template = json_decode( $template );

//			debug::log( $template );

			if ( $template->error )
			{
				throw new \Exception( $template->errorMessage );
			}
			else
			{
				return $template->template;
			}

		}
	}
