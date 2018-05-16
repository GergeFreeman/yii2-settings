<?php

namespace h0rseduck\settings\events;

use yii\base\Event;
use yii\base\Model;

/**
 * Class FormEvent
 *
 * @package h0rseduck\settings\events
 */
class FormEvent extends Event
{
    /**
     * @var Model
     */
    private $_form;

    /**
     * @return Model
     */
    public function getForm()
    {
        return $this->_form;
    }

    /**
     * @param Model $form
     */
    public function setForm(Model $form)
    {
        $this->_form = $form;
    }
}
