<?php

namespace common\models\referral;

use Yii;

/**
 * This is the model class for table "tbl_formop".
 *
 * @property int $formop_id
 * @property int $agency_id
 * @property string $title
 * @property string $number
 * @property string $rev_num
 * @property int $print_format
 * @property string $rev_date
 * @property string $logo_left
 * @property string $logo_right
 */
class Formop extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_formop';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('referraldb');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agency_id', 'title', 'number', 'rev_num', 'print_format', 'rev_date', 'logo_left', 'logo_right'], 'required'],
            [['agency_id', 'print_format'], 'integer'],
            [['title', 'number', 'logo_left', 'logo_right'], 'string', 'max' => 200],
            [['rev_num', 'rev_date'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'formop_id' => 'Formop ID',
            'agency_id' => 'Agency ID',
            'title' => 'Title',
            'number' => 'Number',
            'rev_num' => 'Rev Num',
            'print_format' => 'Print Format',
            'rev_date' => 'Rev Date',
            'logo_left' => 'Logo Left',
            'logo_right' => 'Logo Right',
        ];
    }
}
