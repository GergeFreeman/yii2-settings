<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $model \h0rseduck\settings\models\SettingModel */

$this->title = Yii::t('h0rseduck.settings', 'Create Setting');
$this->params['breadcrumbs'][] = ['label' => Yii::t('h0rseduck.settings', 'Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setting-create">

    <h1><?php echo Html::encode($this->title); ?></h1>

    <?php echo $this->render('_form', [
        'model' => $model,
    ]);
    ?>

</div>
