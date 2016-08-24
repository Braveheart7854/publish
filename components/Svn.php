<?php
/**
 * Created by PhpStorm.
 * User: iFree
 * Date: 2016/8/20
 * Time: 14:26
 */
namespace app\components;

class Svn extends Command
{
    /**
     * 同步主干到本地
     */
    public function downTrunk()
    {
        if (!file_exists(self::$checkout)) {
            die('发布路径不存在：' . self::$checkout);
        }
        $cmd[] = sprintf('cd %s', self::$checkout);
        $cmd[] = sprintf('rm -rf %s', self::$name);
        $cmd[] = sprintf('svn co %s %s %s', $this->getTrunk(), self::$name, $this->_getSvnUser());
        $cmd[] = sprintf('cd %s', self::$name);
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
        $version = $this->getBranchesVersion($this->getBranches() . '/' . $branches);
        $cmd[] = sprintf('cd %s/%s', self::$checkout, self::$name);
        $cmd[] = sprintf('svn merge -%s:HEAD %s %s', $version, $this->getBranches() . '/' . $branches, $this->_getSvnUser());
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
        if (!file_exists(self::$checkout)) {
            die('导出路径不存在：' . self::$checkout);
        }
        $cmd[] = sprintf('cd %s', self::$checkout);
        $cmd[] = sprintf('rm -rf %s', self::$name . '-export');
        $cmd[] = sprintf('svn export %s/%s %s', self::$checkout, self::$name, self::$name . '-export');
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
        foreach (self::$excludes as $val) {
            $val = trim($val);
            if ($val == '') continue;
            $excludes .= "--exclude=\"{$val}\" ";
        }

        if (is_array(self::$remote_host)) {
            foreach (self::$remote_host as $host) {
                $cmd[] = sprintf('rsync -avz --delete %s %s %s@%s:%s',
                    $excludes,
                    self::$checkout . '/' . self::$name . '-export/',
                    self::$remote_user,
                    $host,
                    self::$export
                );
                $cmd[] = sprintf('ssh %s@%s "chown -R nobody:nobody %s"',
                    self::$remote_user, $host, self::$export);
                $command = join(' && ', $cmd);
                $result = $this->_runLocalCommand($command);
                if ($result['status'] != 0) return $result['ooutput'];
            }
        } else {
            $cmd[] = sprintf('rsync -avz --delete %s %s %s@%s:%s',
                $excludes,
                self::$checkout . '/' . self::$name . '-export/',
                self::$remote_user,
                self::$remote_host,
                self::$export
            );
            $cmd[] = sprintf('ssh %s@%s "chown -R nobody:nobody %s"',
                self::$remote_user, self::$remote_host, self::$export);
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
        $cmd[] = sprintf('cd %s/%s', self::$checkout, self::$name);
        $cmd[] = sprintf('svn ci -m "%s" %s', $msg, $this->_getSvnUser());
        $command = join(' && ', $cmd);
        $result = $this->_runLocalCommand($command);
        return $result;
    }
}