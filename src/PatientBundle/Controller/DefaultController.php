<?php

namespace PatientBundle\Controller;

use CommonBundle\Annotation\AjaxOnlyRequest;
use CommonBundle\Mapper\FormErrorMapper;
use CommonBundle\Utils\ResponseTrait;
use Doctrine\ORM\Query;
use PatientBundle\Entity\Patient;
use PatientBundle\Event\PatientHistoryEvent;
use PatientBundle\Form\Type\PatientType;
use StaffBundle\Annotation\KXSecureAction;
use StaffBundle\Annotation\KXSecureClass;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class DefaultController
 * @KXSecureClass("Patient")
 * @package PatientBundle\Controller
 */
class DefaultController extends Controller
{
    use ResponseTrait;

    /**
     * @AjaxOnlyRequest()
     * @KXSecureAction(role="ROLE_PATIENT_VIEW", title="View profile")
     * @Route("/show/{id}", name="patient.show")
     */
    public function showAction()
    {
        return $this->render('PatientBundle:Default:show.html.twig');
    }

    /**
     * @AjaxOnlyRequest()
     * @KXSecureAction(role="ROLE_PATIENT_VIEW")
     * @Route("/show/{id}/overview", name="patient.overview")
     */
    public function overviewAction()
    {
        return $this->render('PatientBundle:Default:overview.html.twig');
    }

    /**
     * @KXSecureAction(role="ROLE_PATIENT_VIEW")
     * @Route("/show-data", name="patient_show_data")
     */
    public function showDataAction(Request $request)
    {
        $patient = $this->getDoctrine()->getRepository('PatientBundle:Patient')->find($request->get('id'));

        $session = $this->get('session');
        $session->set('current_patient_id', $patient->getId());

        if (!$patient) {
            throw new NotFoundHttpException('Patient not found!');
        }

        return $this->response($patient);
    }

    /**
     * @AjaxOnlyRequest()
     * @KXSecureAction(role="ROLE_PATIENT_LIST", title="Listing")
     * @Route("/list", name="patient_list")
     */
    public function listAction()
    {
        return $this->render('PatientBundle:Default:list.html.twig');
    }

