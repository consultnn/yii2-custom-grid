Yii2-Custom-Grid Settings plugin
===========

# Usage session storage

Session storage class
```
namespace backend\components;

use Yii;
use yii\web\Session;
use consultnn\grid\plugins\SettingsStorageInterface;

class StorageSession extends Session implements SettingsStorageInterface
{  
    function has($name)
    {
        $storage = Yii::$app->getSession()->get('StorageSession['.$name.']');
        return isset($storage);
    }
        
    public function get($name)
    {
        if ($this->has($name)) {
            return Yii::$app->getSession()->get('StorageSession['.$name.']');
        } else {
            return null;
        }
    }
    
    public function set($name, $value)
    {
        Yii::$app->getSession()->set('StorageSession['.$name.']', $value);
    }
}
```
Controller for save data
```
namespace backend\controllers;

use Yii;

class SettingsController extends Controller
{
    public function actionSave()
    {
        Yii::$app->user->getSettingsStorage()->set(
            Yii::$app->request->post('storage_id'),
            Yii::$app->request->post('settings')
        );

        return $this->redirect(Yii::$app->request->getReferrer());
    }
}
```

Using storage in plugin
```
use consultnn\grid\GridView;
echo GridView::widget(
    [
        'dataProvider' => $model->search(),
        'id' => 'company',
        'layout' => "{summary}{settings}\n{items}\n{pager}",
        'filterModel' => $model,
        'plugins' => [
            \consultnn\grid\plugins\ResizableColumns::className(),
            [
                'class' => \consultnn\grid\plugins\Settings::className(),
                'url' => \yii\helpers\Url::to('/settings/save'),
                'storage' => Yii::$app->user->getSettingsStorage(),
                'activeColumns' => $model->getActiveColumns(),
            ]
        ]
    ]
);
```
