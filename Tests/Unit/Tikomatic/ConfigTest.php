<?php
use Tikomatic\Registry;
use Tikomatic\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $config = new Config();
        $registry = Registry::getInstance();
        $registry->set('config', $config);

        $testconfig = $registry->get('config');

        $this->assertInstanceOf('Tikomatic\Config', $config);
        $this->assertInstanceOf('Tikomatic\Config', $testconfig);

    }

    /**
     * @expectedException Exception
     */
    public function testReadIniException()
    {
        $config = new Config('iamnonexistent.ini');
    }

    public function testBulkGet() {

        $registry = Registry::getInstance();
        $testconfig = $registry->get('config');

        $this->assertArrayHasKey('language', $testconfig->get() );
        $this->assertArrayHasKey('check-for-updates', $testconfig->get() );

    }

    public function testGet()
    {

        $registry = Registry::getInstance();
        $testconfig = $registry->get('config');


        $this->assertEquals( 'en', $testconfig->get('language') );

        $this->assertEmpty(  $testconfig->get('check-for-updates') );
        $this->assertEquals( '53820', $testconfig->get('port') );
        $this->assertEquals( '1', $testconfig->get('ssl') );

    }


    public function testSet()
    {

        $registry = Registry::getInstance();
        $testconfig = $registry->get('config');

        $this->assertEquals( 'en', $testconfig->get('language') );
        $testconfig->set('language', 'gb');
        $this->assertEquals( 'gb', $testconfig->get('language') );

    }


}
