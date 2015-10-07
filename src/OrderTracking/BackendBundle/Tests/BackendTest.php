<?php

namespace OrderTracking\BackendBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BackendTest extends WebTestCase
{
    public function testCompleteBackendScenario()
    {
        /**
         * Comprobar si al intentar acceder al backend es redireccionado al no estar logueado como admin.
         */
        $client = static::createClient();
        $client->request('GET', '/backend/nuevo');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Nombre de usuario")')->count()
        );

        // @TODO TDD: Crear un pedido manualmente desde backend y realizar comprobación.
        // @TODO TDD: Editar información de un pedido.
    }
}