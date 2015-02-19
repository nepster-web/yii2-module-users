<?php

namespace nepster\users\commands;

use nepster\users\models\User;
use yii\helpers\Console;
use yii\log\Logger;
use Yii;

/**
 * Install module users
 */
class InstallController extends \yii\console\Controller
{
    /**
     * @var string
     */
    public $from = "@vendor/nepster-web/yii2-module-users/demo";

    /**
     * @var string
     */
    public $to = "@common/modules/users";

    /**
     * @var string
     */
    public $namespace = "common\\modules\\users";

    /**
     * @var string
     */
    public $extendsController = "yii\\base\\Controller";

    /**
     * Copy files from $fromPath to $toPath
     *
     * @param string $fromPath
     * @param string $toPath
     * @param string $namespace
     */
    protected function copyFiles($fromPath, $toPath, $namespace, $extendsController)
    {
        // trim paths
        $fromPath = rtrim($fromPath, "/\\");
        $toPath = rtrim($toPath, "/\\");
        // get files recursively
        $filePaths = $this->glob_recursive($fromPath . "/*");
        // generate new files
        $results = [];
        foreach ($filePaths as $file) {
            // skip directories
            if (is_dir($file)) {
                continue;
            }
            // calculate new file path and relative file
            $newFilePath = str_replace($fromPath, $toPath, $file);
            $relativeFile = str_replace($fromPath, "", $file);
            // get file content and replace namespace
            $content = file_get_contents($file);
            $content = str_replace("common\\modules\\users", $namespace, $content);
            $content = str_replace("yii\\base\\Controller", $extendsController, $content);

            // save and store result
            if (file_exists($newFilePath)) {
                $results[$relativeFile] = "File already exists ... skipping";
            } else {
                $result = $this->save($newFilePath, $content);
                $results[$relativeFile] = ($result === true ? "success" : $result);
            }
        }
        print_r($results);
    }

    /**
     * Recursive glob
     * Does not support flag GLOB_BRACE
     *
     * @link http://php.net/glob#106595
     *
     * @param string $pattern
     * @param int $flags
     * @return array
     */
    protected function glob_recursive($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);
        foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge($files, $this->glob_recursive($dir . '/' . basename($pattern), $flags));
        }
        return $files;
    }

    /**
     * Saves the code into the file specified by [[path]].
     * Taken/modified from yii\gii\CodeFile
     *
     * @param string $path
     * @param string $content
     * @return string|boolean the error occurred while saving the code file, or true if no error.
     */
    protected function save($path, $content)
    {
        $newDirMode = 0755;
        $newFileMode = 0644;
        $dir = dirname($path);
        if (!is_dir($dir)) {
            $mask = @umask(0);
            $result = @mkdir($dir, $newDirMode, true);
            @umask($mask);
            if (!$result) {
                return "Unable to create the directory '$dir'.";
            }
        }
        if (@file_put_contents($path, $content) === false) {
            return "Unable to write the file '{$path}'.";
        } else {
            $mask = @umask(0);
            @chmod($path, $newFileMode);
            @umask($mask);
        }
        return true;
    }
}