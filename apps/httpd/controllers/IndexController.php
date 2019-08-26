<?php
/*
|--------------------------------------------------------------------------
| 敏感词过滤
|--------------------------------------------------------------------------
|
| This value is the name of your application. This value is used when the
| framework needs to place the application's name in a notification or
| any other location as required by the application or its packages.
|
*/

namespace apps\httpd\controllers;

use mix\http\Controller;
use apps\httpd\libraries\doubleArrayTrie;
use apps\httpd\libraries\addWords;

class IndexController extends Controller
{
    // 默认动作
    public function actionIndex()
    {
        return 'Hello, World!';
    }

    public function actionTest()
    {
        $table = new \swoole_table(1024);
        //设置表格字段  参数 （字段名：string ， 字段类型：int、float、string ， 长度：int）
        $table->column('id',$table::TYPE_INT,4);
        $table->column('name',$table::TYPE_STRING,64);
        $table->column('price',$table::TYPE_INT,11);
        //创建表格
        $table->create();
        //添加数据  两种方式
        $table->set('iphoneX',['id'=>1,'name'=>'iphoneX','price'=>9999]);

        $ret = $table->get('iphoneX');

    	return json_encode(['code' => 0, 'message' => 'OK', 'data' => $ret]);
    	//print_r(app()->getPublicPath());
    	//return 'test';
    }
    
    /**
    * 添加  可以从MySQL || TXT文档 读敏感词库
    *
    * @param 
    * @return mixd
    */
    public function actionAdd()
	{
      try {
            //敏感词数组 可以录读第五部分的词库 然后生成敏感词文件
            $words = addWords::getWords();
            //创建一个空的trie tree
            $tire = \trie_filter_new();
            //向trie tree中添加敏感词
            foreach ( $words ?: [] as $k => $v) {
                trie_filter_store($tire, $v);
            }
            //生成敏感词文件
            trie_filter_save($tire, app()->getPublicPath() . '/dict.tree');

            return \json_encode(['code' => 0, 'message' => 'OK']);
      } catch (Throwable $e) {
        return \json_encode([ 'code' => -1, 'message' => 'Error' ]);
      }
	}

    /**
    * 获取替换之后的敏感词
    *
    * @param 
    * @return mixd
    */
	public function actionGet() 
	{
		try {
            $content = app()->request->get('content') ?? '邓朴方草拟吗 哈哈哈 达赖';
            //\trie_filter_new();
            $result  = $str = '';

            // 字典树文件路径，默认当时目录下
            $tree_file = app()->getPublicPath() . '/dict.tree';

            // 清除文件状态缓存
            //clearstatcache();

            // 获取请求时，字典树文件的修改时间
            $new_mtime = filemtime($tree_file);

            // 获取最新trie-tree对象
            $resTrie = doubleArrayTrie::getResTrie($tree_file, $new_mtime);

            // 执行过滤
            $arrRet = trie_filter_search_all($resTrie, $content);

            // 提取过滤出的敏感词
            $result = doubleArrayTrie::getFilterWords($content, $arrRet);

            $str = strtr($content, array_combine($result,array_fill(0,count($result),'***')));  

            //释放trie资源
            //trie_filter_free($tire);
            return \json_encode([ 'code' => 0, 'message' => 'OK', 'data' => $str ]);
        } catch (\Throwable $e) {
            return \json_encode([ 'code' => -1, 'message' => 'Error' ]);
        }
	}

}
