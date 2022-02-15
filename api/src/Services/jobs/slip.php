<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 31/1/17
 * Time: 4:41 PM
 */

namespace API\jobs;

use API\functions\debug;
use API\functions\utility;
use API\model\messageModel;
use API\sql\request as standardSqlRequest;
use API\traits\getPathfromPaths;
use Slim\Http\Response;
use Slim\Http\Request;


class slip extends standardSqlRequest
{
	use getPathfromPaths;

	function __construct()
	{
		parent::__construct( 'job_slips' );
	}

	private static function add( $jobid , $filename )
	{
		$s = new self();
		$s->setField( 'job_id' , $jobid );
		$s->setField( 'file_name' , $filename , true );
		return $s->insert();
	}

	public static function get( $jobid )
	{
		$s = new self();
		$s->where( 'job_id' , $jobid );
		$s->select();
		return ( $s->found ) ? $s->rows[ 0 ] : (object)[];
	}

	public function upload( Request $request , Response $response , $args )
	{
		$messageModel = new messageModel();
//		$data = $request->getAttribute( 'data' );
		$p = $request->getParsedBody();
//		debug::log( 'uploading slip' );
//		print_r( $p ); exit;
		/* @todo Add check to see if interpreter is assigned to this job while being aware that this request could come from a staff member */
		$port = $request->getServerParams()[ 'SERVER_PORT' ];
		$ref = $args[ 'id' ];
		$jobId = details::getId( $ref );
		$pth = ( $port == '8888' ) ? '/Applications/MAMP/htdocs/storage/slips/' : '/var/jobslips/toBox/';
		$temp_file_storage = ($port === '8888') ? '/Applications/MAMP/htdocs/storage/temp_file_storage/' : sys_get_temp_dir() . '/';
		$file = @ $p[ 'base64' ];
		$file_name = $p[ 'file_name' ];
		$fileType = @ $p[ 'type' ];
		$fileExtension = @ $p[ 'ext' ];
		$finished_uploading = $p[ 'finished_uploading' ];

		$fileUploadResponse = $this->intoBox($ref, $pth, $temp_file_storage, $file, $file_name, $finished_uploading, $fileExtension, $jobId, $fileType);

//		$file_data = explode(";base64,", $file);
//		$data = $file_data[ 1 ];

//		$newFname = $ref . '.' . $fileExtension;
//		$content = base64_decode( $data );
//		file_put_contents( $pth . $newFname , $content );
//		self::add( $jobid , $newFname );

//		$obj = (object)['error' => ( $this->error ) ? $this->error : false];
//
//		$response = $response->withJson( $obj );

//		return $response;

		if ($fileUploadResponse->error === true) {
			$returnMessage = $messageModel->returnMessage(true, 'Failed to Save/Update!', $fileUploadResponse);
		}
		else {
			$returnMessage = $messageModel->returnMessage(false, 'Saved/Updated successfully!', $fileUploadResponse);
		}

		return $response->withJson( $returnMessage );
	}

	public static function intoBox( $ref, $pth, $temp_file_storage, $file, $file_name, $finished_uploading, $fileExtension, $jobId, $fileType)
	{
		if ($file){

			$new_file_name = $jobId . $file_name . $ref . "." . $fileExtension;
			$file_data = explode(";base64,", $file);
			$data = $file_data[ 1 ];

//				print_r($file_data); exit;

			if ( $fileType ) {
				if ( $data ) {
					$createFile = fopen($temp_file_storage . $new_file_name, "a");

					if ( $createFile ) {
						$content = base64_decode($data);

						if ( $content ) {

							$file_write_state = fwrite($createFile, $content);

							if ($file_write_state) {
								$file_close_state = fclose($createFile);

								if ( $file_close_state ) {

									if ( $finished_uploading === 1 ) {

										$newFileName = $ref . '.' . $fileExtension;
										self::add( $jobId , $newFileName );

										$destination = $pth . $newFileName;
										$validation_status = rename ( $temp_file_storage . $new_file_name , $destination );

										if ($validation_status) {

											$returnMessage = (object)['error' => false, 'message' => 'Saved into Box successfully'];

										}
										else {
											$returnMessage = (object)['error' => true, 'message' => 'Moving the file from temp dir to box dir failed', 'data' => $validation_status];
										}
									}
									else {
										$returnMessage = (object)['error' => false, 'message' => 'Still waiting for chunked files to merge into current file', 'data' => $finished_uploading];
									}

								}
								else {
									$returnMessage = (object)['error' => true, 'message' => 'File close failed. Check the fclose function', 'error_log' => $file_close_state];
								}

							}
							else {
								$returnMessage = (object)['error' => true, 'message' => 'File is broken. Check the fwrite function', 'error_log' => $file_write_state];
							}

						}
						else {
							$returnMessage = (object)['error' => true, 'message' => 'File content are broken. Check whether its a correct base64 file format', 'error_log' => $content];
						}

					}
					else {
						$returnMessage = (object)['error' => true, 'message' => 'File could not be created or opened.', 'error_log' => $temp_file_storage];
					}

				}
				else {
					$returnMessage = (object)['error' => true, 'message' => 'File data is not supplied.', 'error_log' => $data];
				}
			}
			else {
				$returnMessage = (object)['error' => true, 'message' => 'File upload failed due to incorrect file type.', 'error_log' => $fileType];
			}

		}
		else {
			$returnMessage = (object)['error' => true, 'message' => 'No file recieved by API'];
		}

		return $returnMessage;
	}



