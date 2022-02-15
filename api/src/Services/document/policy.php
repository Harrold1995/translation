<?php
	/**
	 * Created by PhpStorm.
	 * User: bhim
	 * Date: 15/1/20
	 * Time: 1:50 PM
	 */

	namespace API\document;


	use API\model\messageModel;
	use API\sql\request as standardSqlRequest;
	use Slim\Http\Response;
	use Slim\Http\Request;
	use API\functions\utility;
	use API\traits\getPathfromPaths;


	class policy extends standardSqlRequest
	{
		use getPathfromPaths;

		function __construct ($fmid)
		{
			parent::__construct('policy_documents');
		}

		public function getPolicyDocument (Request $request, Response $response, $args)
		{
			$messageModel = new messageModel();
			$tokenData = $request->getAttribute('data');

			$this->addField('policy_document_id');
			$this->addField('title');
			$this->addFieldAlias('read', 'ifnull(interpreter_policy_documents_read.`policy_document_id`, null)', 'interpreter_policy_documents_read');
			$this->joins = $this->addLeftJoin('interpreter_policy_documents_read', 'policy_document_id');

			$jn = $this->addLeftJoin('interpreter_policy_documents_read', 'policy_document_id');
			$jn->addJoin('', 'interpreter_id', $tokenData->intid);

			$this->select();

			if ($this->error === true) {
				$returnMessage = $messageModel->returnMessage(true, 'Failed!', $this->error);
			}
			else {
				$returnMessage = $messageModel->returnMessage(false, 'successfully!', $this->rows);
			}

			return $response->withJson($returnMessage);
		}

		public function getPDF (Request $request, Response $response, $args)
		{
			//$obj = (object)['error' => false];
			$pdID = $request->getQueryParam('pdID');

			$result = self::checkPolicyDocuments($pdID);

			$fileName = $result->file_name;

			$path = ($request->getUri()->getHost() == 'localhost' || $request->getUri()->getHost() == '127.0.0.1') ? '/Applications/MAMP/htdocs/storage/slips' : '/var/box/documents/toBox/';
			$pathsArr[] = $path . '/' . $fileName;
			$pathsArr[] = '/var/box/documents/inBox/' . $fileName;

			if ($imagePath = $this->getPathFromPaths($pathsArr)) {
				$image = @file_get_contents($imagePath);
			}
			else {
				$retval = shell_exec('/var/box/documents/getFromBox.sh ' . $fileName . ' 2>&1');

				if ($retval == '200') {
					$imagePath = $this->getPathFromPaths($pathsArr);
				}
				else {
					$imagePath = "/var/www/html/sencha/interp_photos/noimage.png";
					//throw new \Exception( 'File not found' );
				}
				$image = @file_get_contents($imagePath);
			}


			$response->write($image);
			return $response->withHeader('Content-Type', mime_content_type($imagePath));

			//return $response->withJson( $obj );

		}

		public function checkPolicyDocuments($pdID)
		{
			$s = new standardSQLRequest('policy_documents');
			$s->addJoin('documents_box', 'document_box_id');
			$s->where('policy_document_id', $pdID);
			$s->addField('file_name', 'documents_box');
			$s->select();

			if ($s->found) {
				return $s->rows[ 0 ];
			}
		}
	}
