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
        $crawler = $client->request('GET', '/pedido/G24OQ4YQ443O');
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("No hemos encontrado ningún pedido con ese número de seguimiento :-(")')->count()
        );

        // Add an order and verify it
        $clientNames = array(
            "Alfonso M.",
            "John Doe",
            "Vladímir Putin"
        );
        $randName = $clientNames[array_rand($clientNames)];

        $clientEmails = array(
            "hello@alfonsomga.com",
            "john@doe.com",
            "putin@russia.com"
        );
        $randEmail = $clientEmails[array_rand($clientEmails)];

        $crawler = $client->request('POST', 'api/crear/'.$randName.'/'.$randEmail.'/5000 Twitter Followers/39.95/yoursecretkeyhere');
        $response = $client->getResponse();
        $responseJson = json_decode($client->getResponse(), true);

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for POST api/crear/");
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
        $this->assertEquals('{"estado":"success"}', $responseJson['estado']);
        $this->assertNotEmpty($client->getResponse()->getContent());
    }
}
