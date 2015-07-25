<?php

namespace OrderTracking\FrontendBundle\Tests\Controller;

use OrderTracking\BackendBundle\Entity\Pedidos;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PedidosControllerTest extends WebTestCase
{
    private $em;
    public function setUp()
    {
        self::bootKernel();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;
    }

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
        $crawler = $client->request('GET', '/pedido/G24OQ4YQ443O');
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("No hemos encontrado ningún pedido con ese número de seguimiento :-(")')->count()
        );

        // Add an order and verify it
        $client2 = static::createClient();
        $client2->followRedirects(true);

        $clientNames = array(
            "Alfonso M.",
            "John Doe",
            "Vladímir Putin"
        );
        $clientEmails = array(
            "hello@alfonsomga.com",
            "john@doe.com",
            "putin@russia.com"
        );

        $order = new Pedidos();
        $order->setEmailCliente($clientEmails[array_rand($clientEmails)]);
        $order->setNombreCliente($clientNames[array_rand($clientNames)]);
        $order->setNombreProducto('5000 Twitter Followers');
        $order->setPrecioProducto('39.95');
        $order->setCodigoSeguimiento('W233Q42HC4IO');
        $order->setEstadoPedido('pendiente');

        $this->em->persist($order);
        $this->em->flush($order);

        $client2->request('GET', '/pedido/W233Q42HC4IO');
        $this->assertEquals(200, $client2->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /pedido/x");
        $this->assertNotEmpty($client2->getResponse()->getContent());

        $this->em->remove($order);
        $this->em->flush();

    }
}
