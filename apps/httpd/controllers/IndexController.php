<?php

namespace apps\httpd\controllers;

use mix\http\Controller;
use apps\httpd\libraries\doubleArrayTrie;
use apps\httpd\libraries\addWords;

/*
|--------------------------------------------------------------------------
| 敏感词过滤
|--------------------------------------------------------------------------
|
| snippet
|
|
*/
class IndexController extends Controller
{
    // 默认动作
    public function actionIndex()
    {
        return 'Hello, World!';
    }

    public function actionTest()
    {
    	return json_encode(['code' => 0, 'message' => 'OK', 'data' => app()->getPublicPath()]);
    	//print_r(app()->getPublicPath());
    	//return 'test';
    }
    
    /**
     * 添加  可以从MySQL || TXT文档 读敏感词库
     *
     * @var mixed
     */
    public function actionAdd()
	{
		$content = app()->request->get('content') ?? '草';
        //return ['code' => 0, 'message' => 'OK', 'data' => $data];

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

        return json_encode(['code' => 0, 'message' => 'OK']);
	}

    /**
     * 获取替换之后的敏感词
     *
     * @var mixed
     */
	public function actionGet() 
	{
		$content = app()->request->get('content') ?? '邓朴方草拟吗 哈哈哈 达赖';
		//\trie_filter_new();

		//准备要过滤的文本
		//$content = '傻逼草拟吗 哈哈哈';

		$result  = $str = '';

	    if ( $content ) {

	        // 字典树文件路径，默认当时目录下
	        $tree_file = app()->getPublicPath() . '/dict.tree';

	        // 清除文件状态缓存
	        clearstatcache();

	        // 获取请求时，字典树文件的修改时间
	        $new_mtime = filemtime($tree_file);

	        // 获取最新trie-tree对象
	        $resTrie = doubleArrayTrie::getResTrie($tree_file, $new_mtime);

	        // 执行过滤
	        $arrRet = trie_filter_search_all($resTrie, $content);

	        // 提取过滤出的敏感词
	        $result = doubleArrayTrie::getFilterWords($content, $arrRet);


	        $badword = array_combine($result,array_fill(0,count($result),'***'));  

			$str = strtr($content, $badword);  

	    }

		//释放trie资源
		//trie_filter_free($tire);

		return ['code' => 0, 'message' => 'OK', 'data' => $str];
	}

}
