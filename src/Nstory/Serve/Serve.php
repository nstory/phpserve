<?php

namespace Nstory\Serve;

class Serve
{
    private $host = '127.0.0.1';
    private $port = 8000;
    private $php_executable;
    private $root_directory;
    private $router_script;
    private $stdout_file;
    private $stderr_file;
    private $process;

    public function start()
    {
        $this->stdout_file = $this->tmpFile();
        $this->stderr_file = $this->tmpFile();
        $stdin = ['pipe', 'r'];
        $stdout = ['file', $this->stdout_file, 'a'];
        $stderr = ['file', $this->stderr_file, 'a'];
        $this->process = proc_open(
            $this->cmdline(),
            [$stdin, $stdout, $stderr],
            $pipes
        );
        register_shutdown_function(function() {
            $this->shutdown();
        });
        sleep(1);
        // FIXME: wait for server to start up (properly)
    }

    private function cmdline()
    {
        $cmdline =  sprintf(
            "%s -S %s:%d",
            $this->getPhpExecutable(),
            $this->host,
            $this->port);

        if ($this->root_directory) {
            $cmdline .= " -t {$this->root_directory}";
        }

        if ($this->router_script) {
            $cmdline .= " {$this->router_script}";
        }

        return $cmdline;
    }

    private function tmpfile()
    {
        return tempnam(sys_get_temp_dir(), 'Serve');
    }

    public function shutdown()
    {
        if ($this->process !== null) {
            proc_terminate($this->process);
            unlink($this->stdout_file);
            unlink($this->stderr_file);
            $this->process = null;
        }
    }

    public function getHost()
    {
        return $this->host;
    }

    /**
     * Host the server will bind to. Defalts to 127.0.0.1 (meaning that
     * connections will only be accepted from the local machine). Set
     * this to "0.0.0.0" if you want connections from any machine
     * to be accepted.
     * @param string $host
     * @return static
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    public function getPhpExecutable()
    {
        if ($this->php_executable === null) {
            return PHP_BINDIR . '/php';
        }
        return $this->php_executable;
    }

    /**
     * The PHP executable invoked to run the web server. Defaults to
     * null (which will cause the path to be auto-determined).
     * @param string $php_executable
     * @return static
     */
    public function setPhpExecutable($php_executable)
    {
        $this->php_executable = $php_executable;
        return $this;
    }

    public function getPort()
    {
        return $this->port;
    }

    /**
     * Port on which the web server will run; defaults to 8000.
     * @param int $port
     * @param static
     */
    public function setPort($port)
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @param string $root_directory the directory out of which files will be
     * served
     * @return static
     */
    public function setRootDirectory($root_directory)
    {
        $this->root_directory = $root_directory;
        return $this;
    }

    public function getRouterScript()
    {
        return $this->route_script;
    }

    /**
     * @param string $router_script
     * @return static
     */
    public function setRouterScript($router_script)
    {
        $this->router_script = $router_script;
        return $this;
    }
}
