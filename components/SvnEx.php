<?php
/**
 * Created by PhpStorm.
 * User: iFree
 * Date: 2016/8/20
 * Time: 14:26
 */
namespace app\components;

class SvnEx extends SvnBase
{
    public function setConfig($config)
    {
        $this->config = (object)$config;
    }

    /**
     * 同步主干到本地
     */
    public function downTrunk()
    {
        if (!file_exists($this->config->pubPath)) {
            die('发布路径不存在：' . $this->config->pubPath);
        }
        $cmd[] = sprintf('cd %s', $this->config->pubPath);
        $cmd[] = sprintf('rm -rf %s', $this->config->projectName);
        $cmd[] = sprintf('svn co %s %s %s', $this->getTrunk(), $this->config->projectName, $this->_getSvnCmd());
        $cmd[] = sprintf('cd %s', $this->config->projectName);
        $command = join(' && ', $cmd);
        $result = $this->_runLocalCommand($command);
        if ($result['status'] == 0) return true; return $result['output'];
    }

    /**
     * 合并分支代码
     * @param $branches
     */
    public function mergeBranches($branches)
    {
        $version = $this->getBranchesVersion($branches);
        $cmd[] = sprintf('cd %s/%s', $this->config->pubPath, $this->config->projectName);
        $cmd[] = sprintf('svn merge -%s:HEAD %s %s', $version, $branches, $this->_getSvnCmd());
        $command = join(' && ', $cmd);
        $result = $this->_runLocalCommand($command);
        if ($result['status'] == 0) return true; return $result['output'];
    }

    /**
     * 导出项目文件
     * @param $branches
     * @return mixed
     */
    public function export()
    {
        if (!file_exists($this->config->expPath)) {
            die('导出路径不存在：' . $this->config->expPath);
        }
        $cmd[] = sprintf('cd %s', $this->config->expPath);
        $cmd[] = sprintf('rm -rf %s', $this->config->projectName . '-export');
        $cmd[] = sprintf('svn export %s/%s %s', $this->config->pubPath, $this->config->projectName, $this->config->projectName . '-export');
        $command = join(' && ', $cmd);
        $result = $this->_runLocalCommand($command);
        if ($result['status'] == 0) return true; return $result['output'];
    }

    /**
     * 发布到目标机器
     */
    public function sync()
    {
        $excludes = '';
        foreach ($this->config->excludes as $val) {
            $excludes .= " --exclude=\"{$val}\" ";
        }

        if (is_array($this->config->remote_host)) {
            foreach ($this->config->remote_host as $host) {
                $cmd[] = sprintf('cd %s', $this->config->expPath);
                $cmd[] = sprintf('rsync -avz --delete %s %s %s@%s:%s',
                    $excludes,
                    $this->config->projectName . '-export/',
                    $this->config->remote_user,
                    $host,
                    $this->config->remote_dir
                );
                $cmd[] = sprintf('ssh %s@%s "chown -R nobody:nobody %s"',
                    $this->config->remote_user, $host, $this->config->remote_dir);
                $command = join(' && ', $cmd);
                $result = $this->_runLocalCommand($command);
                if ($result['status'] != 0) return $result['ooutput'];
            }
        } else {
            $cmd[] = sprintf('cd %s', $this->config->expPath);
            $cmd[] = sprintf('rsync -avz --delete %s %s %s@%s:%s',
                $excludes,
                $this->config->projectName . '-export/',
                $this->config->remote_user,
                $this->config->remote_host,
                $this->config->remote_dir
            );
            $cmd[] = sprintf('ssh %s@%s "chown -R nobody:nobody %s"',
                $this->config->remote_user, $this->config->remote_host, $this->config->remote_dir);
            $command = join(' && ', $cmd);
            $result = $this->_runLocalCommand($command);
            if ($result['status'] != 0) return $result['output'];
        }
        return true;
    }

    /**
     * 提交当前分支
     * @param string $msg
     */
    public function commit($msg)
    {
        $cmd[] = sprintf('cd %s/%s', $this->config->pubPath, $this->config->projectName);
        $cmd[] = sprintf('svn ci -m "%s" %s', $msg, $this->_getSvnCmd());
        $command = join(' && ', $cmd);
        $this->_runLocalCommand($command);
    }
}