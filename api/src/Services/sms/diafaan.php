<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 21/8/18
 * Time: 12:54 PM
 */

namespace API\sms;


use API\curl\post;

class diafaan
{
    private $urlBase = '';
    private $url = '';
    public $postFields = [];

    public function __construct( $mobile , $ref = '' )
    {
//        $this->urlBase = ( $_SERVER[ 'SERVER_PORT' ] == 8888 ) ? '127.0.0.1:8888' : 'localhost';
//        $this->url = 'http://' . $this->urlBase . '/local_api/public/sms/send';

        // Start - Delete this after testing: Satish added it for testing
        $this->urlBase = '10.64.7.194';
        $this->url = 'http://' . $this->urlBase . '/local_api/public/sms/send';
        // End - Delete this after testing: Satish added it for testing

        $this->addPost( 'mobile', str_replace( ' ', '', $mobile ) );
        if( $ref ) $this->addPost( 'jobNum', $ref );
    }

    public function addPost( $fld, $val = false )
    {
        $this->postFields[] = $this->postField( $fld, $val );
    }

    public function system( $system )
    {
        $this->addPost( 'system' , $system );
    }

    public function user( $user )
    {
        $this->addPost( 'user' , $user );
    }

    public function sentByRef( $ref )
    {
        $this->addPost( 'sentByRef' , $ref );
    }

    public function jobNum( $ref )
    {
        $this->addPost( 'jobNum' , $ref );
    }

    private function postField( $fld , $val = false )
    {
        if( ! $val ) $val = $fld ;
        return $fld . '=' . rawurlencode( $val ) ;
    }

    public function send( $message )
    {
        $this->addPost( 'message', $message );
        $curl = post::call( $this->url , $this->content() );
        return $curl ;
    }

    private function content()
    {
//        die( implode( '&' , $this->postFields ) );
        return implode( '&' , $this->postFields ) ;
    }





}