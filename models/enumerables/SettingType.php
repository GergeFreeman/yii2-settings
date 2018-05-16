<?php

namespace h0rseduck\settings\models\enumerables;

use yii2mod\enum\helpers\BaseEnum;

/**
 * Class SettingStatus
 *
 * @package h0rseduck\settings\models\enumerables
 */
class SettingType extends BaseEnum
{
    const STRING_TYPE = 'string';
    const INTEGER_TYPE = 'integer';
    const BOOLEAN_TYPE = 'boolean';
    const FLOAT_TYPE = 'float';
    const NULL_TYPE = 'null';

    /**
     * @var string message category
     */
    public static $messageCategory = 'h0rseduck.settings';

    /**
     * @var array
     */
    public static $list = [
        self::STRING_TYPE => 'String',
        self::INTEGER_TYPE => 'Integer',
        self::BOOLEAN_TYPE => 'Boolean',
        self::FLOAT_TYPE => 'Float',
        self::NULL_TYPE => 'Null',
    ];
}
