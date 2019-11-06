<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\referraladmin\Testname */

$this->title = 'Create Testname';
$this->params['breadcrumbs'][] = ['label' => 'Testnames', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="testname-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
