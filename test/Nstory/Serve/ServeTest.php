<?php

namespace Nstory\Serve;

class ServeTest extends \PHPUnit_Framework_TestCase
{
    private $serve;
    private $tmpfile;

    public function setUp()
    {
        $this->serve = new Serve();
    }

    public function tearDown()
    {
        if ($this->tmpfile && file_exists($this->tmpfile)) {
            unlink($this->tmpfile);
        }
        $this->serve->shutdown();
    }

    public function test_auto_detect_php_exe()
    {
        $php_exe = $this->serve->getPhpExecutable();
        $output = shell_exec("$php_exe -v");
        $this->assertRegExp('/Zend Engine/', $output);
    }

    public function test_manually_set_php_exe()
    {
        $this->serve->setPhpExecutable('/usr/bin/php');
        $this->assertEquals('/usr/bin/php',
            $this->serve->getPhpExecutable());
    }

    public function test_serve_file()
    {
        $this->serve->setRouterScript(
            $this->tmpfile('<?php echo "Hello, World!";')
        );
        $this->serve->start();
        $response = file_get_contents('http://127.0.01:8000');
        $this->assertEquals('Hello, World!', $response);
    }

    public function test_set_root_directory()
    {
        $path = $this->tmpfile('Hi!');
        $file_name = basename($path);
        $dir_path = dirname($path);
        $this->serve->setRootDirectory($dir_path);
        $this->serve->start();
        $response = file_get_contents('http://127.0.0.1:8000/' . $file_name);
        $this->assertEquals('Hi!', $response);
    }

    // public function test_set_host()
    // {
    //     $this->fail('difficult to test ):');
    // }

    public function test_set_port()
    {
        $this->serve->setPort(8021);
        $this->serve->setRouterScript(
            $this->tmpfile('<?php echo "Hello, World!";')
        );
        $this->serve->start();
        $response = file_get_contents('http://127.0.01:8021');
        $this->assertEquals('Hello, World!', $response);
    }

    private function tmpfile($contents)
    {
        // create .build directory if it doesn't exist
        $build_dir = PROJECT_BASE_DIR . '/.build';
        @mkdir($build_dir);

        // create the temp file in .build
        $tmpfile = tempnam($build_dir, 'ServeTest');
        file_put_contents($tmpfile, $contents);
        return ($this->tmpfile = $tmpfile);
    }
}
