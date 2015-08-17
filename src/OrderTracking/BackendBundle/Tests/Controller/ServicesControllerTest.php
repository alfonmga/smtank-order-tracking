<?php

namespace OrderTracking\BackendBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ServicesControllerTest extends WebTestCase
{
    public $trackcodegenerator;

    public function setUp()
    {
        static::bootKernel();
    }

    public function testTrackCodeGenerator()
    {
        $codigoSeguimiento = static::$kernel->getContainer()->get('trackcodegenerator')->generate();
        $this->assertTrue(strlen($codigoSeguimiento) == 12);
    }
}