	/**
	 * @param Request $request
	 * @param $fileName
	 * @return array
	 */
	private function pathsArray( Request $request , $fileName )
	{
		$path = ( $request->getUri()->getHost() == 'localhost' ) ? '/Applications/MAMP/htdocs/storage/slips' : '/var/jobslips/toBox';
		$pathsArr[] = $path . '/' . $fileName;
		$pathsArr[] = '/var/jobslips/inBox/' . $fileName;
		return $pathsArr;
	}

	public function remove( Request $request , Response $response , $args )
	{
		$data = $request->getAttribute( 'data' );
		$obj = (object)['error' => true];

		if ( $jobid = details::exists( $args[ 'id' ] , $data->fmid ) )
		{
			$slip = self::get( $jobid );
			$pathsArr = $this->pathsArray( $request , $slip->file_name );
			$retval = shell_exec( '/var/jobslips/deleteFromBox.sh ' . $slip->file_name . ' 2>&1' );

			if ( $retval == '204')
			{
//				$imagePath = $this->getPathFromPaths( $pathsArr );
//				unlink( $imagePath );
				$obj->error = false ;
				$obj->success = true ;
				$obj->responseCode = $retval ;
			}
			else
			{
				$obj->error = true ;
				$obj->success = false ;
				$obj->responseCode = $retval ;
			}
		}

		return $response->withJson( $obj );


	}

	public function retrieve( Request $request , Response $response , $args )
	{

		$data = $request->getAttribute( 'data' );
		$obj = (object)['error' => false];

        if ( $jobid = details::exists( $args[ 'id' ] , $data->fmid, $data->clientid ) )
        {
			$path = ( $request->getUri()->getHost() == 'localhost' ) ? '/Applications/MAMP/htdocs/storage/slips' : '/var/jobslips/toBox';
			$slip = self::get( $jobid );
			$pathsArr[] = $path . '/' . $slip->file_name;
			$pathsArr[] = '/var/jobslips/inBox/' . $slip->file_name;


            if ( $imagePath = $this->getPathFromPaths( $pathsArr ) )
            {
                $image = @file_get_contents( $imagePath );
            }
            else
            {
                $retval = shell_exec( '/var/jobslips/getFromBox.sh ' . $slip->file_name . ' 2>&1' );

                if ( $retval == '200' )
                {
                    $imagePath = $this->getPathFromPaths( $pathsArr );
                    $image = @file_get_contents( $imagePath );
                }
                else
                {
                    throw new \Exception( 'File not found' );
                }
            }


			$response->write( $image );
			return $response->withHeader( 'Content-Type' , mime_content_type( $imagePath ) );

		}
		else
		{
			$obj->error = true;
		}

		return $response->withJson( $obj );

	}

    public function retrieveAll( Request $request , Response $response , $args )
    {
        $data = $request->getAttribute( 'data' );
        $obj = (object)['error' => "false", 'message' => '', 'file_name' => '', 'file_extension' => '', 'content' => ''];
		$jobid = details::exists( $args[ 'id' ] , $data->fmid, $data->clientid );

		$path = ( $request->getServerParams()[ 'SERVER_PORT' ] == '8888' ) ? '/Applications/MAMP/htdocs/storage/slips' : '/var/jobslips/toBox';
		$slip = self::get( $jobid );
		$pathsArr[] = $path . '/' . $slip->file_name;
		$pathsArr[] = '/var/jobslips/inBox/' . $slip->file_name;

		if (!isset($slip->file_name)) {
			$obj->error = true;
			$obj->message = "Slip not yet uploaded";
			return $response->withJson( $obj );
		}

		if ( $imagePath = $this->getPathFromPaths( $pathsArr ) ){}
		else
		{
			$retval = shell_exec( '/var/jobslips/getFromBox.sh ' . $slip->file_name . ' 2>&1' );

			if ($retval == '200') {
				$imagePath = $this->getPathFromPaths($pathsArr);
			}
			else {
				$imagePath = "/var/www/html/sencha/interp_photos/noimage.png";
				//throw new \Exception( 'File not found' );
			}
		}

		set_time_limit(0);
		$file = @fopen($imagePath,"rb");

		$stream = new \Slim\Http\Stream($file); // create a stream instance for the response body\

		return $response->withHeader('Content-Type', 'application/force-download')
						->withHeader('Content-Type', 'application/octet-stream')
						->withHeader('Content-Type', 'application/download')
						->withHeader('Content-Description', 'File Transfer')
						->withHeader('Content-Transfer-Encoding', 'binary')
						->withHeader('Content-Disposition', 'attachment; filename="' . basename($file) . '"')
						->withHeader('Expires', '0')
						->withHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
						->withHeader('Pragma', 'public')
						->withBody($stream); // all stream contents will be sent to the response


	}

    public function check( Request $request , Response $response , $args )
    {

        $data = $request->getAttribute( 'data' );
        $obj = (object)['found' => false];

        if ( $jobid = details::exists( $args[ 'id' ] , $data->fmid ) )
        {
            $path = ( $request->getUri()->getHost() == ('localhost' || '127.0.0.1') ) ? '/Applications/MAMP/htdocs/storage/slips' : '/var/jobslips/toBox';
            $slip = self::get( $jobid );
            if ($slip->file_name == '') {
                $obj->found = false;
            } else {
                $pathsArr[] = $path . '/' . $slip->file_name;
                $pathsArr[] = '/var/jobslips/inBox/' . $slip->file_name;

                if ( $imagePath = $this->getPathFromPaths( $pathsArr ) )
                    $obj->found = true;
                else
                    $obj->found = false;

            }
        }
        else
        {
            $obj->found = false;
        }

        return $response->withJson( $obj );

    }
}
