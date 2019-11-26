<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Customer */

$this->title = $model->customer_id;
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->customer_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->customer_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'customer_id',
            'rstl_id',
            'customer_code',
            'customer_name',
            'classification_id',
            'latitude',
            'longitude',
            'head',
            'barangay_id',
            'address',
            'tel',
            'fax',
            'email:email',
            'customer_type_id',
            'business_nature_id',
            'industrytype_id',
            'created_at',
            'customer_old_id',
            'Oldcolumn_municipalitycity_id',
            'Oldcolumn_district',
            'local_customer_id',
        ],
    ]) ?>

</div>