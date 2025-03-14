<?php

namespace gplaza\uploadutil\models;

use Yii;
use yii\base\Action;
use yii\web\Response;
use yii\web\UploadedFile;
use gplaza\uploadutil\models\DocumentoUpload;

class UploadAction extends Action
{
    public $model;
    public $sessionParamName;
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

        $files = UploadedFile::getInstancesByName('medias');

        foreach ($files as $file) {

            $imageNameExploded = explode('.', $file->name);
            $extension = strtolower(end($imageNameExploded));

            $Filename = Yii::$app->security->generateRandomString() . ".{$extension}";
            $path = Yii::getAlias($this->uploadPath) . $Filename;

            if ($file->saveAs($path)) {

                $media = new DocumentoUpload();
                $media->model = $this->model;
                $media->descripcion_archivo = $file->name;
                $media->url_archivo_anexo = $path;

                $session = Yii::$app->session;
                $uploads = ($session->has($this->sessionParamName)) ? $session[$this->sessionParamName] : [];
                $uploads[] = $media;
                $session[$this->sessionParamName] = $uploads;
            } else {
                $error = 'Imposible grabar el archivo subido';
                Yii::error("Error Upload Number : " . $file->error, __METHOD__);
            }

            if (!empty($error)) {
                break;
            }
        }

        Yii::$app->response->data = ['error' => $error];
    }
}
