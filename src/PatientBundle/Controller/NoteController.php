<?php

namespace PatientBundle\Controller;

use CommonBundle\Annotation\AjaxOnlyRequest;
use CommonBundle\Grid\Printer\Printer;
use CommonBundle\Mapper\FormErrorMapper;
use CommonBundle\Utils\ResponseTrait;
use PatientBundle\Entity\NoteAndTask;
use PatientBundle\Event\PatientHistoryEvent;
use PatientBundle\Form\Type\NoteType;
use StaffBundle\Annotation\KXSecureAction;
use StaffBundle\Annotation\KXSecureClass;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class NoteController
 * @KXSecureClass("Patient note and task")
 * @package PatientBundle\Controller
 */
class NoteController extends Controller
{
    use ResponseTrait;

    /**
     * @AjaxOnlyRequest()
     * @KXSecureAction(role="ROLE_PATIENT_NOTE_AND_TASK_LIST", title="Listing")
     * @Route("/show/{id}/list-notes", name="patient_notes_list")
     *
     */
    public function listNotesAction()
    {
        return $this->render('PatientBundle:Note:list.html.twig');
    }

    /**
     * @KXSecureAction(role="ROLE_PATIENT_NOTE_AND_TASK_LIST")
     * @Route("/list-notes-data", name="patient_notes_list_data")
     */
    public function listNotesDataAction(Request $request)
    {
        $builder = $this->createGridBuilder($request);
        return $this->response($builder);
    }

    /**
     * @KXSecureAction(role="ROLE_PATIENT_NOTE_AND_TASK_PRINT", title="Print")
     * @Route("/list-notes-print", name="patient_notes_print_data")
     */
    public function listNotesPrintAction(Request $request)
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
            ->getRepository('PatientBundle:NoteAndTask')
            ->createQueryBuilder('o')
            ->orderBy('o.id', 'DESC');

        parse_str($request->get('filterString'), $filterParams);

        foreach ($filterParams as $name => $value) {

            if ($name == 'searchString' && !empty($value)) {
                $queryBuilder
                    ->orWhere('o.id LIKE :search')
                    ->orWhere('o.title LIKE :search')
                    ->setParameter('search','%'. $value .'%');
            }
        }

        $queryBuilder
            ->join('o.patient', 'patient')
            ->andWhere('patient.id = :id')
            ->setParameter('id', intval($patientId));

        $builder
            ->add('id', 'ID')
            ->add('code', 'Task Id')
            ->add('title', 'TITLE')
            ->add('startTime', 'Start Date', 'datetime')
            ->add('endTime', 'End Date', 'datetime')
            ->setLimit($request->get('limit', 10))
            ->setOffset($request->get('offset', 0))
            ->setEntityClass('PatientBundle:NotesAndTasks')
            ->setQueryBuilder($queryBuilder)
            ->build();

        return $builder;
    }

    /**
     * @AjaxOnlyRequest()
     * @KXSecureAction(role="ROLE_PATIENT_NOTE_AND_TASK_VIEW", title="View")
     * @Route("/show-note", name="patient_note_show")
     */
    public function showNoteAction(Request $request)
    {
        $note = $this->getDoctrine()->getRepository('PatientBundle:NoteAndTask')->find($request->get('id'));

        if (!$note) {
            throw new NotFoundHttpException('Note not found!');
        }

        return $this->render('PatientBundle:Note:show.html.twig', ['note' => $note]);
    }

    /**
     * @AjaxOnlyRequest()
     * @KXSecureAction(role="ROLE_PATIENT_NOTE_AND_TASK_CREATE", title="Create")
     * @Route("/note-create", name="note_create")
     */
    public function createAction(Request $request)
    {
        $complaint = null;
        if ($complaintId = $request->get('complaintId')) {
            $complaint = $this->getDoctrine()->getRepository('PatientBundle:Complaint')->find($complaintId);
        }

        $form = $this->createForm(NoteType::class, $complaint ? (new NoteAndTask())->setComplaint($complaint) : null);

        return $this->render('PatientBundle:Note:create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @AjaxOnlyRequest()
     * @KXSecureAction(role="ROLE_PATIENT_NOTE_AND_TASK_EDIT", title="Edit")
     * @Route("/note-edit", name="note_edit")
     */
    public function editAction(Request $request)
    {
        $note = $this->getDoctrine()
            ->getRepository('PatientBundle:NoteAndTask')
            ->find($request->get('id'));

        if (!$note) {
            throw new NotFoundHttpException('Note not found!');
        }

        $form = $this->createForm(NoteType::class, $note);

        return $this->render('PatientBundle:Note:create.html.twig', ['form' => $form->createView(), 'id' => $note->getId()]);
    }

    /**
     * @KXSecureAction(role="ROLE_PATIENT_NOTE_AND_TASK_CREATE")
     * @KXSecureAction(role="ROLE_PATIENT_NOTE_AND_TASK_EDIT")
     * @Route("/note-submit", name="note_submit")
     */
    public function submitAction(Request $request)
    {
        $patientId = $this->get('session')->get('current_patient_id');
        $id = $request->get('id');

        $note = $this->getDoctrine()->getRepository('PatientBundle:NoteAndTask')->find($id);

        if (is_null($note)) {
            $note = new NoteAndTask();
        }

        $form = $this->createForm(NoteType::class, $note);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $patient = $this->getDoctrine()
                ->getRepository('PatientBundle:Patient')
                ->find($patientId);

            if (!$patient) {
                throw new NotFoundHttpException('Patient not found!');
            }

            $note = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $note->setPatient($patient);

            $manager->persist($note);
            $manager->flush();

            $event = new PatientHistoryEvent($patient, $note->getNameForHistory(). ' is created or updated');
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch('patient.history.event', $event);

            return $this->response(null);
        } else {
            $errors = new FormErrorMapper($form);

            return $this->error('Form filling error', null, $errors);
        }
    }
}
