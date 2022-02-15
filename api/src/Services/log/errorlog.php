<?php
/**
 * Created by PhpStorm.
 * User: satish
 * Date: 11/12/19
 * Time: 12:06 PM
 */

namespace API\log;


use API\sql\request as standardSqlRequest;
use Slim\Http\Response;
use Slim\Http\Request;

class errorlog extends standardSqlRequest
{
    function __construct ()
    {
        parent::__construct('error_logs');
    }

    public function createApi (Request $request, Response $response, $args)
    {
        $p = $request->getParsedBody();
        if (!$p) {
            return;
        }
        errorlog::create((object)[
            "job_id" => $p[ 'ref_num' ],
            "message" => $p[ 'message' ],
            "file" => $p[ 'file' ],
            "process" => $p[ 'process' ],
            "project" => $p[ 'project' ]
        ]);
    }

    /**
     * @param $error
     */
    public static function create ($error)
    {
        $e = new self;
        $e->setField('job_id', ($error->job_id) ? $error->job_id : 0);
        $e->setField('message', $error->message);
        $e->setField('file', $error->file);
        $e->setField('process', $error->process);
        $e->setField('project', $error->project);
        $e->insert();
    }
}