<?php

namespace OrderTracking\FrontendBundle\Tests\Controller;

use OrderTracking\BackendBundle\Entity\Pedidos;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontendTest extends WebTestCase
{
    private $em;
    private $container;

    public function setUp()
    {
        self::bootKernel();
        $this->container = static::$kernel->getContainer();
        $this->em = $this->container->get('doctrine')
            ->getManager()
        ;
    }

    public function testCompleteFrontendScenario()
    {
        // Crear nuevo cliente web
        $client = static::createClient();
        $client->followRedirects(true);

        // Comprobar página de inicio
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Seguimiento de pedidos")')->count()
        );

        // @TODO TDD: Comprobar si el cliente puede volver a la tienda (http://smtank.com)

        // Comprobar el estado de un pedido no existente
        $crawler = $client->request('GET', '/pedido/'.$this->container->get('trackcodegenerator')->generate());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("No hemos encontrado ningún pedido con ese número de seguimiento")')->count()
        );

        // Crear nuevo pedido
        $order = new Pedidos();
        $order->setEmailCliente($this->container->get('demodatagenerator')->emailCliente());
        $order->setNombreCliente($this->container->get('demodatagenerator')->nombreCliente());
        $order->setNombreProducto($this->container->get('demodatagenerator')->nombreProducto());
        $order->setPrecioProducto($this->container->get('demodatagenerator')->precioProducto());
        $order->setCodigoSeguimiento($this->container->get('trackcodegenerator')->generate());
        $order->setEstadoPedido($this->container->get('demodatagenerator')->estadoPedido());
        $this->em->persist($order);
        $this->em->flush($order);

        // Comprobar pedido creado
        $this->assertGreaterThan(0, count($this->em->getRepository('OrderTrackingBackendBundle:Pedidos')->findOneBy(
                array('id' => $order->getId())))
        );
        $crawler = $client->request('GET', '/pedido/'.$order->getCodigoSeguimiento());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertNotEmpty($client->getResponse()->getContent());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("'.$order->getCodigoSeguimiento().'")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("'.$order->getNombreCliente().'")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("'.$order->getEmailCliente().'")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("'.$order->getNombreProducto().'")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("'.$order->getPrecioProducto().'")')->count());

        // @TODO TDD: Enviar un mensaje de soporte y comprobar flashmessage + email enviado.

        // Eliminar pedido
        $this->em->remove($order);
        $this->em->flush();
    }
}