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

    public function getUploads($info, $readOnly = false)
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
                    $extension = strtolower($extension);
                    if ($extension === 'jpg' || $extension === 'jpeg' || $extension === 'gif' || $extension === 'png' || $extension === 'jfif') {
                        $documentos[] = [
                            'caption' => $archivo->descripcion_archivo,
                            'size' => $size,
                            'url' => $readOnly ? '' : Url::to([$this->deleteUrl]),
                            'key' => $archivo->id,
                        ];
                    } else {
                        $type = "other";
                        switch ($extension) {
                            case "pdf":
                                $type = "pdf";
                                break;
                            case "xls":
                            case "xlsx":
                            case "docx":
                                $type = "office";
                                break;
                            case "txt":
                            case "sql":
                                $type = "text";
                                break;
                            case "dwg":
                            case "dxf":
                                $type = "cad";
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
                            'url' => $readOnly ? '' : Url::to([$this->deleteUrl]),
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
        return DocumentoUpload::findAll(["model" => get_class($this->owner), "model_id" => $this->owner->id]);
    }
}
