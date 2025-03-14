<?php

namespace gplaza\uploadutil\behaviors;

use Yii;
use yii\helpers\Url;
use yii\base\Behavior;

class DocumentUploadBehavior extends Behavior
{
    public function getDocumentoUpload($info, $archivos, $deleteUrl)
    {
        if (!empty($archivos)) {

            $documentos = [];

            foreach ($archivos as $archivo) {

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
                            'url' => Url::to([$deleteUrl]),
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
                            'url' => Url::to([$deleteUrl]),
                            'key' => $archivo->id,
                        ];
                    }
                }
            }

            return $documentos;
        }

        return false;
    }
}
