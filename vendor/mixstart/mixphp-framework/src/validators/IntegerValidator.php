<?php

namespace mix\validators;

/**
 * IntegerValidator类
 * @author 刘健 <coder.liu@qq.com>
 */
class IntegerValidator extends BaseValidator
{

    // 初始化选项
    protected $_initOptions = ['integer'];

    // 启用的选项
    protected $_enabledOptions = ['unsigned', 'min', 'max', 'length', 'minLength', 'maxLength'];

    // 类型验证
    protected function integer()
    {
        $value = $this->attributeValue;
        if (!Validate::isInteger($value)) {
            // 设置错误消息
            $defaultMessage = "{$this->attribute}只能为整数.";
            $this->setError(__FUNCTION__, $defaultMessage);
            // 返回
            return false;
        }
        return true;
    }

}
