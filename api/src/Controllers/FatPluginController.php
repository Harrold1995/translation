<?php
namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\URL;
use App\Models\UrlCheck;
use App\Models\Language;
use App\Models\DocumentLanguage;
use App\Models\Organisation;
use App\app\LocalApi;
use Illuminate\Database\Capsule\Manager as DB;

class FatPluginController extends BaseController
{
 
    public function searchDocument(Request $request, Response $response, array $args)
    {
      $data = $request->getParsedBody();
      $page = $data['page']; 
      $itemPerRow = $data['itemPerRow']; 
      $search = $data['search']; 
      $draw = $data['draw']; 
      $custom_search_document = addslashes($data['document']);
      $languageCode = $data['language'];
      $custom_search_tags = json_decode($data['tags'],true);
      // $custom_search_tags = $data['tags'];
      $custom_search_organisations = json_decode($data['organisations'],true);
      $custom_search_states = json_decode($data['states'],true);
      $custom_search_type = $data['type'];


        //** Get Language Details */
        $languageInfo = Language::where('word_press_code',$languageCode)->first();
        $languageInfo->language == $languageInfo->native_name ? $language_native_name = $languageInfo->language : $language_native_name = $languageInfo->language .' - '. $languageInfo->native_name;

      if($draw == 1) {
        $total = 0;
        $results = [];
      } else {
  
         //** SAVE Searches */
        $searchID = DB::table('searches')->insertGetId([
          'ip4_address' => $_SERVER['HTTP_CLIENT_IP'] ? $_SERVER['HTTP_CLIENT_IP'] : ($_SERVER['HTTP_X_FORWARDED_FOR'] ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']),
        ]);

        $saveLanguageCriteria = DB::table('search_criteria')->insert([
          'search_id' => $searchID,
          'search_criteria' => 'Language',
          'entered_value' => $languageCode
        ]);

        $documentQuery = DB::table('document_languages')
        ->select('document_languages.id', 'documents.id as document_id', 'document_languages.document_name as name','document_languages.language_id','languages.language', 'documents.type', 'organisation.name as organisation','url_check.last_run')
        ->leftJoin('documents', 'document_languages.document_id', '=', 'documents.id')
        ->leftJoin('document_url', 'documents.id', '=', 'document_url.document_id')
        ->leftJoin('urls', 'document_url.url_id', '=', 'urls.id')
        ->leftJoin('url_check', 'urls.id', '=', 'url_check.url_id')
        ->leftJoin('document_language_link as source', 'document_languages.id', '=', 'source.source_document_language')
        ->leftJoin('document_language_link as target', 'document_languages.id', '=', 'target.target_document_language')
        ->leftJoin('languages', 'document_languages.language_id', '=', 'languages.id')
        ->leftJoin('document_organisation', 'document_organisation.document_id', '=', 'documents.id')
        ->leftJoin('organisation', 'document_organisation.organisation_id', '=', 'organisation.id')
        ->leftJoin('document_terms', 'document_languages.id', '=', 'document_terms.document_language_id')
        ->leftJoin('terms', 'document_terms.term_id', '=', 'terms.id');
      

        if(!empty($custom_search_tags)) {
          $documentQuery  ->leftJoin('document_tags', 'document_languages.id', '=', 'document_tags.document_language_id')
          ->leftJoin('tags as tagsDoc', 'document_tags.tag_id', '=', 'tagsDoc.id')
          ->leftJoin('term_tags', 'terms.id', '=', 'term_tags.term_id')
          ->leftJoin('tags as tagsTerm', 'term_tags.tag_id', '=', 'tagsTerm.id');
        }

        $documentQuery->where('languages.word_press_code',$languageCode);
        $documentQuery->where('url_check.pass',1);
        $documentQuery->where(function ($query) {
          $query->whereRaw('document_languages.id = source.source_document_language')
                ->orWhereRaw('document_languages.id = target.target_document_language');
        });

  
        //** Check wether the custom_search_document is empty or not */
        if (!empty($custom_search_document)) {

          $saveKeywordCriteria = DB::table('search_criteria')->insert([
            'search_id' => $searchID,
            'search_criteria' => 'Keyword',
            'entered_value' => $custom_search_document
          ]);

          //** Logical Grouping */
          $documentQuery->where(function ($query) use($custom_search_document) {
            $query->where('document_languages.document_name','LIKE',"%".$custom_search_document."%")
                  ->orWhere('terms.text','LIKE',"%".$custom_search_document."%");
          });
        }

        //** Check wether the tags is empty or not */
         if(!empty($custom_search_tags)) {
           
            foreach($custom_search_tags as $val){
              $tagsIDs[] = $val['id'];
              $tagsCriteria[] = [
                'search_id' => $searchID,
                'search_criteria' => 'Tag',
                'entered_value' => $val['text']
              ];
            }

            $saveTagCriteria = DB::table('search_criteria')->insert($tagsCriteria);

            //** Logical Grouping */
            $documentQuery->where(function ($query) use($tagsIDs) {
              $query->whereIn('tagsDoc.id',$tagsIDs)
                    ->orWhereIn('tagsTerm.id',$tagsIDs);
            });

         } 

        //** Check wether the organisation is empty or not */
        if(!empty($custom_search_organisations)) {

          foreach($custom_search_organisations as $val){
            $orgsIDs[] = $val['id'];
            $orgsCriteria[] = [
              'search_id' => $searchID,
              'search_criteria' => 'Organisation',
              'entered_value' => $val['text']
            ];
          }
          $saveOrgCriteria = DB::table('search_criteria')->insert($orgsCriteria);
          $documentQuery->whereIn('organisation.id',$orgsIDs);
        }

         //** Check wether the state is empty or not */
         if(!empty($custom_search_states)) {

          foreach($custom_search_states as $val){
            $statesIDs[] = $val['text'];
            $stateCriteria[] = [
              'search_id' => $searchID,
              'search_criteria' => 'State',
              'entered_value' => $val['text']
            ];
          }
          $saveStateCriteria = DB::table('search_criteria')->insert($stateCriteria);
          $documentQuery->whereIn('organisation.state',$statesIDs);
        }

        //** Check wether the type is empty or not */
        if(!empty($custom_search_type)) {

          $documentType = in_array('Document', $custom_search_type);

          if($documentType)
            array_push($custom_search_type,"PDF");

          foreach($custom_search_type as $val){
            $typeIDs[] = $val;
            $typeCriteria[] = [
              'search_id' => $searchID,
              'search_criteria' => 'Type',
              'entered_value' => $val
            ];
          }

          $saveTypeCriteria = DB::table('search_criteria')->insert($typeCriteria);
          $documentQuery->whereIn('documents.type',$typeIDs);
        }

        $total = $documentQuery->distinct('document_languages.id')->count('documents.id');
        $documents = $documentQuery->distinct('document_languages.id')
        ->take($itemPerRow)
        ->skip($itemPerRow *  ($page - 1) )
        ->orderBy('url_check.last_run','DESC')
        ->orderBy('document_languages.document_name')
        ->get();

        $results = array();    
        foreach($documents as $k=>$v) {
          $v->criteria = $searchID;
          if ($v->last_run || $v->last_run == null) {
            if($v->last_run == null) {
              $date = 'Not Verified Yet!';
            } else {
              $timestamp = strtotime($v->last_run);
              $date = date("F j, Y", $timestamp);
            }
            $v->last_verified = $date;
            unset($v->last_run);
          }
          
          $comb = $v;
          $results[] = $comb;
        }

      }

      
      $message = [
        'data' => $results,
        'total' => $total,
        'language' => $language_native_name
     ];
    

      return $this->jsonResponse($response, 'success', $message, 201);
      
    }

    public function documentDetails(Request $request, Response $response, array $args)
    {
      $data = $request->getParsedBody();
      $id = $data['id']; 
      $criteria = $data['criteria']; 
      
      //** Get Search Criteria */
      $criteriaQuery = DB::table('search_document_short_url_ids')
        ->leftJoin('search_documents', 'search_document_short_url_ids.search_document_id', '=', 'search_documents.id')
        ->where('search_documents.search_id',$criteria)
        ->first();
      $shortURl = LocalApi::URLDetails($criteriaQuery->short_url_id);

      $document = DocumentLanguage::with(['document.link.urlCheck','language','target' => function ($query) {
        // $query->select(['document_language_link.id','source_document_language','target_document_language','document_languages.id as document_language_id','document_languages.document_name','documents.id as document_id','documents.type'])
        // ->leftJoin('document_languages', 'document_language_link.target_document_language', '=', 'document_languages.id')
        // ->leftJoin('documents', 'document_languages.document_id', '=', 'documents.id');
        $query->with(['targetDocumentLanguage.document','targetDocumentLanguage.language']);

        },'source' => function ($query) {
          $query->with(['sourceDocumentLanguage.document','sourceDocumentLanguage.language']);
          }
        ])
        
      ->where('id',$id)->first();

      //** Identify if document is Multilingual */
      $multiQ = DocumentLanguage::where('document_id', $document->document_id)->count();
      $multilingual = false;
      if($multiQ > 1)
      $multilingual = true;
          


      $message = [
        "result" => $document,
        "multilingual" => $multilingual,
        "search_criteria" => $shortURl
      ];


      return $response->withJson($message, 201, JSON_PRETTY_PRINT);

    }

    public function documentCriteria(Request $request, Response $response, array $args)
    {
      $data = $request->getParsedBody();
      $docId = $data['id']; 
      $criteria = $data['criteria']; 
      $documentUrl = $data['documentUrl']; 
     
      $document = DocumentLanguage::find($docId);


      //** Shorten URL - Document View Search Criteria */
      $shorteningDocView = LocalApi::URLShorten($documentUrl);
      
  
      $saveDocumentCriteriaID = DB::table('search_documents')->insertGetId([
        'search_id' => $criteria,
        'document_language_id' => $document->id,
        'document_view_short_url_id' => $shorteningDocView->id
      ]);
   
      return $this->jsonResponse($response, 'success', $shorteningDocView, 201);
        
    }

    public function getAllTags(Request $request, Response $response, array $args)
    {
      
      $data = $request->getParsedBody();

      $languageCode = $data['language'];

      $mainQuery = "SELECT distinct(tags.tag) as text, tags.id FROM glossary.tags
        left join document_tags on tags.id = document_tags.tag_id
        left join document_languages on document_tags.document_language_id = document_languages.id
        left join languages on document_languages.language_id = languages.id
        left join term_tags on tags.id = term_tags.tag_id
        left join terms on term_tags.term_id = terms.id";

      if(!$data['search']) {
        if($data['tag']) {
          $tags = DB::select($mainQuery
          . " where tags.tag like '%".$data['tag']."%' AND languages.word_press_code ='".$languageCode."' ORDER BY tags.tag");
        } else {
          $tags = [];
        }
       
      } else {
        if(!$data['tag']) {
          $condition =  "where (document_languages.document_name like '%".$data['search']."%' OR terms.text like '%".$data['search']."%') AND languages.word_press_code ='".$languageCode."'";
        }else {
          $condition =  "where (document_languages.document_name like '%".$data['search']."%' OR terms.text like '%".$data['search']."%') AND tags.tag like '%".$data['tag']."%' AND languages.word_press_code ='".$languageCode."'";
        }
        $tags = DB::select($mainQuery
        ." ".$condition.
        " ORDER BY tags.tag");
      }
    
      // $test = ['results' => $tags];
    
      // echo json_encode($tags);
      return $response->withJson($tags, 201, JSON_PRETTY_PRINT);

    } 

    public function getAllOrganisations(Request $request, Response $response, array $args)
    {

      $data = $request->getParsedBody();
     
      $organisations = Organisation::select('id', 'name as text' )->where('name','LIKE',"%".$data['organisation']."%")->orderBy('name')->get();

      return $response->withJson($organisations, 201, JSON_PRETTY_PRINT);
    
    }

    public function getAllStates(Request $request, Response $response, array $args)
    {

      $data = $request->getParsedBody();
     
      $states = Organisation::select('state as text')->distinct('text')->where('state','LIKE',"%".$data['state']."%")->get();

      //$i = 1;
      foreach($states as $key => $val)
      {
        // $states[$key]['id'] = $i++;
        $result[] = [
          'id' => $val['text'],
          'text' => $val['text']
        ];
      }
      return $response->withJson($result, 201, JSON_PRETTY_PRINT);
    
    }

    public function searchCriteria(Request $request, Response $response, array $args)
    {
      
      $data = $request->getParsedBody();

      $criteria = $data['criteria'];

      $mainQuery = DB::select("SELECT searches.id, search_criteria.search_criteria, search_criteria.entered_value FROM searches 
      left join search_criteria on search_criteria.search_id = searches.id
      where searches.id = $criteria");

      $criteriaDetailsType = [];
      foreach ( $mainQuery as $k => $v) {
       
        if( !in_array($v->search_criteria, ['Tag','Organisation','State'], true )) {
          $criteriaDetails[$v->search_criteria] = $v->entered_value;
        }

        if($v->search_criteria == 'Tag') {
          $criteriaDetailsTags[] = [
            $v->search_criteria => $v->entered_value
          ];
        }

        if($v->search_criteria == 'Organisation') {
          $criteriaDetailsOrganisation[] = [
            $v->search_criteria => $v->entered_value
          ];
        }

        if($v->search_criteria == 'State') {
          $criteriaDetailsState[] = [
            $v->search_criteria => $v->entered_value
          ];
        }

        if($v->search_criteria == 'Type') {
          $criteriaDetailsType[] = $v->entered_value;
        }
      }

      $tags = [];
      if($criteriaDetailsTags != null) {
        $criteriaTags = array_column($criteriaDetailsTags, 'Tag');
        $tags = DB::table('tags')
        ->whereIn('tag', $criteriaTags)
        ->get();
      }

      $organisations = [];
      if($criteriaDetailsOrganisation != null) {
        $criteriaOrgs = array_column($criteriaDetailsOrganisation, 'Organisation');
        $organisations = DB::table('organisation')
        ->whereIn('name', $criteriaOrgs)
        ->get();
      }

      $states = [];
      if($criteriaDetailsState != null) {
        $criteriaStates = array_column($criteriaDetailsState, 'State');
        $states = DB::table('organisation')
        ->select('state')
        ->distinct('state')
        ->whereIn('state', $criteriaStates)
        ->get();
        foreach($states as $key => $val)
        {
          $states[$key]->id = $val->state;
        }
      }

      

      $result = [
        'criteria' => $criteriaDetails,
        'tags' => $tags,
        'organisations' => $organisations,
        'states' => $states,
        'type' => $criteriaDetailsType
      ];

      return $response->withJson($result, 201, JSON_PRETTY_PRINT);

    } 

    public function processLink(Request $request, Response $response, array $args)
    {
      $data = $request->getParsedBody();

      $docId = $data['id']; 
      $criteria = $data['criteria'];
 
      $document = DocumentLanguage::with('document.link.urlCheck')
      ->where('id',$docId)->first();

      $longUrl = $document->document->link[0]->url;
      $urlID = $document->document->link[0]->id;

  
      //** Identify if the document url is already shorten */
      if($document->document->link[0]->shorturl == ''){
        //** Shorten URL - Shorten main url documents */
        $docURlShortening = LocalApi::URLShorten($document->document->link[0]->url);
        //** Update URL with short URL */
        $docURL = Url::find($document->document->link[0]->id);
        $docURL->short_url_id = $docURlShortening->id;
        $docURL->shorturl = $docURlShortening->short_url;
        $docURL->save();  
        $newUrl = $docURL->shorturl.'?id='.time().'_'.$criteria;
      } else {
        $newUrl = $document->document->link[0]->shorturl.'?id='.time().'_'.$criteria;
      }

      //** Shorten URL - Document Search Criteria */
      $shorteningDocSC = LocalApi::URLShorten($newUrl);

       //** Get Search Criteria */
       $criteriaQuery = DB::table('search_documents')
       ->where('search_id',$criteria)
       ->first();
      
      $saveDocumentShortURLCriteria = DB::table('search_document_short_url_ids')->insert([
        'search_document_id' => $criteriaQuery->id,
        'short_url_id' => $shorteningDocSC->id
      ]);
  

       //** URL Checked */
       $check_url_status = UrlCheck::url_exists($longUrl);
       
       $check_url_status[0] == 'Work' ? $pass = 1 : $pass = 0;

      //** Update URL Check */
      $url_check = UrlCheck::where('url_id',$urlID)->first();

      if($url_check) {
      //** Update URL Document in url_check table */
        $url_check->pass = $pass;
        $url_check->last_run = date('Y-m-d H:i:s');
        $url_check->save();

      //** Delete previous email notification for broken link */
      $url_check->email()->detach();

      } else {
        //** Add document to url_check table */
        $checked = new UrlCheck();
        $checked->url_id = $urlID;
        $checked->pass = $pass;
        $checked->last_run = date('Y-m-d H:i:s');
        $checked->save();
      }

      $shortURl = LocalApi::URLDetails($shorteningDocSC->id);

      return $response->withJson($shortURl, 201, JSON_PRETTY_PRINT);

    }

}