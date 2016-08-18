<?php

namespace PatientBundle\Controller;

use CommonBundle\Annotation\AjaxOnlyRequest;
use CommonBundle\Grid\Printer\Printer;
use CommonBundle\Mapper\FormErrorMapper;
use CommonBundle\Utils\ResponseTrait;
use PatientBundle\Entity\Complaint;
use PatientBundle\Event\PatientHistoryEvent;
use PatientBundle\Form\Type\ComplaintType;
use StaffBundle\Annotation\KXSecureAction;
use StaffBundle\Annotation\KXSecureClass;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ComplaintController
 * @KXSecureClass("Patient complaint")
 * @package PatientBundle\Controller
 */
class ComplaintController extends Controller
{
    use ResponseTrait;

    /**
     * @AjaxOnlyRequest()
     * @KXSecureAction(role="ROLE_COMPLAINT_LIST", title="Listing")
     * @Route("/show/{id}/list-complaints", name="patient_complaints_list")
     */
    public function listComplaintsAction()
    {
        return $this->render('PatientBundle:Complaint:list.html.twig');
    }

    /**
     * @KXSecureAction(role="ROLE_COMPLAINT_LIST")
     * @Route("/list-complaints-data", name="patient_complaints_list_data")
     */
    public function listComplaintsDataAction(Request $request)
    {
        $builder = $this->createGridBuilder($request);

        return $this->response($builder);
    }

    /**
     * @KXSecureAction(role="ROLE_COMPLAINT_PRINT", title="Print")
     * @Route("/list-complaints-print", name="patient_complaints_print_data")
     */
    public function listComplaintsPrintAction(Request $request)
    {
        $builder = $this->createGridBuilder($request);

        $printer = $this->container->get('kx.grid_printer');
        $printer->setBuilder($builder);

        return $printer->write(Printer::FORMAT_PDF);
    }

    /**
     * @param $request
     * @return \CommonBundle\Grid\GridBuilder
     */
    private function createGridBuilder($request)
    {
        $builder = $this->container->get('kx.grid_builder');
        $patientId = $this->get('session')->get('current_patient_id');

        $queryBuilder = $this->getDoctrine()
            ->getRepository('PatientBundle:Complaint')
            ->createQueryBuilder('o')
            ->orderBy('o.id', 'DESC');

        parse_str($request->get('filterString'), $filterParams);

        foreach ($filterParams as $name => $value) {

            if ($name == 'searchString' && !empty($value)) {

                $queryBuilder
                    ->orWhere('o.id LIKE :search')
                    ->orWhere('o.dateSampleReceived = :search')
                    ->orWhere('o.description LIKE :search')
                    ->orWhere('o.dueDate LIKE :search')
                    ->orWhere('o.lotBatchNumber LIKE :search')
                    ->orWhere('o.note LIKE :search')
                    ->orWhere('o.preferredContactMethod LIKE :search')
                    ->orWhere('o.priority LIKE :search')
                    ->orWhere('o.productName LIKE :search')
                    ->orWhere('o.quantityReceived LIKE :search')
                    ->orWhere('o.receivedBy LIKE :search')
                    ->orWhere('o.sampleAvailable LIKE :search')
                    ->orWhere('o.status LIKE :search')
                    ->orWhere('o.title LIKE :search')
                    ->setParameter('search', '%' .$value . '%' )
                ;
            }
        }

        $queryBuilder
            ->join('o.patient', 'patient')
            ->andWhere('patient.id = :id')
            ->setParameter('id', intval($patientId));

        $builder
            ->add('id', 'ID')
            ->add('code', 'Complaint ID')
            ->add('status', 'STATUS')
            ->add('assignedTo', 'Assigned To')
            ->add('createdAt', 'Creation Date', 'datetime')
            ->add('dueDate', 'Due Date', 'datetime')
            ->setLimit($request->get('limit', 10))
            ->setOffset($request->get('offset', 0))
            ->setEntityClass('PatientBundle:Complaint')
            ->setQueryBuilder($queryBuilder)
            ->build();

        return $builder;
    }

    /**
     * @AjaxOnlyRequest()
     * @KXSecureAction(role="ROLE_COMPLAINT_VIEW", title="View")
     * @Route("/show-complaint", name="patient_complaint_show")
     */
    public function showComplaintAction(Request $request)
    {
        $complaint = $this->getDoctrine()->getRepository('PatientBundle:Complaint')->find($request->get('id'));

        return $this->render('PatientBundle:Complaint:show.html.twig', ['complaint' => $complaint]);
    }

    /**
     * @KXSecureAction(role="ROLE_COMPLAINT_VIEW")
     * @Route("/show-complaint-tasks-show-data", name="patient_complaint_tasks_show_data")
     */
    public function showComplaintDataAction(Request $request)
    {
        $complaint = $this->getDoctrine()->getRepository('PatientBundle:Complaint')->find($request->get('id'));
        $notes = $complaint->getNotesAndTasks()->toArray();

        if (!$complaint) {
            throw new NotFoundHttpException('Complaint not found!');
        }

        return $this->response($notes);
    }

    /**
     * @AjaxOnlyRequest()
     * @KXSecureAction(role="ROLE_COMPLAINT_CREATE", title="Create")
     * @Route("/complaint-create", name="complaint_create")
     */
    public function createAction()
    {
        $form = $this->createForm(ComplaintType::class);

        return $this->render('PatientBundle:Complaint:create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @AjaxOnlyRequest()
     * @KXSecureAction(role="ROLE_COMPLAINT_EDIT", title="Edit")
     * @Route("/complaint-edit", name="complaint_edit")
     */
    public function editAction(Request $request)
    {
        $complaint = $this->getDoctrine()
            ->getRepository('PatientBundle:Complaint')
            ->find($request->get('id'));

        if (!$complaint) {
            throw new NotFoundHttpException('Complaint not found!');
        }

        $form = $this->createForm(ComplaintType::class, $complaint);

        return $this->render('PatientBundle:Complaint:create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @KXSecureAction(role="ROLE_COMPLAINT_CREATE")
     * @KXSecureAction(role="ROLE_COMPLAINT_EDIT")
     * @Route("/complaint-submit", name="complaint_submit")
     */
    public function submitAction(Request $request)
    {
        $patientId = $this->get('session')->get('current_patient_id');

        $form = $this->createForm(ComplaintType::class, new Complaint());

        $form->handleRequest($request);

        if ($form->isValid()) {

            $patient = $this->getDoctrine()
                ->getRepository('PatientBundle:Patient')
                ->find($patientId);

            if (!$patient) {
                throw new NotFoundHttpException('Patient not found!');
            }

            $complaint = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $complaint->setPatient($patient);

            $manager->persist($complaint);
            $manager->flush();

            $event = new PatientHistoryEvent($patient, $complaint->getNameForHistory(). ' created/updated');
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch('patient.history.event', $event);

            return $this->response(NULL);
        } else {
            $errors = new FormErrorMapper($form);

            return $this->error('Form filling error', null, $errors);
        }
    }
}
