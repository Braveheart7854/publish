<?php
/**
 * Created by PhpStorm.
 * User: iFree
 * Date: 2016/8/21
 * Time: 13:12
 */
namespace app\components;

class Command {
    protected static $logFile = null;

    /**
     * @var string 项目标题
     */
    public static $name;
    /**
     * @var string svn账号
     */
    public static $user;
    /**
     * @var string svn密码
     */
    public static $pass;
    /**
     * @var string 分支路径
     */
    public static $trunk;
    /**
     * @var string 导出路径
     */
    public static $export;
    /**
     * @var string 检出路径
     */
    public static $checkout;
    /**
     * @var array 忽略文件列表
     */
    public static $excludes;
    /**
     * @var array 目标机器地址
     */
    public static $remote_host;
    /**
     * @var string 目标机器登录用户
     */
    public static $remote_user;

    public function getBranchesList()
    {
        $cmd[] = sprintf('cd %s/%s', self::$checkout, self::$name);
        $cmd[] = sprintf('svn ls %s %s', $this->getBranches(), $this->_getSvnUser());
        $command = join(' && ', $cmd);
        $result = $this->_runLocalCommand($command);
        if ($result['status'] == 0) {
            foreach ($result['output'] as &$val) {
                $val = str_replace('/', '', $val);
            }
            return $result['output'];
        }
        return [];
    }

    public function getBranchesVersion($branches)
    {
        $cmd[] = sprintf('cd %s/%s', self::$checkout, self::$name);
        $cmd[] = sprintf('svn log --stop-on-copy %s %s |grep "^r"|tail -1|awk \'{print $1}\'', $branches, $this->_getSvnUser());
        $command = join(' && ', $cmd);
        $result = $this->_runLocalCommand($command);
        return $result['output'][0];
    }

    public static function getTrunk()
    {
        return self::$trunk . '/trunk';
    }

    public static function getBranches()
    {
        return self::$trunk . '/branches';
    }

    public static function getTags()
    {
        return self::$trunk . '/tags';
    }

    public static function log($message) {
        if (empty(\Yii::$app->params['logPath'])) return;

        $logDir = \Yii::$app->params['logPath'];
        if (!file_exists($logDir)) return;

        $logFile = realpath($logDir) . '/publish-' . date('Ymd') . '.log';
        if (self::$logFile === null) {
            self::$logFile = fopen($logFile, 'a');
        }

        $message = date('Y-m-d H:i:s -- ') . $message;
        fwrite(self::$logFile, $message . PHP_EOL);
    }

    /**
     * @param $cmd
     * @return string
     */
    protected function _getSvnUser() {
        return sprintf(' --username=%s --password=%s --non-interactive --trust-server-cert',
            escapeshellarg(self::$user), escapeshellarg(self::$pass));
    }

    /**
     * 执行本地机器命令
     * @param $command
     * @return array
     */
    protected function _runLocalCommand($command)
    {
        self::log('------------- start -------------');
        self::log('Exec: ' . $command);
        exec($command, $output, $status);
        self::log('-------------- end --------------');
        return ['status' => $status, 'output' => $output];
    }
}