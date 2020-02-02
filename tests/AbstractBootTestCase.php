<?php
/**
 * This file is part of the BEAR.Middleware package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\Middleware;

use PHPUnit\Framework\TestCase;

class AbstractBootTestCase extends TestCase
{
    /**
     * @var FakeAppMeta
     */
    protected $appMeta;

    public function setUp() : void
    {
        parent::setUp();
        $this->appMeta = new FakeAppMeta;
        $this->appMeta->name = 'BEAR\Middleware';
        $this->appMeta->tmpDir = __DIR__ . '/tmp';
    }

    public function tearDown() : void
    {
        $unlink = function ($path) use (&$unlink) {
            foreach (glob(rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '*') as $file) {
                is_dir($file) ? $unlink($file) : unlink($file);
                @rmdir($file);
            }
        };
        $unlink($this->appMeta->tmpDir);
    }
}
