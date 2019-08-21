<?php
/*
|--------------------------------------------------------------------------
| 敏感词过滤  基于Double-Array Trie 
|--------------------------------------------------------------------------
|
| This value is the name of your application. This value is used when the
| framework needs to place the application's name in a notification or
| any other location as required by the application or its packages.
|
*/

namespace apps\httpd\libraries;

class doubleArrayTrie 
{

	// trie-tree对象
    private static $_resTrie = null;
    // 字典树的更新时间
    private static $_mtime = null;


 	/**
     * 提供trie-tree对象
     *
     * @param $tree_file 字典树文件路径
     * @param $new_mtime 当前调用时字典树的更新时间
     * @return null
     */
    static public function getResTrie( $tree_file, $new_mtime )
    {
        if (is_null(self::$_mtime)) {
            self::$_mtime = $new_mtime;
        }

        if (($new_mtime != self::$_mtime) || is_null(self::$_resTrie)) {
            self::$_resTrie = trie_filter_load($tree_file);
            self::$_mtime = $new_mtime;

            // 输出字典文件重载时间
            echo date('Y-m-d H:i:s') . "\tdictionary reload success!\n";
        }

        return self::$_resTrie;
    }

    /**
     * 从原字符串中提取过滤出的敏感词
     *
     * @param $str 原字符串
     * @param $res 1-3 表示 从位置1开始，3个字符长度
     * @return array
     */
    static public function getFilterWords( $str, $res ): array
    {
        $result = [];
        foreach ($res as $k => $v) {
            $word = substr($str, $v[0], $v[1]);

            if (!in_array($word, $result)) {
                $result[] = $word;
            }
        }
        return $result;
    }
}