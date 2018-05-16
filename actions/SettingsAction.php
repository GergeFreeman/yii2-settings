<?php

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace h0rseduck\settings\actions;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use h0rseduck\settings\events\FormEvent;

/**
 * Class SettingsAction.
 */
class SettingsAction extends Action
{
    /**
     * Event is triggered before the settings will be saved.
     * Triggered with \h0rseduck\settings\events\FormEvent.
     */
    const EVENT_BEFORE_SAVE = 'beforeSave';

    /**
     * Event is triggered after the settings have been saved successfully.
     * Triggered with \h0rseduck\settings\events\FormEvent.
     */
    const EVENT_AFTER_SAVE = 'afterSave';

    /**
     * @var string class name of the model which will be used to validate the attributes
     */
    public $modelClass;

    /**
     * @var callable a PHP callable that will be called for save the settings.
     *               If not set, [[saveSettings()]] will be used instead.
     *               The signature of the callable should be:
     *
     * ```php
     * function ($model) {
     *      // $model is the object which will be used to validate the attributes
     * }
     * ```
     */
    public $saveSettings;

    /**
     * @var callable a PHP callable that will be called to prepare a model.
     *               If not set, [[prepareModel()]] will be used instead.
     *               The signature of the callable should be:
     *
     * ```php
     * function ($model) {
     *      // $model is the object which will be used to validate the attributes
     * }
     * ```
     */
    public $prepareModel;

    /**
     * @var string
     */
    public $sectionName;

    /**
     * @var string message to be set on successful save a model
     */
    public $successMessage = 'Settings have been saved successfully.';

    /**
     * @var string the name of the settings view
     */
    public $view = 'settings';

    /**
     * @var array additional view params
     */
    public $viewParams = [];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if ($this->modelClass === null) {
            throw new InvalidConfigException('The "modelClass" property must be set.');
        }
    }

    /**
     * Renders the settings form.
     *
     * @return string
     */
    public function run()
    {
        /* @var $model Model */
        $model = Yii::createObject($this->modelClass);
        $event = Yii::createObject(['class' => FormEvent::class, 'form' => $model]);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $this->trigger(self::EVENT_BEFORE_SAVE, $event);

            $this->saveSettings($model);

            $this->trigger(self::EVENT_AFTER_SAVE, $event);

            if ($this->successMessage !== null) {
                Yii::$app->session->setFlash('success', $this->successMessage);
            }

            return $this->controller->refresh();
        }

        $this->prepareModel($model);

        return $this->controller->render($this->view, ArrayHelper::merge($this->viewParams, [
            'model' => $model,
        ]));
    }

    /**
     * Prepares the model which will be used to validate the attributes.
     *
     * @param Model $model
     */
    protected function prepareModel(Model $model)
    {
        if (is_callable($this->prepareModel)) {
            call_user_func($this->prepareModel, $model);
        } else {
            foreach ($model->attributes() as $attribute) {
                $value = Yii::$app->settings->get($this->getSection($model), $attribute);
                if (!is_null($value)) {
                    $model->{$attribute} = $value;
                }
            }
        }
    }

    /**
     * @param Model $model
     */
    protected function saveSettings(Model $model)
    {
        if (is_callable($this->saveSettings)) {
            call_user_func($this->saveSettings, $model);
        } else {
            foreach ($model->toArray() as $key => $value) {
                Yii::$app->settings->set($this->getSection($model), $key, $value);
            }
        }
    }

    /**
     * @param Model $model
     *
     * @return string
     */
    protected function getSection(Model $model)
    {
        if ($this->sectionName) {
            return $this->sectionName;
        }

        return $model->formName();
    }
}
