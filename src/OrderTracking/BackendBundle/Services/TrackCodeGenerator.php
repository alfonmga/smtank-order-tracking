<?php

namespace OrderTracking\BackendBundle\Services;

/**
 * Generador de códigos de seguimiento.
 *
 * Class TrackCodeGenerator
 * @package OrderTracking\BackendBundle\Services
 */
class TrackCodeGenerator {

    public function generate() {
        $codigoSeguimiento = '';
        for ($i = 0; $i < 12; $i++) {
            $codigoSeguimiento .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('A'), ord('Z')));
        }
        return $codigoSeguimiento;
    }

}