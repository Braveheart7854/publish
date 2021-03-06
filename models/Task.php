<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $projectId
 * @property string $title
 * @property integer $status
 * @property string $branches
 * @property string $errorMsg
 */
class Task extends \yii\db\ActiveRecord
{
    public $projectName;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'projectId', 'status'], 'integer'],
            [['title', 'branches', 'errorMsg'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'PK',
            'uid' => '用户ID',
            'projectId' => '项目ID',
            'title' => '标题',
            'status' => '状态',
            'branches' => '发布分支',
            'errorMsg' => '错误信息',
        ];
    }
}