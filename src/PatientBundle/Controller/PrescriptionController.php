<?php

namespace PatientBundle\Controller;

use CommonBundle\Annotation\AjaxOnlyRequest;
use CommonBundle\Grid\Printer\Printer;
use CommonBundle\Mapper\FormErrorMapper;
use CommonBundle\Utils\ResponseTrait;
use PatientBundle\Event\PatientHistoryEvent;
use PatientBundle\Form\Type\PrescriptionType;
use StaffBundle\Annotation\KXSecureAction;
use StaffBundle\Annotation\KXSecureClass;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class PrescriptionController
 * @KXSecureClass("Patient prescriptions")
 * @package PatientBundle\Controller
 */
class PrescriptionController extends Controller
{
    use ResponseTrait;

    /**
     * @AjaxOnlyRequest()
     * @KXSecureAction(role="ROLE_PATIENT_PRESCRIPTION_LIST", title="Listing")
     * @Route("/show/{id}/list-prescriptions", name="patient_prescriptions_list")
     *
     */
    public function listPrescriptionsAction()
    {
        return $this->render('PatientBundle:Prescription:list.html.twig');
    }

    /**
     * @KXSecureAction(role="ROLE_PATIENT_PRESCRIPTION_LIST")
     * @Route("/list-prescriptions-data", name="patient_prescriptions_list_data")
     * @param Request $request
     * @return JsonResponse
     */
    public function listPrescriptionsDataAction(Request $request)
    {
        $patientId = $this->get('session')->get('current_patient_id');
        $builder = $this->createGridBuilder($request, $patientId);

        return $this->response($builder);
    }

    /**
     * Print listing
     *
     * @KXSecureAction(role="ROLE_PATIENT_PRESCRIPTION_PRINT", title="Print")
     * @Route("/list-prescription-print", name="patient.prescription_print_data")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \CommonBundle\Grid\Exception\PrinterException
     */
    public function listComplaintsPrintAction(Request $request)
    {
        $patientId = $this->get('session')->get('current_patient_id');
        $builder = $this->createGridBuilder($request, $patientId);

        $printer = $this->container->get('kx.grid_printer');
        $printer->setBuilder($builder);

        return $printer->write(Printer::FORMAT_PDF);
    }

    /**
     * @AjaxOnlyRequest()
     * @KXSecureAction(role="ROLE_PATIENT_PRESCRIPTION_VIEW", title="View")
     * @Route("/show/{id}/show-prescription", name="patient_prescription_show")
     */
    public function  showPrescriptionAction()
    {
        return $this->render('PatientBundle:Prescription:show.html.twig');
    }

    /**
     * @KXSecureAction(role="ROLE_PATIENT_PRESCRIPTION_VIEW")
     * @Route("/show-prescription-data", name="patient_prescription_show_data")
     * @param Request $request
     * @return JsonResponse
     */
    public function showPrescriptionDataAction(Request $request)
    {
        $prescription = $this->getDoctrine()->getRepository('PatientBundle:Prescription')->find($request->get('id'));

        if (!$prescription) {
            throw new NotFoundHttpException('Prescription not found!');
        }

        return $this->response($prescription);
    }

    /**
     * @param Request $request
     * @param $patientId
     * @return \CommonBundle\Grid\GridBuilder
     * @throws \Exception
     */
    private function createGridBuilder(Request $request, $patientId)
    {
        $builder = $this->container->get('kx.grid_builder');

        $queryBuilder = $this->getDoctrine()
            ->getRepository('PatientBundle:Prescription')
            ->createQueryBuilder('o')
            ->orderBy('o.id', 'DESC');

        parse_str($request->get('filterString'), $filterParams);

        foreach ($filterParams as $name => $value) {

            if ($name == 'searchString' && !empty($value)) {

                $queryBuilder
                    ->orWhere('o.prescriptionStart LIKE :search')
                    ->orWhere('o.prescriptionEnd LIKE :search')
                    ->orWhere('o.monthlyLimit LIKE :search')
                    ->orWhere('o.dailyLimit LIKE :search')
                    ->setParameter('search', '%'. $value . '%');

            }
        }

        $queryBuilder
            ->join('o.patient', 'patient')
            ->andWhere('patient.id = :id')
            ->setParameter('id', intval($patientId));

        $builder
            ->add('id', 'ID')
            ->add('prescriptionStart', 'Prescription Start')
            ->add('prescriptionEnd', 'Prescription End')
            ->add('monthlyLimit', 'thirty-day limit')
            ->add('dailyLimit', 'Daily Limit')
            ->add('doctor.name', 'Doctor')
            ->add('prescriptionStatus', 'Prescription Status')
            ->setLimit($request->get('limit', 10))
            ->setOffset($request->get('offset', 0))
            ->setEntityClass('PatientBundle:Prescription')
            ->setQueryBuilder($queryBuilder)
            ->build();

        return $builder;
    }

    /**
     * @AjaxOnlyRequest()
     * @KXSecureAction(role="ROLE_PATIENT_PRESCRIPTION_CREATE", title="Create")
     * @Route("/prescription-create", name="prescription_create")
     */
    public function createAction()
    {
        $form = $this->createForm(PrescriptionType::class);

        return $this->render('PatientBundle:Prescription:create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @AjaxOnlyRequest()
     * @KXSecureAction(role="ROLE_PATIENT_PRESCRIPTION_EDIT", title="Edit")
     * @Route("/prescription-edit", name="prescription_edit")
     * @param Request $request
     * @return JsonResponse
     */
    public function editAction(Request $request)
    {
        $prescription = $this->getDoctrine()
            ->getRepository('PatientBundle:Prescription')
            ->find($request->get('prescriptionId'));

        if (!$prescription) {
            throw new NotFoundHttpException('Prescription not found!');
        }

        $form = $this->createForm(PrescriptionType::class, $prescription);

        return $this->render('PatientBundle:Prescription:create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @KXSecureAction(role="ROLE_PATIENT_PRESCRIPTION_CREATE")
     * @KXSecureAction(role="ROLE_PATIENT_PRESCRIPTION_EDIT")
     * @Route("/prescription-submit", name="prescription_submit")
     * @param Request $request
     * @param int $patientId
     * @return JsonResponse
     */
    public function submitAction(Request $request, $patientId = null)
    {
        if($patientId === null){
            $patientId = $this->get('session')->get('current_patient_id');
        }

        $form = $this->createForm(PrescriptionType::class);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $patient = $this->getDoctrine()
                ->getRepository('PatientBundle:Patient')
                ->find($patientId);

            if (!$patient) {
                throw new NotFoundHttpException('Patient not found!');
            }

            $prescription = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $prescription->setPatient($patient);

            $manager->persist($prescription);
            $manager->flush();

            $event = new PatientHistoryEvent($patient, 'Patient prescription is created or updated');
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch('patient.history.event', $event);

            return $this->response(NULL);
        } else {
            $errors = new FormErrorMapper($form);

            return $this->error('Form filling error', null, $errors);
        }
    }
}
