<?php

namespace h0rseduck\settings\tests\data;

/**
 * Class Controller
 *
 * @package h0rseduck\settings\tests\data
 */
class Controller extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function render($view, $params = [])
    {
        return [
            'view' => $view,
            'params' => $params,
        ];
    }
}
