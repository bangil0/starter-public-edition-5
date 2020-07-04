<?php

namespace Common\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class AssetsCompile extends BaseCommand
{
    protected $group       = 'Assets';
    protected $name        = 'assets:compile';
    protected $description = 'Executes prepared tasks for web-assets compilation.';

    protected $tasks;

    public function __construct()
    {
        $this->tasks = config('AssetsCompile')->tasks;
    }

    public function run(array $params)
    {
        if (empty($this->tasks)) {
            return;
        }

        $tasks = [];

        if (empty($params)) {

            $tasks = $this->tasks;

        } else {

            foreach ($params as $name) {

                if (is_int($key = $this->find($name))) {
                    $tasks[] = $this->tasks[$key];
                }
            }
        }

        if (empty($tasks)) {
            return;
        }

        foreach ($tasks as $task) {

            $source = isset($task['source']) ? (string) $task['source'] : '';

            if (isset($task['source'])) {

                if ($source == '') {
                    continue;
                }

                if (!is_file($source)) {
                    continue;
                }

                $task['source'] = $source;
            }

            $destination = isset($task['destination']) ? (string) $task['destination'] : '';

            if (isset($task['destination'])) {

                if ($destination == '') {
                    continue;
                }

                $dir = pathinfo($destination, PATHINFO_DIRNAME);
                file_exists($dir) OR mkdir($dir, DIR_WRITE_MODE, TRUE);

                if (!is_dir($dir)) {
                    continue;
                }

                $task['destination'] = $destination;
            }

            $this->execute($task);

            if ($task['destination'] != '') {

                try {

                    write_file($task['destination'], $task['result']);
                    @chmod($task['destination'], FILE_WRITE_MODE);

                    if (!empty($task['sha384'])) {

                        $destination_hash = $task['destination'].'.sha384';
                        $hash = hash('sha384', $task['result']);
                        write_file($destination_hash, $hash);
                        @chmod($destination_hash, FILE_WRITE_MODE);
                    }

                    if (!empty($task['sha384.base64'])) {

                        $destination_hash = $task['destination'].'.sha384.base64';
                        $hash = base64_encode(hash('sha384', $task['result']));
                        write_file($destination_hash, $hash);
                        @chmod($destination_hash, FILE_WRITE_MODE);
                    }

                    unset($task['result']);

                    CLI::write(CLI::color($task['destination'], 'green'));

                } catch(Exception $e) {

                    CLI::write(CLI::color($e->getMessage(), 'yellow'));
                }
            }
        }

        CLI::newLine();
    }

    protected function find($name)
    {
        $key = array_search($name, array_column($this->tasks, 'name'));

        if (!is_int($key)) {
            $key = false;
        }

        return $key;
    }

    protected function execute(& $task)
    {
        switch ($task['type']) {

            case 'merge_css':

                $this->merge_css($task);

                break;

            case 'merge_js':

                $this->merge_js($task);

                break;

            case 'copy':

                $this->copy($task);

                break;

            case 'less':

                $this->less($task);

                break;

            case 'scss':

                $this->scss($task);

                break;

            case 'autoprefixer':

                $this->autoprefixer($task);

                break;

            case 'cssmin':

                $this->cssmin($task);

                break;

            case 'jsmin':

                $this->jsmin($task);

                break;

            case 'jsonmin':

                $this->jsonmin($task);

                break;
        }
    }

    protected function copy(& $task)
    {
        $task['result'] = file_get_contents($task['source']);
    }

    protected function merge_css(& $task) {

        $task['result'] = '';

        $sources = [];

        if (!empty($task['sources'])) {

            $first = true;

            foreach ($task['sources'] as & $subtask) {

                if (!in_array($subtask['type'], ['copy', 'less', 'scss', 'autoprefixer', 'cssmin'])) {
                    continue;
                }

                $this->execute($subtask);

                if ($first) {
                    $task['result'] = trim($subtask['result']);
                } else {
                    $task['result'] .= "\n\n".trim($subtask['result']);
                }

                unset($subtask['result']);

                $first = false;
            }
        }
    }

    protected function merge_js(& $task) {

        $task['result'] = '';

        $sources = [];

        if (!empty($task['sources'])) {

            $first = true;

            foreach ($task['sources'] as & $subtask) {

                if (!in_array($subtask['type'], ['copy', 'jsmin'])) {
                    continue;
                }

                $this->execute($subtask);

                if ($first) {
                    $task['result'] = trim($subtask['result']);
                } else {
                    $task['result'] .= "\n\n".trim($subtask['result']);
                }

                unset($subtask['result']);

                $first = false;
            }
        }
    }

    protected function less(& $task)
    {
        $task['result'] = '';

        $renderers = [];

        $renderers['less'] = isset($task['less']) ? $task['less'] : [];
        $renderers['less']['full_path'] = true;

        if (isset($task['autoprefixer'])) {
            $renderers['autoprefixer'] = $task['autoprefixer'];
        }

        if (isset($task['cssmin'])) {
            $renderers['cssmin'] = $task['cssmin'];
        }

        $task['result'] = render($task['source'], null, $renderers);
    }

    protected function scss(& $task)
    {
        $task['result'] = '';

        $renderers = [];

        $renderers['scss'] = isset($task['scss']) ? $task['scss'] : [];
        $renderers['scss']['full_path'] = true;

        if (isset($task['autoprefixer'])) {
            $renderers['autoprefixer'] = $task['autoprefixer'];
        }

        if (isset($task['cssmin'])) {
            $renderers['cssmin'] = $task['cssmin'];
        }

        $task['result'] = render($task['source'], null, $renderers);
    }

    protected function autoprefixer(& $task)
    {
        $task['result'] = '';

        $renderers = [];

        $renderers['autoprefixer'] = isset($task['autoprefixer']) ? $task['autoprefixer'] : [];
        $renderers['autoprefixer']['full_path'] = true;

        if (isset($task['cssmin'])) {
            $renderers['cssmin'] = $task['cssmin'];
        }

        $task['result'] = render($task['source'], null, $renderers);
    }

    protected function cssmin(& $task)
    {
        $task['result'] = '';

        $renderers = [];

        $renderers['cssmin'] = isset($task['cssmin']) ? $task['cssmin'] : [];
        $renderers['cssmin']['full_path'] = true;

        $task['result'] = render($task['source'], null, $renderers);
    }

    protected function jsmin(& $task)
    {
        $task['result'] = '';

        $renderers = [];

        $renderers['jsmin'] = isset($task['jsmin']) ? $task['jsmin'] : [];
        $renderers['jsmin']['full_path'] = true;

        $task['result'] = render($task['source'], null, $renderers);
    }

    protected function jsonmin(& $task)
    {
        $task['result'] = '';

        $renderers = [];

        $renderers['jsonmin'] = isset($task['jsonmin']) ? $task['jsonmin'] : [];
        $renderers['jsonmin']['full_path'] = true;

        $task['result'] = render($task['source'], null, $renderers);
    }

}
