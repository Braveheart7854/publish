<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $name
 * @property integer $status
 */
class Project extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'status'], 'integer'],
            [['name', 'trunk', 'checkout', 'export'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'PK',
            'uid' => 'Uid',
            'name' => 'Name',
            'status' => 'Status',
            'trunk' => '分支',
            'checkout' => '检出路径',
            'export' => '导出路径',
        ];
    }
}
