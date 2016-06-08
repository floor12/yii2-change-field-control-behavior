<?php

namespace floor12\yii2ChangeFieldControlBehavior;

use Yii;
use common\models\Change;
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

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Change model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Change model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Change();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($model->backlink)
                return $this->redirect($model->backlink);
            else
                return $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Change model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($model->backlink)
                return $this->redirect($model->backlink);
            else
                return $this->redirect('index');
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Change model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Change model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Change the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Change::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
