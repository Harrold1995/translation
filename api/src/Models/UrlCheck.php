<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Services\curl\post;

class UrlCheck extends Model{

   protected $table = 'url_check';
   protected $fillable = ['url_id','last_run','pass'];


   /* 
	* Relationship
    */
    
    public function email()
    {
        return $this->belongsToMany('App\Models\Email','url_check_email','url_check_id','email_id');
    }

    public function url() 
    {
        return $this->belongsTo('App\Models\Url');
    }


    /* 
	* Accessor
    */
    public function getLastRunAttribute()
    {
        $date = strtotime($this->attributes['last_run']);
        return date("d-m-Y, g:i A", $date);
    }

    /* 
	   * Function
    */
    public function url_exists($url) { 
    

    //**  S1 */
    // $agent = "Mozilla/4.0 (B*U*S)";
    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, $url);
    // // curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    // curl_setopt($ch, CURLOPT_USERAGENT ,"Mozilla/5.0 (Windows NT 6.0) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1"); 
    // curl_setopt($ch, CURLOPT_VERBOSE, false);
    // // curl_setopt($ch, CURLOPT_TIMEOUT, 45);
    // // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 45);
    // curl_setopt($ch, CURLOPT_HEADER, true);
    // // curl_setopt($ch, CURLOPT_NOBODY, true);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    // curl_setopt($ch, CURLOPT_MAXREDIRS, 5); //follow up to 10 redirections - avoids loops
    // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); //fix for certificate issue
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); //fix for certificate issue
    // $data = curl_exec($ch);
    // $err = curl_error($ch);
    // $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    // curl_close($ch);

    
    //** S2 */
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data    = curl_exec($ch);
    $headers = curl_getinfo($ch);
    $err = curl_error($ch);
    $httpcode = $headers['http_code'];
    curl_close($ch);

      $codes = array(
          0 => 'Domain Not Found', //bl
          100 => 'Continue',
          101 => 'Switching Protocols',
          200 => 'OK',
          201 => 'Created',
          202 => 'Accepted',
          203 => 'Non-Authoritative Information',
          204 => 'No Content',
          205 => 'Reset Content',
          206 => 'Partial Content',
          300 => 'Multiple Choices',
          301 => 'Moved Permanently',
          302 => 'Found',
          303 => 'See Other',
          304 => 'Not Modified',
          305 => 'Use Proxy',
          307 => 'Temporary Redirect',
          400 => 'Bad Request', //bl // above is fail - below is ok
          401 => 'Unauthorized',
          402 => 'Payment Required',
          403 => 'Forbidden',
          404 => 'Not Found', //bl
          405 => 'Method Not Allowed',
          406 => 'Not Acceptable',
          407 => 'Proxy Authentication Required',
          408 => 'Request Timeout', //bl
          409 => 'Conflict',
          410 => 'Gone',
          411 => 'Length Required',
          412 => 'Precondition Failed',
          413 => 'Request Entity Too Large',
          414 => 'Request-URI Too Long',
          415 => 'Unsupported Media Type',
          416 => 'Requested Range Not Satisfiable',
          417 => 'Expectation Failed',
          500 => 'Internal Server Error', //bl
          501 => 'Not Implemented', 
          502 => 'Bad Gateway', //bl
          503 => 'Service Unavailable', //bl
          504 => 'Gateway Timeout', //bl
          505 => 'HTTP Version Not Supported'
      );

      $httpcode_out = 'http: ' . $httpcode . ' (' . $codes[$httpcode] . ')';
      $err = 'curl error: ' . $err;

      $out = array(
          $url,
          $httpcode_out,
          $err,
          $httpcode
      );

    
    // s1: add $httpcode === 403 (Fail)
    // s2: remove $httpcode === 403 (Work)
    if ($httpcode === 0 || $httpcode === 400 || $httpcode === 404 || $httpcode === 408 || $httpcode === 500 || ($httpcode >= 502 && $httpcode <= 504))
    {
        return array(
            'Fail',
            $out
        );
    }
    else
    {
        return array(
            'Work',
            $out
        );
    }


    // if ($httpcode >= 200 && $httpcode < 307)

    //   //** Add 401 to 403 and 405 to be accepted as Valid */
    //   if (($httpcode >= 200 && $httpcode < 307) || ($httpcode >= 401 && $httpcode < 404) || $httpcode === 405)
    //   {//good
    //       return array(
    //           'Work',
    //           $out
    //       );
    //   }
    //   else
    //   {//BAD
    //       return array(
    //           'Fail',
    //           $out
    //       );
    //   }



   }

    function getHttpResponseCode_using_curl($url, $followredirects = true){
        // returns int responsecode, or false (if url does not exist or connection timeout occurs)
        // NOTE: could potentially take up to 0-30 seconds , blocking further code execution (more or less depending on connection, target site, and local timeout settings))
        // if $followredirects == false: return the FIRST known httpcode (ignore redirects)
        // if $followredirects == true : return the LAST  known httpcode (when redirected)
        if(! $url || ! is_string($url)){
            return false;
        }
        $ch = @curl_init($url);
        if($ch === false){
            return false;
        }
        @curl_setopt($ch, CURLOPT_HEADER         ,true);    // we want headers
        @curl_setopt($ch, CURLOPT_NOBODY         ,true);    // dont need body
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER ,true);    // catch output (do NOT print!)
        if($followredirects){
            @curl_setopt($ch, CURLOPT_FOLLOWLOCATION ,true);
            @curl_setopt($ch, CURLOPT_MAXREDIRS      ,10);  // fairly random number, but could prevent unwanted endless redirects with followlocation=true
        }else{
            @curl_setopt($ch, CURLOPT_FOLLOWLOCATION ,false);
        }
    //      @curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,5);   // fairly random number (seconds)... but could prevent waiting forever to get a result
    //      @curl_setopt($ch, CURLOPT_TIMEOUT        ,6);   // fairly random number (seconds)... but could prevent waiting forever to get a result
    //      @curl_setopt($ch, CURLOPT_USERAGENT      ,"Mozilla/5.0 (Windows NT 6.0) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1");   // pretend we're a regular browser
        @curl_exec($ch);
        if(@curl_errno($ch)){   // should be 0
            @curl_close($ch);
            return false;
        }
        $code = @curl_getinfo($ch, CURLINFO_HTTP_CODE); // note: php.net documentation shows this returns a string, but really it returns an int
        @curl_close($ch);
        return $code;
}


}