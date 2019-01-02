<?php
/*
|--------------------------------------------------------------------------
| 读取敏感词
|--------------------------------------------------------------------------
|
| snippet
|
|
*/

namespace apps\httpd\libraries;

class addWords
{
	public static function getWords()
	{
		return require (app()->getPublicPath() . '/badword.src.php') ?? [];
	}

}