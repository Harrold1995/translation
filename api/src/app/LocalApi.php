<?php

namespace App\app;

use App\Services\curl\post;
use App\Services\curl\get;


class LocalApi
{
    /* 
    * Templates
    */
    public static function templates ( $templateContent, $templateParam)
   {
    $url = 'http://10.64.7.194/local_api/public/template';

    $dataLocalAPI = [
        'template' => $templateContent,
        'data' => json_encode($templateParam),
    ];
   
    $response = json_decode(post::call( $url, $dataLocalAPI ));

    if ( $response->error )
    {
        throw new \Exception( $response->errorMessage );
    }
    else
    {
        return $response->template;
    }
   
   }

    /* 
    * URL DETAILS
    */

    public static function URLDetails ($id)
    {
     $urlAPI = 'https://url.allgraduates.com.au/url/'.$id;
    
     $response = json_decode(get::call( $urlAPI));
 
     return $response;
 
    }
 
    /* 
     * URL SHORTENING
     */
 
     public static function URLShorten ($url)
     {
      $urlAPI = 'https://url.allgraduates.com.au/url/shorten';
  
      $dataLocalAPI = [
          'url' => $url
      ];
     
     $response = json_decode(post::call( $urlAPI, $dataLocalAPI ));
     // $response = post::call( $urlAPI, $dataLocalAPI );
 
     if ( $response->error )
     {
         throw new \Exception( $response->error );
     }
     else
     {
         return $response->data;
     }
  
 
     }
 
     /* 
     * GET URL ACCESS INFORMATION
     */
     public static function URLAccessedDetails ( $id)
     {
      $urlAPI = 'https://url.allgraduates.com.au/locations/'.$id;
     
     $response = json_decode(get::call( $urlAPI));
  
     return $response;
 
     }
 
 
     public static function URLGroupAccessedDetails ($ids)
     {
         $urlAPI = 'https://url.allgraduates.com.au/locations';
         // $urlAPI = 'http://glossarydev.allgraduates.com.au/api/public/v1/test';
 
         // $headers = array("Content-type: multipart/form-data","Accept: application/json");
         $headers = [
             'Accept'       => 'application/json',
             'Content-Type' => 'multipart/form-data',
         ];
         $dataLocalAPI = [
             'url_id' => $ids
         ];
 
         $post = array();
         self::http_build_query_for_curl($dataLocalAPI, $post);
         
         $response = json_decode(post::call( $urlAPI, $post ));
         
         
         return $response;
 
     }

     //** Sending Multi dimensional array in Curl */
    public static function http_build_query_for_curl( $arrays, &$new = array(), $prefix = null ) {

        if ( is_object( $arrays ) ) {
            $arrays = get_object_vars( $arrays );
        }

        foreach ( $arrays AS $key => $value ) {
            $k = isset( $prefix ) ? $prefix . '[' . $key . ']' : $key;
            if ( is_array( $value ) OR is_object( $value )  ) {
                self::http_build_query_for_curl( $value, $new, $k );
            } else {
                $new[$k] = $value;
            }
        }
    }

}
