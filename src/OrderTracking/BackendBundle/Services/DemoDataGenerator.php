<?php

namespace OrderTracking\BackendBundle\Services;

/**
 * Datos utilizado para generar pedidos demo.
 *
 * Class DemoDataGenerator
 * @package OrderTracking\BackendBundle\Services
 */
class DemoDataGenerator {

    public function nombreCliente() {
        $nombreClientes = array(
            "Alfonso M.",
            "John Doe",
            "Vladímir Putin",
            "Barack Obama",
            "Jimmy",
            "Christian",
            "Dan",
            "Danny",
            "Apostolis Tristram",
            "José García"
        );
        $data = $nombreClientes[array_rand($nombreClientes)];
        return $data;
    }

    public function emailCliente() {
        $emailClientes = array(
            "hello@alfonso.com",
            "john@doe.com",
            "putin@russia.com",
            "obama@whitehouse.com",
            "dan@gmail.com",
            "1337@leet.com",
            "chris@home.com"
        );
        $data = $emailClientes[array_rand($emailClientes)];
        return $data;
    }

    public function estadoPedido() {
        $estadosPedido = array(
            "pendiente",
            "en progreso",
            "completado",
            "cancelado"
        );
        $data = $estadosPedido[array_rand($estadosPedido)];
        return $data;
    }

    public function nombreProducto() {
        $nombreProductos = array(
            "Seguidores de Twitter",
            "Likes de Facebook",
            "Reproducciones de YouTube",
            "Reproducciones de Vimeo",
            "Retweets de Twitter",
            "Seguidores de Google+",
            "Likes de Instagram",
            "Pack #1 - SEO",
            "Tráfico web",
            "Diseño web",
            "Reproducciones de Soundcloud",
            "Likes de Vine"
        );
        $data = $nombreProductos[array_rand($nombreProductos)];
        return $data;
    }

    public function precioProducto() {
        $precioProductos = array(
            "29,99",
            "9,99",
            "1.203,29",
            "5,99",
            "99,99",
            "26,99",
            "74,00",
            "302,99"
        );
        $data = $precioProductos[array_rand($precioProductos)];
        return $data;
    }

    public function fechaInicio() {
        $date = new \DateTime();
        $data = $date->getTimestamp();
        return $data;
    }

    /**
     * @param $date || fecha en formato timestamp
     * @return \DateTime
     */
    public function fechaRandInferior($date) {
        $newDate = new \DateTime();
        $newDate->setTimestamp($date);
        $newDate->modify('-'.rand(1,28).'days');
        $newDate->modify('-'.rand(1,24).'hours');
        $newDate->modify('-'.rand(1,60).'minutes');
        $newDate->modify('-'.rand(1,60).'seconds');
        return $newDate;
    }

    /**
     * @param $date || fecha en formato timestamp
     * @return \DateTime
     */
    public function fechaRandSuperior($date) {
        $newDate = new \DateTime();
        $newDate->setTimestamp($date);
        $newDate->modify('+'.rand(1,28).'days');
        $newDate->modify('+'.rand(1,24).'hours');
        $newDate->modify('+'.rand(1,60).'minutes');
        $newDate->modify('+'.rand(1,60).'seconds');
        return $newDate;
    }

}