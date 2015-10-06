<?php
use Tikomatic\Registry;
use Tikomatic\Config;

class RegistryTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $registry = Registry::getInstance();

        $this->assertInstanceOf('Tikomatic\Registry', $registry);

    }

    /**
     * @expectedException DomainException
     */
    public function testException()
    {
        $registry = Registry::getInstance();
        $obj = $registry->get('fooobject');
    }

    public function testSetGet()
    {
        $obj = new \stdClass();
        $registry = Registry::getInstance();
        $registry->set('obj', $obj);

        $newregistry = Registry::getInstance();
        $getobj = $newregistry->get('obj');

        $this->assertInstanceOf('stdClass', $getobj);
    }
}
