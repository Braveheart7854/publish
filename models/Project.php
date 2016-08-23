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
 * @property string $trunk
 * @property string $checkout
 * @property string $export
 * @property string $remote_host
 * @property string $remote_user
 * @property string $excludes
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
            [['name', 'trunk', 'checkout', 'export', 'remote_host', 'remote_user', 'excludes'], 'string', 'max' => 255],
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
            'remote_host' => '目标机器',
            'remote_user' => '目标用户',
            'excludes' => '忽略文件',
        ];
    }
}