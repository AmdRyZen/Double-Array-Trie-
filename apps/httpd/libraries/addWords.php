<?php
/*
|--------------------------------------------------------------------------
| 读取敏感词
|--------------------------------------------------------------------------
|
| This value is the name of your application. This value is used when the
| framework needs to place the application's name in a notification or
| any other location as required by the application or its packages.
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