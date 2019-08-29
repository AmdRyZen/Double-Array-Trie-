<?php
/*
|--------------------------------------------------------------------------
| elasticsearch
|--------------------------------------------------------------------------
|
| This value is the name of your application. This value is used when the
| framework needs to place the application's name in a notification or
| any other location as required by the application or its packages.
|
*/

namespace apps\httpd\controllers;

use mix\http\Controller;
use Elasticsearch\ClientBuilder;

class EsController extends Controller
{
    // 默认动作
    public function actionIndex()
    {
        return 'Hello, World!';
    }

    public function actionAdd()
    {
      try {
          $list = \Mix::app()->pdo->createCommand("SELECT * FROM `t_match`")->queryAll();
          
          $client = ClientBuilder::create()->build();

          foreach ($list as $k => $v) {
            $params = [
              'index' => 'match_index',
              'type' => 'match_type',
              'id' => 'match_' . $v['match_id'],
              'body' => [
                'id' => $v['match_id'],
                'match_name' => $v['match_name'],
                'home_team' => $v['home_team'],
                'away_team' => $v['away_team'],
              ],
            ];
            $response = $client->index($params);
          }
          return ['code' => 0, 'message' => 'OK', 'data' => $response];
          } catch (\Throwable $e) {
          return ['code' => -1, 'message' => $e->getMessage()];
      }
    }

    public function actionGet()
    {
     try {
          $kwords = app()->request->get('kwords') ?? '123';
          $size = app()->request->get('size') ?? 10;
          $page = app()->request->get('page') ?? 1;
          $scroll_id = app()->request->get('scroll_id') ?? '';

          $client = ClientBuilder::create()->build();
           //搜索
          $serparams = [ 
            'index' => 'match_index',
            'type' => 'match_type',
            'scroll' => "1m",
            'size' => $size,
            'body' => [
              'query' => [
              'bool' => [
                'filter' => [
                  'bool' => [
                    'must' => [
                      'bool' => [
                        'should' => [
                          ['wildcard' => ['match_name' => $kwords]],
                          ['wildcard' => ['home_team' => $kwords]],
                          ],
                        ],
                      ],
                    ],
                  ],
                ],
              ],
            ],
          ];  

          //$serparams['body']['query']['match']['match_name'] = $kwords;
          if( empty( $scroll_id ) ) {
            $resech = $client->search($serparams);
          } else {
            $resech = $client->scroll( ["scroll_id" => $scroll_id, 'scroll' => '1m'] );
          }

          return ['code' => 0, 'message' => 'OK', 'data' => $resech];
       } catch (\Throwable $e) {
          //return ['code' => 0, 'message' => 'OK', 'data' => []];
          return ['code' => -1, 'message' => $e->getMessage()];
       }
    }
}
