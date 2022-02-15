<?php

// namespace API\curl;

namespace App\Services\curl;



class base
{
    private $ch;
    protected $url;
    protected $errorMessage;

    public function __construct( $url ) {
        $this->url = $url;
        $this->init();
        $this->opt( CURLOPT_RETURNTRANSFER , true );
    }

    private function init()
    {
        $this->ch = curl_init( $this->url );
    }

    public function opt( $option , $value )
    {
        curl_setopt( $this->ch , $option , $value );
    }

    protected function setHeaders( array $headers )
    {
        $this->opt( CURLOPT_HTTPHEADER , $headers );
    }

    protected function exec()
    {
        $out = curl_exec( $this->ch );
//			$info = curl_getinfo($this->ch);
//			print_r( $info ); exit;
        $this->error();
        return $out;
    }

    protected function close()
    {
        curl_close( $this->ch );
    }

    protected function error()
    {
        $this->errorMessage = curl_error( $this->ch );
    }

    protected function process()
    {
        $output = $this->exec();
//			print_r( $this ); exit;
//			print_r( $output ); exit;
        $this->close();
        return ( $this->errorMessage ) ? "Error: {$this->errorMessage}" : $output;
    }

}