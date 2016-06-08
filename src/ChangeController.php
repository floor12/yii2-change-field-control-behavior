<?php

namespace floor12\yii2ChangeFieldControlBehavior;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;


/**
 * ChangeController implements the CRUD actions for Change model.
 */
class ChangeController extends Controller
{
    /**
     * @inheritdoc
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


    public function actionApprove($id)
    {
        $model = Change::findOne($id);
        if (!$model)
            throw new NotFoundHttpException;
        if ($model->approved || $model->canceled)
            throw new NotFoundHttpException;
        $model->approve();
        $this->redirect(\Yii::$app->request->referrer);

    }

    public function actionCancel($id)
    {
        $model = Change::findOne($id);
        if (!$model)
            throw new NotFoundHttpException;
        if ($model->approved || $model->canceled)
            throw new NotFoundHttpException;
        $model->cancel();
        $this->redirect(\Yii::$app->request->referrer);

    }

    /**
     * Lists all Change models.
     * @return mixed
     */

    public function actionIndex()
    {
        $query = Change::find();

        $params = Yii::$app->request->get('Change');

        if ($params['filter'])
            $query
                ->orFilterWhere(['like', 'title', $params['filter']]);
        if ($params['status'] != '')
            $query->andWhere(['status' => $params['status']]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created' => SORT_DESC]],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('@vendor/floor12/yii2-change-field-control-behavior/views/index.php', [
            'dataProvider' => $dataProvider,
        ]);

    }


}
