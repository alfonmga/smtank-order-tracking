<?php

namespace OrderTracking\BackendBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PedidosControllerTest extends WebTestCase
{
    public function testCompleteScenario()
    {
        /**
         * Comprobar si al intentar acceder al Backend es redireccionado a la página de login
         */
        $client = static::createClient();
        $client->request('GET', '/backend/');
        $this->assertEquals(302, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /backend/");
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Nombre de usuario:")')->count()
        );
    }
}