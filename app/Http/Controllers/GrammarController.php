<?php

namespace App\Http\Controllers;

use App\Models\Grammar;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use StanfordNLP\POSTagger;

class GrammarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $client = new Client();
        // echo $request->grammar;
        $words =  '';
        for ($i=0; $i < strlen($request->grammar); $i++){
            $words .= '%'.dechex(ord($request->grammar[$i]));
            // echo $words."\n";
        }
        // return $words;
        // $utf8string = html_entity_decode(preg_replace("/U\+([0-9A-F]{4})/", "&#x\\1;", $request->grammar), ENT_NOQUOTES, 'UTF-8');
        // return $utf8string;
        $uri = "https://chinese.yabla.com/chinese-english-pinyin-dictionary.php?define=".$words;
        $result = $client->get($uri);
        $response = ''.$result->getBody();
        $crawler = new Crawler($response);
        $nodeValues = $crawler->filter('#segmented_results > tbody > tr > td.word')->each(function (Crawler $node ,$I) {
         //get text
         $item = $node->text();
         // echo $item.'<br>';
         return $item;
        });
        
        $path = resource_path() . '/stanford-parser-full-2018-02-27/';

        $parser = new \StanfordNLP\Parser(
          $path . 'stanford-parser.jar',
          // $path . DIRECTORY_SEPARATOR . 'stanford-chinese-corenlp-models.jar'
          //ga tau yang mana
          $path . 'stanford-parser-3.9.1-models.jar'
        );

        $result = $parser->parseSentences($nodeValues);
        var_dump($result);
        // $client = new Client(); //GuzzleHttp\Client
        // $result = $client->post('http://nlp.stanford.edu:8080/parser/index.jsp', [
        //  'headers' => [
        //         'Content-Type' => 'application/x-www-form-urlencoded',
        //         'Referer'     => 'http://nlp.stanford.edu:8080/parser/',
        //  ],
        //  'form_params' => [
        //      //Ini yang bakal di lempar nanti
        //      //Note : ini cuman contoh ya
        //         'query' => $request->grammar,
        //         'parserSelect' => 'Chinese'
        //  ]
        // ]);
        // $response = ''.$result->getBody();
        // // echo $response;
        // $crawler = new Crawler($response);

        // //looping crawler
        // $nodeValues = $crawler->filter('body > div:nth-child(3) > div:nth-child(7)')->each(function (Crawler $node ,$I) {
        //  //get text
        //  $item = $node->text();
        //  return $item;
        // });
        // print_r($nodeValues);

        // $tags = ["PN", "AD","SS", "VC", "NN" ];
         // $bab = 9;
            
         // $query_create="CREATE TEMPORARY TABLE patterns ( pattern varchar(20) )";
         // $query_insert = "insert into patterns VALUES "; 
         // $count=0;
         // foreach ($tags as $value) {
         //     $count+=1;
         //     if($count<count($tags)){
         //         $query_insert.= "('%".$value."%'),";

         //     }else{
         //         $query_insert.= "('%".$value."%');";    
         //     }
                            
         // }

         // $query_data ="select k.GrammarID,
         //           k.Struct,
         //           COUNT(k.GrammarID) AS Total
         //         FROM(
         //             SELECT a.*,p.pattern FROM Grammars a JOIN patterns p ON (a.Struct LIKE p.pattern)
         //             )as k".
         //         " where k.bab =".$bab.
         //         " GROUP BY k.GrammarID ORDER BY Total DESC LIMIT 1";
         // $query_drop = "drop TEMPORARY TABLE patterns";

         //     $res = DB::select($query_create);
         //     $res = DB::select($query_insert);
         //     $res_final = DB::select($query_data);
         //     $res = DB::select($query_drop);
         // $res_final=explode(" ",$res_final[0]->Struct);
         //     $flags =array_fill(0,count($tags), 0); //flags ini di pake untuk nentuin kata mana yg salah (kata ke index x yg isinya 0 berarti salah)

         //     foreach($res_final as $value){
         //         $key = array_search($value, $tags);
         //         $flags[$key]=1;
         //     }
         //     var_dump($flags);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Grammar  $grammar
     * @return \Illuminate\Http\Response
     */
    public function show(Grammar $grammar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Grammar  $grammar
     * @return \Illuminate\Http\Response
     */
    public function edit(Grammar $grammar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Grammar  $grammar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Grammar $grammar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Grammar  $grammar
     * @return \Illuminate\Http\Response
     */
    public function destroy(Grammar $grammar)
    {
        //
    }
}
