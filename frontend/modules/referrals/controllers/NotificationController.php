<?php

namespace frontend\modules\referrals\controllers;

use Yii;
use common\models\referral\Notification;
use common\models\referral\NotificationSearch;
use common\models\referral\Agency;
use common\models\referral\Referral;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\lab\exRequestreferral;
use yii\helpers\Json;
use common\components\ReferralFunctions;
use yii\data\ArrayDataProvider;

/**
 * NotificationController implements the CRUD actions for Notification model.
 */
class NotificationController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Notification models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(isset(Yii::$app->user->identity->profile->rstl_id)){
            $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;
            $function= new ReferralFunctions();
            //$notification = json_decode($refcomponent->getNotificationAll($rstlId),true);
            //$count = $notification['count_notification'];
            $query = Notification::find()->where('recipient_id =:recipientId', [':recipientId'=>$rstlId]);
            $notification = $query->orderBy('notification_date DESC')->all();
            $count = $query->count();

        } else {
            //return 'Session time out!';
            return $this->redirect(['/site/login']);
        }

        //$unresponded_notification = !empty($notification['notification']) ? $notification['notification'] : null;
        $list = [];
        if($count > 0){
            //$notice_list = $notification['notification'];
            foreach ($notification as $data) {
                $notification_type = $data->notification_type_id;
                switch($data->notification_type_id){
                    case 1:
                        $agencyName = $this->getAgency($data->sender_id);
                        $checkOwner = $function->checkowner($data->referral_id,$rstlId);
                        $arr_data = ['notice_sent'=>"<b>".$data->sender_name."</b> of <b>".$agencyName."</b> notified a referral request.",'notice_id'=>$data->notification_id,'notification_date'=>$data->notification_date,'referral_id'=>$data->referral_id,'owner'=>$checkOwner,'local_request_id'=>$data->referral->local_request_id,'responded'=>$data->responded];
                    break;
                    case 2:
                        $agencyName = $this->getAgency($data->sender_id);
                        $checkOwner = $function->checkowner($data->referral_id,$rstlId);
                        $arr_data = ['notice_sent'=>"<b>".$data->sender_name."</b> of <b>".$agencyName."</b> confirmed the referral notification.",'notice_id'=>$data->notification_id,'notification_date'=>$data->notification_date,'referral_id'=>$data->referral_id,'owner'=>$checkOwner,'local_request_id'=>$data->referral->local_request_id,'responded'=>$data->responded];
                    break;
                    case 3:
                        $agencyName = $this->getAgency($data->sender_id);
                        $checkOwner = $function->checkowner($data->referral_id,$rstlId);
                        $arr_data = ['notice_sent'=>"<b>".$data->sender_name."</b> of <b>".$agencyName."</b> sent a referral request with referral code <b style='color:#000099;'>".$data->referral->referral_code."</b>",'notice_id'=>$data->notification_id,'notification_date'=>$data->notification_date,'referral_id'=>$data->referral_id,'owner'=>$checkOwner,'local_request_id'=>$data->referral->local_request_id,'responded'=>$data->responded];
                    break;
                }
                array_push($list, $arr_data);
            }
        } else {
            $list = [];
        }

        $notificationDataProvider = new ArrayDataProvider([
            //'key'=>'notification_id',
            //'allModels' => $notification['notification'],
            'allModels' => $list,
            'pagination' => [
                'pageSize' => 10,
            ],
            //'pagination'=>false,
        ]);


        if(\Yii::$app->request->isAjax){
            return $this->renderAjax('notifications_all', [
                'notifications' => $list,
                'count_notice' => $count,
                'notificationProvider' => $notificationDataProvider,
            ]);
        } else {
            return $this->render('notifications_all', [
                'notifications' => $list,
                'count_notice' => $count,
                'notificationProvider' => $notificationDataProvider,
            ]);
        }
    }

    //get unresponded notifications
    public function actionCount_unresponded_notification()
    {   
        if(isset(Yii::$app->user->identity->profile->rstl_id)){
            $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;

            $function= new ReferralFunctions();
            $count_all_notifications = $function->countAllNotification($rstlId);
            
            $notificationCount = Notification::find()
                ->where('recipient_id =:recipientId', [':recipientId'=>$rstlId])
                ->andWhere('responded =:responded',[':responded'=>0])
                ->count();

            return Json::encode(['num_notification'=>$notificationCount,'all_notifications'=>$count_all_notifications]);
        } else {
            //return 'Session time out!';
            return $this->redirect(['/site/login']);
        }
    }

    //get list of unresponded notifications
    public function actionList_unresponded_notification()
    {
        if(isset(Yii::$app->user->identity->profile->rstl_id)){
            $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;
            $function= new ReferralFunctions();
            $query = Notification::find()->where('recipient_id =:recipientId AND responded =:responded', [':recipientId'=>$rstlId,':responded'=>0]);
            $notification = $query->limit(10)->orderBy('notification_date DESC')->all();
            $count = $query->count();
        } else {
            //return 'Session time out!';
            return $this->redirect(['/site/login']);
        }

        //$unresponded_notification = !empty($notification) ? $notification['notification'] : null;

        $notice_list = [];
        if(count($count) > 0) {
            foreach ($notification as $data) {
                $notification_type = $data->notification_type_id;
                switch($data['notification_type_id']){
                    case 1:
                        $agencyName = $this->getAgency($data->sender_id);
                        $checkOwner = $function->checkowner($data->referral_id,$rstlId);
                        $arr_data = ['notice_sent'=>"<b>".$data->sender_name."</b> of <b>".$agencyName."</b> notified a referral request.",'notice_id'=>$data->notification_id,'notification_date'=>$data->notification_date,'referral_id'=>$data->referral_id,'owner'=>$checkOwner,'local_request_id'=>$data->referral->local_request_id];
                    break;
                    case 2:
                        $agencyName = $this->getAgency($data->sender_id);
                        $checkOwner = $function->checkowner($data->referral_id,$rstlId);
                        $arr_data = ['notice_sent'=>"<b>".$data->sender_name."</b> of <b>".$agencyName."</b> confirmed the referral notification.",'notice_id'=>$data->notification_id,'notification_date'=>$data->notification_date,'referral_id'=>$data->referral_id,'owner'=>$checkOwner,'local_request_id'=>$data->referral->local_request_id];
                    break;
                    case 3:
                        $agencyName = $this->getAgency($data->sender_id);
                        $checkOwner = $function->checkowner($data->referral_id,$rstlId);
                        $arr_data = ['notice_sent'=>"<b>".$data->sender_name."</b> of <b>".$agencyName."</b> sent a referral request with referral code <b style='color:#000099;'>".$data->referral->referral_code."</b>",'notice_id'=>$data->notification_id,'notification_date'=>$data->notification_date,'referral_id'=>$data->referral_id,'owner'=>$checkOwner,'local_request_id'=>$data->referral->local_request_id];
                    break;
                }
                array_push($notice_list, $arr_data);
            }
        } else {
            $notice_list = [];
        }

        /*$notificationDataProvider = new ArrayDataProvider([
            //'key'=>'notification_id',
            //'allModels' => $notification['notification'],
            'allModels' => $notice_list,
            'pagination' => [
                'pageSize' => 10,
            ],
            //'pagination'=>false,
        ]);*/

        if(\Yii::$app->request->isAjax){
            return $this->renderAjax('list_unresponded_notification', [
                //'notifications' => $unseen_notification,
                'notifications' => $notice_list,
            ]);
        }

        /*if(\Yii::$app->request->isAjax){
            return $this->renderAjax('list_unresponded_notification', [
                'notifications' => $notice_list,
                'count_notice' => $count,
                'notificationProvider' => $notificationDataProvider,
            ]);
        }*/
    }

    //get list agencies
    private function getAgency($agencyId)
    {
        $agency = Agency::findOne($agencyId);

        if($agency !== null){
            return $agency->name;
        } else {
            return null;
        }
    }

    //get referral code
    private function getReferral($referralId)
    {
        $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;
        $referral = Referral::findOne($referralId);

        if($referral !==  null){
            return $referral;
        } else {
            return null;
        }
    }
}
