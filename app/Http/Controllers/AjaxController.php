<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client;

class AjaxController extends Controller {

  private $url;
  private $key;
  private $output;
  private $lang;

  public function __construct() {
    $this->url = env('ROOT_API_URL');
    $this->key = env('API_KEY');
    $this->output = env('OUTPUT_API');
    $this->LANG = env('API_LANG');
  }

  private function getToken (){
    $client = new Client();
    $res = $client->request('GET', $this->url.'/api/v1/payexpress?method=getToken&secretkey='.$this->key.'&output='.$this->output);
    return $res->getBody();
  }

  public function hotel (Request $request) {
    $client = new Client();
    $token = $request->input('token');
    $keyword = $request->input('hotel_name');
    $hotelId = $request->input('hotel_id');
    $page = ($request->input('page')) ? $request->input('page') : 1;
    $adult = ($request->input('adult')) ? $request->input('adult') : 2;
    $offset = ($request->input('offset')) ? $request->input('offset') : 10;
    $startDate = ($request->input('startdate')) ? $request->input('startdate') : date('Y-m-d');
    $night = ($request->input('night')) ? $request->input('night') : 1;
    $room = ($request->input('room')) ? $request->input('room') : 1;
    $endDate = ($request->input('enddate')) ? $request->input('enddate') : date('Y-m-d', strtotime("+$night day"));
    $maxStar = ($request->input('star')) ? $request->input('star') : 5;
    $minStar = ($request->input('star')) ? $request->input('star') : 0;
    $maxPrice = ($request->input('maxprice')) ? $request->input('maxprice') : 10000000;
    $minPrice = ($request->input('minprice')) ? $request->input('minprice') : 0;

    $url = $this->url.'/search/hotel?token='.$token.'&output='.$this->output.'&lang='.$this->lang.'&q='.$keyword.'&page='.$page.'&offset='.$offset.'&startdate='.$startDate.'&maxstar='.$maxStar.'&minstar='.$minStar.'&minprice='.$minPrice.'&maxprice='.$maxPrice.'&adult='.$adult.'&room='.$room.'&night='.$night;
    $res = $client->request('GET', $url);

    $data = $res->getBody()->getContents();
    $data = json_decode($data, 1);

    if(isset($data['results']['result'])) {
      foreach ($data['results']['result'] as $key => $value) {
        if($value['hotel_id'] == $hotelId) {
          $res = $client->request('GET', $value['business_uri'] . "&token=$token&output=json");
          $dataHotel = json_decode($res->getBody()->getContents(), 1);
          return $dataHotel;
        }
      }
    }
  }

}
