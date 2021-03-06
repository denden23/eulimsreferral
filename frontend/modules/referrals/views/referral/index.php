<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;
use kartik\date\DatePicker;
use \yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\referral\ReferralSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Referrals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="referral-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

     <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id'=>'referral-grid',
        'pjax'=>true,
        'pjaxSettings' => [
            'options' => [
                'enablePushState' => false,
            ]
        ],
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Referral</h3>',
            'type'=>'primary',
            'after'=>false,
            //'before'=>Html::button('<i class="glyphicon glyphicon-plus"></i> Create Referral Request', ['value' => Url::to(['referral/create']),'title'=>'Create Referral Request', 'onclick'=>'addSample(this.value,this.title)', 'class' => 'btn btn-success','id' => 'referralcreate']),
            //'before'=>"<button type='button' onclick='LoadModal(\"Create Referral Request\",\"/referrals/referral/create\")' class=\"btn btn-success\"><i class=\"glyphicon glyphicon-plus\"></i> Create Referral Request</button>",
            'before'=>"<span style='color:#000099;'><b>Note:</b> All referral requests with referral code are shown.</span>",
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'header' => 'Referral Code',
                'attribute' => 'referral_code',
                'format' => 'raw',
                //'value' => function($data){ return $data->referral_code;},
                'headerOptions' => ['class' => 'text-center'],
            ],
            [
                'header' => 'Referral Date',
                'attribute' => 'referral_date_time',
                'format' => 'raw',
                'value' => function($data){ return ($data->referral_date_time != "0000-00-00 00:00:00") ? Yii::$app->formatter->asDate($data->referral_date_time, 'php:F j, Y h:i a') : "<i class='text-danger font-weight-bold h5'>Pending referral request</i>";},
                'headerOptions' => ['class' => 'text-center'],
                'filterType'=> GridView::FILTER_DATE,
                'filterWidgetOptions' => [
                    'model' => $searchModel,
                    'options' => ['placeholder' => 'Select referral date'],
                    'attribute' => 'referral_date_time',
                    //'type' => DatePicker::TYPE_INPUT,
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true
                    ]
                ],
            ],
            /*[
                'header' => 'Customer',
                'attribute' => 'customer_id',
                'format' => 'raw',
                'value' => function($data){ 
                    return !empty($data->customer) ? $data->customer->customer_name : null;
                },
                'headerOptions' => ['class' => 'text-center'],
            ],*/
            [
                'header' => 'Customer',
                'attribute' => 'customer_id',
                'format' => 'raw',
                'value' => function($data){ 
                    return !empty($data->customer) ? $data->customer->customer_name : null;
                },
                'headerOptions' => ['class' => 'text-center'],
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => $customers,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Search customer name', 'id' => 'grid-search-customer_id'],
            ],
            /*[
                'header' => 'Referred By',
                'attribute' => 'receiving_agency_id',
                'format' => 'raw',
                'value' => function($data){
                    return !empty($data->customer) ? $data->agencyreceiving->name : null;
                },
                'headerOptions' => ['class' => 'text-center'],
            ], */
            [
                'header' => 'Referred By',
                'attribute' => 'receiving_agency_id',
                'format' => 'raw',
                'value' => function($data){
                    //return !empty($data->customer) ? $data->agencyreceiving->name : null;
                    return !empty($data->agencyreceiving) ? $data->agencyreceiving->name : null;
                },
                'headerOptions' => ['class' => 'text-center'],
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => $agencies,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Search receiving agency', 'id' => 'grid-search-receiving_agency_id'],
            ],
            /*[
                'header' => 'Referred To',
                'attribute' => 'testing_agency_id',
                'format' => 'raw',
                'value' => function($data){
                    return !empty($data->customer) ? $data->agencytesting->name : null;
                },
                'headerOptions' => ['class' => 'text-center'],
            ],*/
            [
                'header' => 'Referred To',
                'attribute' => 'testing_agency_id',
                'format' => 'raw',
                'value' => function($data){
                    //return !empty($data->customer) ? $data->agencytesting->name : null;
                    return !empty($data->agencytesting) ? $data->agencytesting->name : null;
                },
                'headerOptions' => ['class' => 'text-center'],
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => $agencies,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Search testing agency', 'id' => 'grid-search-testing_agency_id'],
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{view}',
                'dropdown' => false,
                'dropdownOptions' => ['class' => 'pull-right'],
                'headerOptions' => ['class' => 'kartik-sheet-style'],
                'buttons' => [
                    'view' => function ($url, $data) {
                        return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value'=>Url::to(['referral/view','id'=>$data->referral_id]),'onclick'=>'window.open(this.value,"_blank")', 'class' => 'btn btn-primary','title' => 'View '.$data->referral_code]);
                        //return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['referral/view', 'id' => $data->referral_id], ['class' => 'btn btn-primary','title' => 'View '.$data->referral_code,'target'=>"_blank"]);
                    },
                ],
            ],
        ],
        'toolbar' => [
            'content'=> Html::a('<i class="glyphicon glyphicon-repeat"></i> Refresh Grid', [Url::to(['/referrals/referral'])], [
                        'class' => 'btn btn-default', 
                        'title' => 'Refresh Grid'
                    ]),
        ],
]); ?>
</div>
