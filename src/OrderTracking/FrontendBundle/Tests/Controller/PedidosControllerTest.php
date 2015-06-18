<?php

namespace OrderTracking\FrontendBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PedidosControllerTest extends WebTestCase
{
    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();
        $client->followRedirects(true);

        // Check if frontend is ok
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /pedido/");
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Seguimiento de pedidos")')->count()
        );

        // Submit a fake code track
        $form = $crawler->filter('input[id="codigoSeguimiento"]')->form();
        $form['codigoSeguimiento'] = 'thiscodenotexists';
        $botonSubmit = $crawler->selectButton('localizarPedido')->form();
        $crawler = $client->submit($botonSubmit);

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("No hemos encontrado ningún pedido con ese número de seguimiento :-(")')->count()
        );

    }
}
