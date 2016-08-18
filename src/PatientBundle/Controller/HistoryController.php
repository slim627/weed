<?php

namespace PatientBundle\Controller;

use CommonBundle\Annotation\AjaxOnlyRequest;
use CommonBundle\Grid\Printer\Printer;
use CommonBundle\Utils\ResponseTrait;
use StaffBundle\Annotation\KXSecureAction;
use StaffBundle\Annotation\KXSecureClass;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class HistoryController
 * @KXSecureClass("Patient history")
 * @package PatientBundle\Controller
 */
class HistoryController extends Controller
{
    use ResponseTrait;

    /**
     * @AjaxOnlyRequest()
     * @KXSecureAction(role="ROLE_PATIENT_HISTORY_LIST", title="Listing")
     * @Route("/show/{id}/list-histories", name="patient_histories_list")
     */
    public function listHistoriesAction()
    {
        return $this->render('PatientBundle:History:list.html.twig');
    }

    /**
     * @KXSecureAction(role="ROLE_PATIENT_HISTORY_LIST")
     * @Route("/list-histories-data", name="patient_histories_list_data")
     */
    public function listHistoriesDataAction(Request $request)
    {
        $builder = $this->createGridBuilder($request);
        return $this->response($builder);
    }

    /**
     * @KXSecureAction(role="ROLE_PATIENT_HISTORY_PRINT", title="Print")
     * @Route("/list-histories-print", name="patient_histories_print_data")
     */
    public function listHistoriesPrintAction(Request $request)
    {
        $builder = $this->createGridBuilder($request);

        $printer = $this->container->get('kx.grid_printer');
        $printer->setBuilder($builder);

        return $printer->write(Printer::FORMAT_PDF);
    }

    private function createGridBuilder($request)
    {
        $builder = $this->container->get('kx.grid_builder');
        $patientId = $this->get('session')->get('current_patient_id');

        $queryBuilder = $this->getDoctrine()
            ->getRepository('PatientBundle:History')
            ->createQueryBuilder('o');

        parse_str($request->get('filterString'), $filterParams);

        foreach ($filterParams as $name => $value) {

            if ($name == 'searchString' && !empty($value)) {

                $queryBuilder
                    ->orWhere('o.title LIKE :search')
                    ->setParameter('search', '%' .$value . '%' )
                ;
            }
        }

        $queryBuilder
            ->join('o.patient', 'patient')
            ->andWhere('patient.id = :id')
            ->orderBy('o.id', 'DESC')
            ->setParameter('id', intval($patientId));

        $builder
            ->add('id', 'ID')
            ->add('title', 'Activity')
            ->add('patientName', 'User')
            ->add('createdAt', 'Creation Date', 'datetime')
            ->setLimit($request->get('limit', 10))
            ->setOffset($request->get('offset', 0))
            ->setEntityClass('PatientBundle:History')
            ->setQueryBuilder($queryBuilder)
            ->build();

        return $builder;
    }
}
