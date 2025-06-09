<?php

namespace gplaza\uploadutil\actions;

use Yii;
use yii\base\Action;
use yii\web\Response;
use gplaza\uploadutil\models\DocumentoUpload;

class DeleteUploadAction extends Action
{
    public $uploadPath = '';

    /**
     * @inheritDoc
     */
    public function beforeRun()
    {
        if (empty($this->uploadPath)) {
            $this->uploadPath = '@app/uploads/';
        }

        return parent::beforeRun();
    }

    public function run()
    {
        $error = '';
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = Yii::$app->request->post('key');
        $documento = DocumentoUpload::findOne($id);

        if (!empty($documento)) {

            $fileName = pathinfo($documento->url_archivo_anexo, PATHINFO_BASENAME);
            $path = Yii::getAlias($this->uploadPath) . $fileName;

            $usedArchive = DocumentoUpload::find()
                ->where(["url_archivo_anexo" => $documento->url_archivo_anexo])
                ->andWhere(['!=', 'id', $id])
                ->exists();

            if (!$usedArchive) {
                if (!unlink($path)) {
                    $error = 'No se encuentra el archivo en el sistema.';
                }
            }

            if (!$documento->delete()) {
                $error = 'No se puede borrar el archivo en la BDD.';
            }
        } else {
            $error = 'Se ha producido un error al realizar la operación.';
        }

        Yii::$app->response->data = ['error' => $error];
    }
}
