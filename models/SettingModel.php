<?php

namespace h0rseduck\settings\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use h0rseduck\settings\models\enumerables\SettingStatus;
use h0rseduck\settings\models\enumerables\SettingType;

/**
 * This is the model class for table "{{%setting}}".
 *
 * @property int $id
 * @property string $type
 * @property string $section
 * @property string $key
 * @property string $value
 * @property bool $status
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 */
class SettingModel extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%setting}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['section', 'key', 'value'], 'required'],
            [['section', 'key'], 'unique', 'targetAttribute' => ['section', 'key']],
            [['value', 'type'], 'string'],
            [['section', 'key', 'description'], 'string', 'max' => 255],
            [['status'], 'integer'],
            ['status', 'default', 'value' => SettingStatus::ACTIVE],
            ['status', 'in', 'range' => SettingStatus::getConstantsByName()],
            [['type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('h0rseduck.settings', 'ID'),
            'type' => Yii::t('h0rseduck.settings', 'Type'),
            'section' => Yii::t('h0rseduck.settings', 'Section'),
            'key' => Yii::t('h0rseduck.settings', 'Key'),
            'value' => Yii::t('h0rseduck.settings', 'Value'),
            'status' => Yii::t('h0rseduck.settings', 'Status'),
            'description' => Yii::t('h0rseduck.settings', 'Description'),
            'created_at' => Yii::t('h0rseduck.settings', 'Created Date'),
            'updated_at' => Yii::t('h0rseduck.settings', 'Updated Date'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * Creates an [[ActiveQueryInterface]] instance for query purpose.
     *
     * @return SettingQuery
     */
    public static function find()
    {
        return new SettingQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        parent::afterDelete();

        Yii::$app->settings->invalidateCache();
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        Yii::$app->settings->invalidateCache();
    }

    /**
     * Return array of settings
     *
     * @return array
     */
    public function getSettings()
    {
        $result = [];
        $settings = static::find()->select(['type', 'section', 'key', 'value'])->active()->asArray()->all();

        foreach ($settings as $setting) {
            $section = $setting['section'];
            $key = $setting['key'];
            $settingOptions = ['type' => $setting['type'], 'value' => $setting['value']];

            if (isset($result[$section][$key])) {
                ArrayHelper::merge($result[$section][$key], $settingOptions);
            } else {
                $result[$section][$key] = $settingOptions;
            }
        }

        return $result;
    }

    /**
     * Set setting
     *
     * @param $section
     * @param $key
     * @param $value
     * @param null $type
     *
     * @return bool
     */
    public function setSetting($section, $key, $value, $type = null)
    {
        $model = static::findOne(['section' => $section, 'key' => $key]);

        if (empty($model)) {
            $model = new static();
        }

        $model->section = $section;
        $model->key = $key;
        $model->value = strval($value);

        if ($type !== null && ArrayHelper::keyExists($type, SettingType::getConstantsByValue())) {
            $model->type = $type;
        } else {
            $model->type = gettype($value);
        }

        return $model->save();
    }

    /**
     * Remove setting
     *
     * @param $section
     * @param $key
     *
     * @return bool|int|null
     *
     * @throws \Exception
     */
    public function removeSetting($section, $key)
    {
        $model = static::findOne(['section' => $section, 'key' => $key]);

        if (!empty($model)) {
            return $model->delete();
        }

        return false;
    }

    /**
     * Remove all settings
     *
     * @return int
     */
    public function removeAllSettings()
    {
        return static::deleteAll();
    }

    /**
     * Activates a setting
     *
     * @param $section
     * @param $key
     *
     * @return bool
     */
    public function activateSetting($section, $key)
    {
        $model = static::findOne(['section' => $section, 'key' => $key]);

        if ($model && $model->status === SettingStatus::INACTIVE) {
            $model->status = SettingStatus::ACTIVE;

            return $model->save(true, ['status']);
        }

        return false;
    }

    /**
     * Deactivates a setting
     *
     * @param $section
     * @param $key
     *
     * @return bool
     */
    public function deactivateSetting($section, $key)
    {
        $model = static::findOne(['section' => $section, 'key' => $key]);

        if ($model && $model->status === SettingStatus::ACTIVE) {
            $model->status = SettingStatus::INACTIVE;

            return $model->save(true, ['status']);
        }

        return false;
    }
}
