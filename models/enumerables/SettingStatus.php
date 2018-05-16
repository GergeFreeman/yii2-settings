<?php

namespace h0rseduck\settings\models\enumerables;

use yii2mod\enum\helpers\BaseEnum;

/**
 * Class SettingStatus
 *
 * @package h0rseduck\settings\models\enumerables
 */
class SettingStatus extends BaseEnum
{
    const ACTIVE = 1;
    const INACTIVE = 0;

    /**
     * @var string message category
     */
    public static $messageCategory = 'h0rseduck.settings';

    /**
     * @var array
     */
    public static $list = [
        self::ACTIVE => 'Active',
        self::INACTIVE => 'Inactive',
    ];
}
