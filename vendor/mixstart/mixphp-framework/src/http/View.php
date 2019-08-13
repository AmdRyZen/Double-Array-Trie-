<?php

namespace mix\http;

/**
 * View类
 * @author 刘健 <coder.liu@qq.com>
 */
class View
{

    // 标题
    public $title;

    // 渲染视图
    public function render($__template__, $__data__)
    {
        // 传入变量
        extract($__data__);
        // 生成视图
        $__filepath__ = \Mix::app()->getViewPath() . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, $__template__) . '.php';
        if (!is_file($__filepath__)) {
            throw new \mix\exceptions\ViewException("视图文件不存在：{$__filepath__}");
        }
        ob_start();
        include $__filepath__;
        return ob_get_clean();
    }

}
