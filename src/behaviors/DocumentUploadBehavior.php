<?php

namespace gplaza\uploadutil\behaviors;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\base\Behavior;
use yii\helpers\ArrayHelper;
use gplaza\uploadutil\models\DocumentoUpload;

class DocumentUploadBehavior extends Behavior
{
    public $deleteUrl;

    public function getUploads($info)
    {
        if (!empty($this->getDocumentoUploads())) {

            $documentos = [];

            foreach ($this->getDocumentoUploads() as $archivo) {

                $extension = $archivo->extension;
                $size = @filesize(Yii::getAlias('@app/uploads/' . $archivo->realFilename));

                if ($info == 'link' && $size !== false) {
                    $documentos[] = $archivo->url;
                }
                if ($info == 'config' && $size !== false) {
                    if ($extension === 'jpg' || $extension === 'jpeg' || $extension === 'gif' || $extension === 'png') {
                        $documentos[] = [
                            'caption' => $archivo->descripcion_archivo,
                            'size' => $size,
                            'url' => Url::to([$this->deleteUrl]),
                            'key' => $archivo->id,
                        ];
                    } else {
                        $type = "other";
                        switch ($extension) {
                            case "pdf":
                                $type = "pdf";
                                break;
                            case "xlsx":
                                $type = "office";
                                break;
                            case "txt":
                                $type = "text";
                                break;
                            case "sql":
                                $type = "text";
                                break;
                            case "html":
                                $type = "html";
                                break;
                            case "eps":
                                $type = "gdocs";
                                break;
                            case "ai":
                                $type = "gdocs";
                                break;
                        }
                        $documentos[] = [
                            'type' => $type,
                            'caption' => $archivo->descripcion_archivo,
                            'size' => $size,
                            'url' => Url::to([$this->deleteUrl]),
                            'key' => $archivo->id,
                        ];
                    }
                }
            }

            return $documentos;
        }

        return false;
    }

    public function getDocumentoUploadArray()
    {
        $link = [];

        if (!empty($this->getDocumentoUploads())) {
            foreach ($this->getDocumentoUploads() as $documento) {
                $link[]["link"] = Html::a($documento->descripcion_archivo, Url::to('@web/uploads/' . $documento->realFilename, 'https'), ["download" => $documento->descripcion_archivo]);
            }
        }

        return $link;
    }
    
    public function getDocumentoUploadText()
    {
        $links = ArrayHelper::getColumn($this->getDocumentoUploadArray(), 'link');
        return implode(',', $links);
    }

    public function getDocumentoUploads()
    {
        return DocumentoUpload::findAll(["model" => get_class($this->owner), "model_id" => $this->id]);
    }
}
