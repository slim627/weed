<?php

namespace CommonBundle\Grid\Printer\PrintHandler;

use CommonBundle\Grid\Exception\PrinterException;
use CommonBundle\Grid\GridBuilder;
use CommonBundle\Grid\Printer\PrintHandler\PrintHandlerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

class PrintPDFHandler implements PrintHandlerInterface
{
    protected $builder;

    protected $container;

    public function __construct(GridBuilder $builder, ContainerInterface $container)
    {
        $this->builder   = $builder;
        $this->container = $container;
    }

    /**
     * @return File
     * @throws PrinterException
     */
    public function getFile()
    {
        $builder = $this->getBuilder();

        $data = $builder->jsonSerialize();
        $filePath = $this->generateHtml($data);

        if(!file_exists($filePath)){
            throw new PrinterException('View not rendered or not wrote to ' . $filePath);
        }

        $filePathWithoutExt = explode('.', $filePath);
        $pdf_file_path = $filePathWithoutExt[0] . '.pdf';
        shell_exec('unoconv -f pdf ' . $filePath . ' > ' . $pdf_file_path);

        return new File($pdf_file_path);
    }

    public function setBuilder(GridBuilder $builder)
    {
        $this->builder = $builder;

        return $this;
    }

    public function getBuilder()
    {
        return $this->builder;
    }

    public function getContainer()
    {
        return $this->container;
    }

    protected function generateHtml($parameters)
    {
        $html = $this->container->get('twig')->render('CommonBundle:Grid:grid.html.twig', ['parameters' => $parameters]);

        $file_path = tempnam(sys_get_temp_dir(), 'grid_printer_');
        file_put_contents($file_path.'.html', $html);
        $file_path = $file_path.'.html';

        return $file_path;
    }
}