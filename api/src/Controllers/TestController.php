<?php

namespace App\Controllers;
header('Access-Control-Allow-Origin: *');
use Slim\Http\Request;
use Slim\Http\Response;

use App\Models\CMS\Block;
use App\Models\CMS\ClassProperty;
use App\Models\CMS\Image;
use App\Models\CMS\System;
use App\Models\CMS\Page;
use App\Models\CMS\Template;
use App\Models\CMS\TemplateBlock;
use App\Models\CMS\TemplateBlockTag;
use App\Models\CMS\TemplateContent;
use App\Models\CMS\TemplateTag;
use App\Models\CMS\TemplateTagsContentLink;
use App\Models\CMS\TemplateTagProperty;
use App\Models\CMS\PageContentLink;
use App\Controllers\CMS\cms_pages;
use App\Controllers\CMS\template_tags;
use App\Controllers\CMS\templates;
use App\Controllers\CMS\page_content;
use App\Plugins\Plugin;

// use App\Controllers\BaseController;

use App\app\Validator;
use Illuminate\Database\Capsule\Manager as DB;

class TestController extends BaseController
{
    public function index(Request $request, Response $response, array $args)
    {
        $hello = 'Find A Translation CMS';

        $page = cms_pages::instance(1);
        $html = $page->html(0);
        die( $html );


        // $page = cms_pages::instance( 1 );
        // $html = $page->html();
        // die( $html );


        // $main = cms_pages::instance( 2 );

        // $page = template_tags::instance( 57 );
        // $html = $page->html(3);

        // $test= TemplateTagsContentLink::find(40);
        // $test1 = PageContentLink::find(1);
        // $test2 = page_content::get( 33 );

        // return $this->jsonResponse($response, 'success', $html, 201);
 
    }

    public function dbCheck(Request $request, Response $response, array $args)
    {
        $system = System::with(['template','templateBlock','templateTag','block','image','classProperty'])->get();
        $page = Page::with(['system','template'])->get();
        $template = Template::with('system')->get();
        $templateBlock = TemplateBlock::with('system')->get();
        $templateContent = TemplateContent::with(['template','templateBlock'])->get();
        $templateTag = TemplateTag::with(['system','templateTagProperty'])->get();
        $block = Block::with(['system'])->get();
        $templateTagProperty = TemplateTagProperty::with(['templateTag'])->get();
        $templateBlockTag = TemplateBlockTag::with(['templateBlock','templateTag'])->get();
        $image = Image::with(['system'])->get();
        $classProperty = ClassProperty::with(['system'])->get();
        $templateTagsContentLink = TemplateTagsContentLink::with(['parentTag','childTag','block'])->get();
        return $this->jsonResponse($response, 'success', $page, 201);
 
    }

    public function dynamicPage(Request $request, Response $response, array $args)
    {
        $page_name = $args['id'];
        $page_name = str_replace("-", " ", $page_name);

        $getID = Page::where('name',$page_name)->first();
        $test = cms_pages::instance( $getID->id );
        $html = $test->html($getID->id);

        // return $this->jsonResponse($response, 'success', $test, 201);
        // echo '<pre>' . print_r( $test , true ) . '</pre>'; exit;
        die( $html );

    }

    public function playGround(Request $request, Response $response, array $args)
    {
        // $page = cms_pages::instance( 1 );
        // $html = $page->html();

        // die( $html );

        $pattern = '#\{\{(.*?)\}\}#';
        $text1 = ['<h1 class="line-heading text-primary text-center">Choose Your Language </h1>
        {{language_tiles}}
        {{language_select}}
        '];

        $text2 = '<h1 class="line-heading text-primary text-center">Choose Your Language</h1>
        {{language_tiles}}
        {{language_select}}';
    
        foreach($text1 as $val){
            if (preg_match_all($pattern, $val, $matches)) {
                $encoded = array();
                foreach ($matches[1] as $match) {
                    $encoded[$match] = Plugin::$match();
                }
                
                foreach($matches[1] as $match)
                {
                    if(isset($encoded[$match]))
                    {
                        $val = str_replace('{{'.$match.'}}', $encoded[$match], $val);
                    }
                }
                $textArr[] = $val;
            } else {
                $textArr[] = $val;
            }
           
        }
        
		return $textArr[0] ;

    }

    

}