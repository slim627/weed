<?php

namespace DoctorBundle\Controller;

use CommonBundle\Annotation\AjaxOnlyRequest;
use CommonBundle\Mapper\FormErrorMapper;
use CommonBundle\Utils\ResponseTrait;
use DoctorBundle\Entity\Doctor;
use DoctorBundle\Form\Type\DoctorFormType;
use StaffBundle\Annotation\KXSecureAction;
use StaffBundle\Annotation\KXSecureClass;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class DefaultController
 * @KXSecureClass("Doctor")
 * @package DoctorBundle\Controller
 */
class DefaultController extends Controller
{
    use ResponseTrait;

    /**
     * @AjaxOnlyRequest()
     * @KXSecureAction(role="ROLE_DOCTOR_LIST", title="Listing")
     * @Route("/list", name="doctor.list")
     */
    public function indexAction()
    {
        return $this->render('DoctorBundle:Default:index.html.twig');
    }

    /**
     * @KXSecureAction(role="ROLE_DOCTOR_LIST")
     * @Route("/list-data", name="doctor.list_data")
     */
    public function indexDataAction(Request $request)
    {
        $builder = $this->container->get('kx.grid_builder');

        if ($request->get('filterString')) {

            parse_str($request->get('filterString'), $params);

            $queryBuilder = $this->getDoctrine()
                ->getRepository('DoctorBundle:Doctor')
                ->createQueryBuilder('o');

            $queryBuilder
                ->where('o.firstName LIKE :search')
                ->setParameter('search', '%' . $params['searchString'] . '%')
            ;

            $builder->setQueryBuilder($queryBuilder);
        }

        $builder
            ->add('id', 'ID', 'hidden')
            ->add('name')
            ->add('licenseNumber', 'License No.')
            ->add('licenseNumberVerified', 'License No. Verified', 'bool')
            ->setLimit($request->get('limit', 300))
            ->setOffset($request->get('offset', 0))
            ->setEntityClass('DoctorBundle:Doctor')
            ->build();

        return $this->response($builder);
    }

    /**
     * @KXSecureAction(role="ROLE_DOCTOR_CREATE", title="Create")
     * @Route("/create", name="doctor.create")
     */
    public function createAction()
    {
        $form = $this->createForm(DoctorFormType::class, new Doctor());

        return $this->render('DoctorBundle:Default:create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @AjaxOnlyRequest()
     * @KXSecureAction(role="ROLE_DOCTOR_EDIT", title="Edit")
     * @Route("/edit", name="doctor.edit")
     */
    public function editAction(Request $request)
    {
        $doctor = $this->getDoctrine()
            ->getManager()
            ->getRepository('DoctorBundle:Doctor')
            ->find($request->get('id'))
        ;

        if(!$doctor){
            throw new NotFoundHttpException('Employee not found!');
        }

        $form = $this->createForm(DoctorFormType::class, $doctor);

        return $this->render('DoctorBundle:Default:create.html.twig', ['form' => $form->createView(), 'id' => $doctor->getId()]);
    }

    /**
     * @KXSecureAction(role="ROLE_DOCTOR_CREATE")
     * @KXSecureAction(role="ROLE_DOCTOR_EDIT")
     * @Route("/submit", name="doctor.submit")
     */
    public function submitAction(Request $request)
    {
        $id = intval($request->get('id'));
        $doctor = null;

        if($id){
            $doctor = $this->getDoctrine()
                ->getManager()
                ->getRepository('DoctorBundle:Doctor')
                ->find($id)
            ;
        }

        if(!$doctor){
            $doctor = new Doctor();
        }

        $form = $this->createForm(DoctorFormType::class, $doctor);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $doctor = $form->getData();
            if(!$doctor instanceof Doctor){
                return $this->error('Form filling error');
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($doctor);
            $em->flush();

            return $this->response($doctor);
        } else {
            $errors = new FormErrorMapper($form);

            return $this->error('Form filling error', null, $errors);
        }
    }
}
