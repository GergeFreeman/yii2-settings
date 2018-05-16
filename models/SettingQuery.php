<?php

namespace h0rseduck\settings\models;

use yii\db\ActiveQuery;
use h0rseduck\settings\models\enumerables\SettingStatus;

/**
 * Class SettingQuery
 *
 * @package h0rseduck\settings\models
 */
class SettingQuery extends ActiveQuery
{
    /**
     * Scope for settings with active status
     *
     * @return $this
     */
    public function active()
    {
        return $this->andWhere(['status' => SettingStatus::ACTIVE]);
    }

    /**
     * Scope for settings with inactive status
     *
     * @return $this
     */
    public function inactive()
    {
        return $this->andWhere(['status' => SettingStatus::INACTIVE]);
    }
}