    /**
     * @KXSecureAction(role="ROLE_PATIENT_LIST")
     * @Route("/list-data", name="patient_list_data")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function listDataAction(Request $request)
    {
        $builder = $this->container->get('kx.grid_builder');

        $queryBuilder = $this->getDoctrine()
            ->getRepository('PatientBundle:Patient')
            ->createQueryBuilder('o')
            ->select('o', 'doctor')
            ->leftJoin('o.doctor', 'doctor')
            ->orderBy('o.id', 'DESC');

        parse_str($request->get('filterString'), $filterParams);

        foreach ($filterParams as $name => $value) {

            if ($name == 'searchString' && !empty($value)) {

                $queryBuilder
                    ->orWhere('o.id LIKE :search')
                    ->orWhere('o.idNumber LIKE :search')
                    ->orWhere('o.name LIKE :search')
                    ->orWhere('o.phone LIKE :search')
                    ->orWhere('o.healthNumber LIKE :search')
                    ->setParameter('search', '%' . $value . '%');
            }
        }

        $builder
            ->add('id', 'ID')
            ->add('idNumber', 'ID NUMBER')
            ->add('name', 'Name')
            ->add('prescExpiry', 'presc Expiry', 'date')
            ->add('monthlyLimit', 'Monthly Limit')
            ->add('dailyLimit', 'Daily Limit')
            ->add('cycleStart', 'cycle Start', 'datetime')
            ->add('cycleEnd', 'cycle End', 'datetime')
            ->add('gramsLeft', 'grams Left')
            ->add('lastOrder', 'last Order')
            ->add('phone', 'Phone')
            ->add('doctorName', 'Doctor')
            ->add('healthNumber', 'Health Number')
            ->add('verified', 'Verified', 'bool')
            ->add('createdAt', 'Created At', 'datetime')
            ->setLimit($request->get('limit', 10))
            ->setOffset($request->get('offset', 0))
            ->setEntityClass('PatientBundle:Patient')
            ->setQueryBuilder($queryBuilder)
            ->build();

        return $this->response($builder);
    }

    /**
     * @KXSecureAction(role="ROLE_PATIENT_VERIFY", title="Verifying")
     * @Route("/verify", name="patient.verify")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function verifyPatientAction(Request $request)
    {
        $patient = $this->getDoctrine()
            ->getRepository('PatientBundle:Patient')
            ->find($request->get('id'));

        if (!$patient) {
            throw new NotFoundHttpException('Patient not found!');
        }

        $is_verified = intval($request->get('is_verified', 1));

        $patient->setVerified($is_verified);

        $em = $this->getDoctrine()->getManager();
        $em->persist($patient);
        $em->flush();

        $eventMessage = ($is_verified) ?
            'Patient status changed to verified' :
            'Patient status changed to not verified';
        $event = new PatientHistoryEvent($patient, $eventMessage);
        $this->get('event_dispatcher')->dispatch('patient.history.event', $event);

        return $this->response($patient);
    }

    /**
     * @AjaxOnlyRequest()
     * @KXSecureAction(role="ROLE_PATIENT_CREATE", title="Create")
     * @Route("/create", name="patient_create")
     */
    public function createAction()
    {
        $form = $this->createForm(PatientType::class);

        return $this->render('PatientBundle:Default:create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @AjaxOnlyRequest()
     * @KXSecureAction(role="ROLE_PATIENT_EDIT", title="Edit")
     * @Route("/edit", name="patient_edit")
     */
    public function editAction(Request $request)
    {
        $patient = $this->getDoctrine()
            ->getRepository('PatientBundle:Patient')
            ->find($request->get('patientId'));

        if (!$patient) {
            throw new NotFoundHttpException('Patient not found!');
        }

        $form = $this->createForm(PatientType::class, $patient);

        return $this->render('PatientBundle:Default:create.html.twig', [
            'form' => $form->createView(),
            'patientId' => $patient->getId(),
            'files' => $patient->getFiles()->toArray()
        ]);
    }

    /**
     * @KXSecureAction(role="ROLE_PATIENT_CREATE")
     * @KXSecureAction(role="ROLE_PATIENT_EDIT")
     * @Route("/submit", name="patient_submit")
     */
    public function submitAction(Request $request)
    {
        $id = intval($request->get('id'));

        $patient = null;
        if($id){
            $patient = $this->getDoctrine()
                ->getRepository('PatientBundle:Patient')
                ->find($id);
        }

        if (is_null($patient)) {
            $patient = new Patient();
        }

        $form = $this->createForm(PatientType::class, $patient);
        //@todo fix it
        if(!$request->files->count()){
            $form->remove('files');
        }

        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $data = $form->getData();

            $manager->persist($data);
            $manager->flush();

            $event = new PatientHistoryEvent($data, 'Patient was created or updated!');
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch('patient.history.event', $event);

            // Send notification email to staff
            if(!$id){
                $staffEmails = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('StaffBundle:Employee')
                    ->getEmailsForNotification()
                ;
                $letterBody = $this->render('@Patient/Default/letter.registration.html.twig', ['patient' => $data]);
                $message = \Swift_Message::newInstance()
                    ->setSubject('New patient registered')
                    ->setFrom($this->container->getParameter('mailer_header_from'))
                    ->setTo($staffEmails)
                    ->setBody($letterBody);

                $this->get('mailer')->send($message);
            }

            return $this->response($data);
        } else {
            $errors = new FormErrorMapper($form);

            return $this->error('Form filling error', null, $errors);
        }
    }

    /**
     * Remove patient file
     * @KXSecureAction(role="ROLE_PATIENT_EDIT")
     * @Route("/remove-file", name="patient.remove_file")
     */
    public function removeFileAction(Request $request)
    {
        $id = $request->get('id');

        $file = $this->getDoctrine()
            ->getRepository('PatientBundle:File')
            ->findOneBy([
                'id' => $id
            ]);

        if(!$file){
            throw new NotFoundHttpException('File with id ' . $id . ' not found for current patient');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($file);
        $em->flush();

        return $this->response(null);
    }
}
