<?php

namespace Neton\AppBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class NetonAppBundle extends Bundle
{
    /**
     * Inicializa o Bundle.
     */
    public function boot()
    {
        // Recupera a instância do gerenciador de entidades
        $em = $this->container->get('doctrine.orm.entity_manager');

        // adiciona o modo de hidratação FlatScalar
        $em->getConfiguration()->addCustomHydrationMode('FlatScalar', 'Neton\AppBundle\Doctrine\FlatScalar');
    }
}
