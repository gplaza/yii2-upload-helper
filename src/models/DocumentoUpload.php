<?php

namespace gplaza\uploadutil\models;

use yii\helpers\Url;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "documento_upload".
 *
 * @property integer $id
 * @property string $model
 * @property integer $model_id
 * @property string $descripcion_archivo
 * @property string $url_archivo_anexo
 */
class DocumentoUpload extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'documento_upload';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model', 'descripcion_archivo', 'url_archivo_anexo'], 'required'],
            [['model_id'], 'integer'],
            [['descripcion_archivo', 'url_archivo_anexo'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model' => 'Model',
            'model_id' => 'Model ID',
            'descripcion_archivo' => 'Descripcion Archivo',
            'url_archivo_anexo' => 'Url Archivo Anexo',
        ];
    }

    public function getExtension()
    {
        return pathinfo($this->url_archivo_anexo, PATHINFO_EXTENSION);
    }

    public function getRealFilename()
    {
        return pathinfo($this->url_archivo_anexo, PATHINFO_BASENAME);
    }

    public function getUrl()
    {
        $protocol = ((getenv('COMPOSE_PROJECT_HOST') === "localhost") ? 'http' : 'https');
        return Url::to('@web/uploads/' . $this->realFilename, $protocol);
    }
}
