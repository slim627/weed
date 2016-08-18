<?php

namespace CommonBundle\Grid\Printer;

use CommonBundle\Grid\Exception\PrinterException;
use CommonBundle\Grid\GridBuilder;
use CommonBundle\Grid\Printer\PrintHandler\PrintPDFHandler;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class Printer
{
    use ContainerAwareTrait;

    const FORMAT_PDF = 0;
    const FORMAT_DOC = 1;

    protected $builder;

    public function __construct(GridBuilder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param $format
     * @return BinaryFileResponse
     * @throws PrinterException
     */
    public function write($format)
    {
        switch ($format) {

            case self::FORMAT_PDF:
                $pdfHandler = new PrintPDFHandler($this->getBuilder(), $this->container);
                $file = $pdfHandler->getFile();
                
                break;
            default:
                throw new PrinterException('Unexpected format ' . $format);
                break;
        }

        $response = new BinaryFileResponse($file);
        $response->setContentDisposition('attachment', $file->getFilename());

        return $response;
    }

    public function getBuilder()
    {
        return $this->builder;
    }

    public function setBuilder(GridBuilder $builder)
    {
        $this->builder = $builder;

        return $this;
    }
}