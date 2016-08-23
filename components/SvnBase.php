<?php
/**
 * Created by PhpStorm.
 * User: iFree
 * Date: 2016/8/21
 * Time: 13:12
 */
namespace app\components;

class SvnBase {
    protected static $logFile = null;
    protected $config;

    public function getBranchesList()
    {
        $cmd[] = sprintf('cd %s/%s', $this->config->pubPath, $this->config->projectName);
        $cmd[] = sprintf('svn ls %s %s', $this->getBranches(), $this->_getSvnCmd());
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
        $cmd[] = sprintf('cd %s/%s', $this->config->pubPath, $this->config->projectName);
        $cmd[] = sprintf('svn log --stop-on-copy %s |grep "^r"|tail -1|awk \'{print $1}\'', $branches);
        $command = join(' && ', $cmd);
        $result = $this->_runLocalCommand($command);
        return $result['output'][0];
    }

    public function getTrunk()
    {
        return $this->config->repo . '/trunk';
    }

    public function getBranches()
    {
        return $this->config->repo . '/branches';
    }

    public function getTags()
    {
        return $this->config->repo . '/tags';
    }

    public static function log($message) {
        if (empty(\Yii::$app->params['log.dir'])) return;

        $logDir = \Yii::$app->params['log.dir'];
        if (!file_exists($logDir)) return;

        $logFile = realpath($logDir) . '/walle-svn-' . date('Ymd') . '.log';
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
    protected function _getSvnCmd() {
        return sprintf(' --username=%s --password=%s --non-interactive --trust-server-cert',
            escapeshellarg($this->config->svn_user), escapeshellarg($this->config->svn_pass));
    }

    protected function _runLocalCommand($command)
    {
        self::log('------------- start -------------');
        self::log('Exec: ' . $command);
        exec($command, $output, $status);
        self::log('-------------- end --------------');
        return ['status' => $status, 'output' => $output];
    }
}