<?php

namespace Pop\Version\Test;

use Pop\Version\Version;

class VersionTest extends \PHPUnit_Framework_TestCase
{

    public function testCompare()
    {
        $this->assertGreaterThan(0, Version::compareVersion('1.0'));
    }

    public function testGetLatest()
    {
        $this->assertGreaterThan(1, Version::getLatest());
    }

    public function testGetLatestFromGitHub()
    {
        $this->assertGreaterThan(1, Version::getLatest(Version::VERSION_SOURCE_GITHUB));
    }

    public function testIsLatest()
    {
        $this->assertTrue(Version::isLatest());
    }

    public function testSystemCheck()
    {
        $system = Version::systemCheck();
        $this->assertEquals(PHP_VERSION, $system['php']['installed']);
    }

}
