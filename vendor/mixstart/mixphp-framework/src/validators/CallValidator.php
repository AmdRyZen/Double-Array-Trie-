<?php

namespace mix\validators;

/**
 * CallValidator类
 * @author 刘健 <coder.liu@qq.com>
 */
class CallValidator extends BaseValidator
{

    // 启用的选项
    protected $_enabledOptions = ['callback'];

    // 回调验证
    protected function callback($param)
    {
        $value = $this->attributeValue;
        if (!call_user_func_array($param, [$value])) {
            // 设置错误消息
            $defaultMessage = "{$this->attribute}是无效的值.";
            $this->setError(__FUNCTION__, $defaultMessage);
            // 返回
            return false;
        }
        return true;
    }

}
