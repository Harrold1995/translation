<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 23/11/16
 * Time: 3:42 PM
 */

namespace API\jobs;


use API\sql\request as standardSqlRequest ;
use Slim\Http\Response ;
use Slim\Http\Request ;


class sessionalClients extends standardSqlRequest
{

	function __construct()
	{
		parent::__construct( 'role_clients');
	    $this->fetchObj = interpobj::class;
	}

	public static function getClients($roleID) {
        $s = new self() ;
        //get data for the selected state
        $s->addField( 'Client ID', 'clients', 'new_client_id') ;
        $s->addJoin( 'roles' , 'role_id', 'role_id');
        $s->addJoin( 'clients' , 'client_id', 'id' );
        $s->where( 'role_id', $roleID, '=', 'roles') ;
        $s->select() ;
        //echo $s->sql;
        /*
        $queryString = "SELECT c.`Client ID` AS new_client_id FROM role_clients AS a, roles AS b, clients AS c WHERE b.role_id = $staffRoleID AND a.role_id = b.role_id AND c.id = a.client_id";
        $result = $s->query($queryString);
        $clients = array();
        while($data = $result->fetch_object()) {
            $clients[] = $data->new_client_id;
        }
		return $clients ;
        */
	}
}
