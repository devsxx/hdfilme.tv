<?php namespace Anhi\Movie\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Request;
use Symfony\Component\DomCrawler\Crawler;
use Anhi\Movie\Models\Movie;
use Anhi\Shared\Services\CacheService;

class Movies extends Controller
{
    public $implement = ['Backend\Behaviors\ListController','Backend\Behaviors\FormController','Backend\Behaviors\ReorderController',
        'Backend.Behaviors.RelationController'];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';
    public $relationConfig = 'config_relation.yaml';


    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Anhi.Movie', 'movie');
        $this->loadAssets();
    }


    function loadAssets ()
    {

        // $this->addCss('/plugins/anhi/movie/assets/css/select2.min.css');

        $this->addJs('/plugins/anhi/movie/assets/js/jquery.seourl.min.js');
        $this->addJs('/plugins/anhi/movie/assets/js/select2.min.js');
        $this->addJs('/plugins/anhi/movie/assets/js/movie.js');
    }

    function onSave ()
    {
        return;
    }

    function onUpdateMovieInfo ()
    {
        $field = Request::input('field');
        $movieId = Request::input('id');

        $movie = Movie::find($movieId);

        $movie->{$field} = $movie->{$field} ? 0 : ( $field==='slide' ? time() : 1);

        $movie->save();

        return json_encode($movie->{$field} ? 1 : 0);

    }

    function onBuildCache ()
    {
        $cacheService = new CacheService;



        $result = $cacheService->movies();

        return $result;
    }

    function onGetFilmInfo ()
    {
    	return $this->getMovieInfo();
    }

    public function getMovieInfo()
    {
        $url = Request::input("link_filmstart", "http://www.imdb.com/title/tt0245429");
        $url=trim($url);
        $result = array("result" => 0);

        

        if(strpos($url, "filmstarts.de") !== FALSE) {
            //check dupplicate

            ini_set('user_agent', "CharlesUserAgent1.0");


            $content = file_get_contents($url);
            
            $crawler = new Crawler($content);

            if (stristr($url,"serien"))
            {
                // Series

                $result['image'] = $crawler->filter('[property="og:image"]')->first()->attr('content');

                $result["name"] = $crawler->filter('[property="og:title"]')->first()->attr("content");
                //$result["desc"] = $crawler->filter('[description]')->first()->attr("content");
               

                #General Data

                $data_box_table=$crawler->filter('.data_box_table')->first();

                $data_box_table_detail = array();

                $data_box_table->filter('tr')->each(
                    function (Crawler $node, $i) use (&$data_box_table_detail)  {

                    $data_box_table_detail[trim($node->filter('th')->first()->text())] = trim($node->filter('td')->first()->text());
                    }
                );


                #Rebuild Data

                if(isset($data_box_table_detail['Originaltitel'])){
                    $result['english_name'] = $data_box_table_detail['Originaltitel'];
                }

                if(isset($data_box_table_detail['Land'])){
                    $result['country'] = $data_box_table_detail['Land'];
                }            

                if(isset($data_box_table_detail['Nationalität'])){
                    $result['country'] = $data_box_table_detail['Nationalität'];
                }  
                

                if(isset($data_box_table_detail['Länge'])){
                    try {
                        $duration = $data_box_table_detail['Länge'];

                        $duration = str_replace(array(" ", ".", "Std", "Minuten", "Min"), array("","",",",""), $duration);
                        $duration = explode(",", $duration);
                        if(count($duration) > 1) {
                            $duration = (int)$duration[0] * 60 + (int)$duration[1];
                        } else {
                            $duration = $duration[0];
                        }

                        $result["length"] = $duration;

                    } catch (\InvalidArgumentException $e) {
                        $result["length"]=0;                // Handle the current node list is empty..
                    }
                }

       

                if(isset($data_box_table_detail['mit'])){
                    $result["actor"] = str_replace(array('"', "\n", "mehr", ", "), array("", "", "", ","), $data_box_table_detail['mit']);

                }  

                if(isset($data_box_table_detail['Mit'])){
                    $result["actor"] = str_replace(array('"', "\n", "mehr", ", "), array("", "", "", ","), $data_box_table_detail['Mit']);

                }  


                if(isset($data_box_table_detail['Genre'])){
                    $result["genre"] = str_replace(array('"', "\n", "mehr", ", "), array("", "", "", ","), $data_box_table_detail['Genre']);

                }  

                if(isset($data_box_table_detail['Angelegt'])){


                    // Check Year 
                    preg_match_all('/\d+/', $data_box_table_detail['Angelegt'], $matches);
                    if(isset($matches[0][0])){
                        $result["year"] = (int) $matches[0][0];

                    }else{
                        $result["year"]=0;                    
                    }


                }  

                if(isset($data_box_table_detail['Verleiher'])){
                    $result["producer"] = $data_box_table_detail['Verleiher'];

                }  

                if(isset($data_box_table_detail['Regie'])){
                    $result["director"] = $data_box_table_detail['Regie'];

                }  


                if(isset($data_box_table_detail['Starttermin'])){

                    preg_match('#\((.*?)\)#', $data_box_table_detail['Starttermin'], $matches);
                    if(isset($matches[0])){

                        try{

                            $duration = str_replace(array(" ", ".", "Std", "Minuten", "Min"), array("","",",",""), $matches[0]);
                            $duration = str_replace('(', '', $duration);
                            $duration = str_replace(')', '', $duration);
                            $duration = explode(",", $duration);
                            if(count($duration) > 1) {
                                $duration = (int)$duration[0] * 60 + (int)$duration[1];
                            } else {
                                $duration = $duration[0];
                            }
                            $result["length"] = $duration;

                        } catch (\InvalidArgumentException $e) {
                            $result["length"]=0;                // Handle the current node list is empty..
                        }
                    }

                }  
                

                ## Expand Table

                $expendTable=$crawler->filter('.expendTable')->first();

                $expendTable_detail = array(); 

                $expendTable->filter('tr')->each(
                    function (Crawler $node, $i) use (&$expendTable_detail)  {

                        $expendTable_detail[$node->filter('th')->first()->text()] = $node->filter('td')->first()->text();

                    }
                );


                if(isset($expendTable_detail['Verleiher'])){
                    $result['producer'] = $expendTable_detail['Verleiher'];
                }

                if(isset($expendTable_detail['Produktionsjahr'])){
                    $result['year'] = $expendTable_detail['Produktionsjahr'];
                }



                if(isset($expendTable_detail['Originaltitel'])){
                    $result['english_name'] = $expendTable_detail['Originaltitel'];
                }

           
            }
            else
            {
                // Movie 

                $result['image'] = $crawler->filter('[property="og:image"]')->first()->attr('content');

                $result["name"] = $crawler->filter('[property="og:title"]')->first()->attr("content");

                $description="";
                
                if($crawler->filter('[itemprop="description"]')->count()>0)
                    $description=$crawler->filter('[itemprop="description"]')->first()->text();

                $result["desc"] = $description;

                #Meta body

                   #meta-body
                $meta_body_table=$crawler->filter('.meta-body')->first();

                $meta_body_items = array();


                $meta_body_table->filter('.meta-body-item')->each(
                    function (Crawler $node, $i) use (&$meta_body_items)  {

                    $span_obj = $node->filter('.light')->first();
                    $span = $span_obj->text();


                    // $blue_links_list = array();

                    // $node->filter('.blue-link')->each(function (Crawler $node2, $i2) use (&$blue_links_list)  {
                    //     $blue_links_list[]=trim($node2->text());

                    // });

                    $full_string = trim($node->text());
                    $new_string = trim(substr($full_string, strlen($span)));
                    $new_string_list = explode(",", $new_string);
                    $new_string_list=array_map('trim',$new_string_list);
                    $new_string=implode(",", $new_string_list);



                        
                    $meta_body_items[$span] = $new_string;
                    }
                );



                if(isset($meta_body_items['Starttermin'])){

                    preg_match('#\((.*?)\)#', $meta_body_items['Starttermin'], $matches);
                    if(isset($matches[0])){

                        try{

                            $duration = str_replace(array(" ", ".", "Std", "Minuten", "Min"), array("","",",",""), $matches[0]);
                            $duration = str_replace('(', '', $duration);
                            $duration = str_replace(')', '', $duration);
                            $duration = explode(",", $duration);
                            if(count($duration) > 1) {
                                $duration = (int)$duration[0] * 60 + (int)$duration[1];
                            } else {
                                $duration = $duration[0];
                            }
                            $result["length"] = $duration;

                        } catch (\InvalidArgumentException $e) {
                            $result["length"]=0;                // Handle the current node list is empty..
                        }
                    }


                } 

                if(isset($meta_body_items['Von'])){
                    $result["director"] = $meta_body_items['Von'];

                }


                if(isset($meta_body_items['Nationalität'])){
                    $result['country'] = $meta_body_items['Nationalität'];
                }    

                if(isset($meta_body_items['Produktionsland'])){
                    $result['country'] = $meta_body_items['Produktionsland'];
                }  


                if(isset($meta_body_items['mit'])){
                    $result["actor"] = trim(str_replace(array('"', "\n", "mehr", ", "), array("", "", "", ","), $meta_body_items['mit']));

                }  

                if(isset($meta_body_items['Mit'])){
                    $result["actor"] = trim(str_replace(array('"', "\n", "mehr", ", "), array("", "", "", ","), $meta_body_items['Mit']));

                }    

                if(isset($meta_body_items['Genre'])){
                    $result["genre"] = str_replace(array('"', "\n", "mehr", ", "), array("", "", "", ","), $meta_body_items['Genre']);

                }


                if(isset($meta_body_items['Genres'])){
                    $result["genre"] = str_replace(array('"', "\n", "mehr", ", "), array("", "", "", ","), $meta_body_items['Genres']);

                }

                
                



                $more_info_table=$crawler->filter('.ovw-synopsis-info')->first();

                $more_info_items = array();


                $more_info_table->filter('.item')->each(
                    function (Crawler $node, $i) use (&$more_info_items)  {

                    $span_obj = $node->filter('.light')->first();
                    $span = $span_obj->text();


                    // $blue_links_list = array();

                    // $node->filter('.blue-link')->each(function (Crawler $node2, $i2) use (&$blue_links_list)  {
                    //     $blue_links_list[]=trim($node2->text());

                    // });

                    $full_string = trim($node->text());
                    $new_string = trim(substr($full_string, strlen($span)));
                    $new_string_list = explode(",", $new_string);
                    $new_string_list=array_map('trim',$new_string_list);
                    $new_string=implode(",", $new_string_list);



                        
                    $more_info_items[$span] = $new_string;
                    }
                );





                 #Rebuild Data

                if(isset($more_info_items['Originaltitel'])){
                    $result['english_name'] = $more_info_items['Originaltitel'];
                }
                if(isset($more_info_items['Verleiher'])){
                    $result["producer"] = $more_info_items['Verleiher'];

                }  


                if(isset($more_info_items['Produktionsjahr'])){
                    $result['year'] = $more_info_items['Produktionsjahr'];
                }



                // if(isset($data_box_table_detail['Genre'])){
                //     $result["genre"] = str_replace(array('"', "\n", "mehr", ", "), array("", "", "", ","), $data_box_table_detail['Genre']);

                // }  

                // if(isset($data_box_table_detail['Angelegt'])){


                //     // Check Year 
                //     preg_match_all('/\d+/', $data_box_table_detail['Angelegt'], $matches);
                //     if(isset($matches[0][0])){
                //         $result["year"] = (int) $matches[0][0];

                //     }else{
                //         $result["year"]=0;                    
                //     }


                // }  

                





                // $expendTable=$crawler->filter('.expendTable')->first();

                // $expendTable_detail = array(); 

                // $expendTable->filter('tr')->each(
                //     function (Crawler $node, $i) use (&$expendTable_detail)  {

                //         $expendTable_detail[$node->filter('th')->first()->text()] = $node->filter('td')->first()->text();

                //     }
                // );


                // if(isset($expendTable_detail['Verleiher'])){
                //     $result['producer'] = $expendTable_detail['Verleiher'];
                // }

                // if(isset($expendTable_detail['Produktionsjahr'])){
                //     $result['year'] = $expendTable_detail['Produktionsjahr'];
                // }



                // if(isset($expendTable_detail['Originaltitel'])){
                //     $result['english_name'] = $expendTable_detail['Originaltitel'];
                // }


            }



            $result["site_title"] = $result["name"]." stream German HD";
            $result["meta_desc"] = $result["name"]." stream HD Deutsch - HDfilme.TV";
            $result["meta_key"] = $result["name"]." stream";



            $result["result"] = 1;


            //  print_r($result);

            // exit(1);


        } else if(strpos($url, "imdb.com") !== FALSE){
            // if($movie->checkDup($url, "link_imdb")) {
            //     $result["duplicate"] = 1;
            // }
            $id = str_replace(array("http://www.imdb.com/title/", "/"), "", $url);
            $id = explode("?", $id);
            $id = $id[0];
            $api = "http://www.omdbapi.com/?i={$id}&plot=full&r=json&apikey=" . config('apikeys.omdb');
            $apiResult = file_get_contents($api);
            $apiResult = json_decode($apiResult, true);
            if($apiResult["Response"] == "True"){
                $result["name"] = $apiResult["Title"];
                $result["year"] = $apiResult["Year"];
                $result["length"] = explode(" ", $apiResult["Runtime"])[0];
                $result["genre"] = str_replace(" ", "", $apiResult["Genre"]);
                if($apiResult["Director"] != "N/A") {
                    $result["director"] = $apiResult["Director"];
                }
                $result["actor"] = $apiResult["Actors"];
                $result["country"] = $apiResult["Country"];
                $result["imdb"] = $apiResult["imdbRating"];
                $result["type"] = $apiResult["Type"] == "movie" ? 0 : 1;
                $crawler = new Crawler(file_get_contents($url));
                $result["image"] = $crawler->filter('head [property="og:image"]')->first()->attr("content");
                $result["description"] = $crawler->filter('head [name="description"]')->first()->attr("content");
                $result["result"] = 1;
            }
        } else {

        }


        if (Movie::where("link_filmstart", $url)->orWhere('link_imdb', $url)->count()) {
            $result['error'] = 'Link film đã tồn tại';
        }

        return json_encode($result);
    }
}