<?php

namespace CommonBundle\Grid\Printer\PrintHandler;

use CommonBundle\Grid\GridBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

interface PrintHandlerInterface
{
    public function __construct(GridBuilder $builder, ContainerInterface $container);

    /**
     * Print data
     * @return mixed
     */
    public function getFile();
}