<?php

namespace StaffBundle\Controller;

use CommonBundle\Annotation\AjaxOnlyRequest;
use CommonBundle\Mapper\FormErrorMapper;
use CommonBundle\Utils\ResponseTrait;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Collections\Criteria;
use StaffBundle\Annotation\KXSecureAction;
use StaffBundle\Annotation\KXSecureClass;
use StaffBundle\Entity\AccessLevel;
use StaffBundle\Entity\Employee;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class DefaultController
 * @KXSecureClass("Staff")
 * @package StaffBundle\Controller
 */
class DefaultController extends Controller
{
    use ResponseTrait;

    /**
     * @AjaxOnlyRequest()
     * @KXSecureAction(role="ROLE_STAFF_LIST", title="Listing")
     * @Route("/list", name="staff.list")
     */
    public function indexAction()
    {
        $accessLevels = $this->getDoctrine()
            ->getRepository('StaffBundle:AccessLevel')
            ->findBy(['parent' => null]);

        return $this->render('StaffBundle:Default:index.html.twig', ['accessLevels' => $accessLevels]);
    }

    /**
     * @KXSecureAction(role="ROLE_STAFF_LIST")
     * @Route("/list-data", name="staff.list_data")
     */
    public function indexDataAction(Request $request)
    {
        $builder = $this->container->get('kx.grid_builder');

        parse_str($request->get('filterString'), $params);

        $queryBuilder = $this->getDoctrine()
            ->getRepository('StaffBundle:Employee')
            ->createQueryBuilder('o');

        // Apply filter by name
        if (!empty($params['searchString'])) {

            $queryBuilder
                ->where('o.firstName LIKE :search OR o.lastName LIKE :search')
                ->setParameter('search', '%' . $params['searchString'] . '%');
        }

        // Apply filter by access level
        if(!empty($params['access_level'])){

            $criteria = new Criteria();
            $expr = Criteria::expr();

            $accessLevelIds = [];
            foreach($params['access_level'] as $accessLevelId => $val){
                if(intval($val)){
                    $accessLevelIds[] = intval($accessLevelId);
                }
            }

            if(!empty($accessLevelIds)){
                $accessLevels = $this->getDoctrine()
                    ->getRepository('StaffBundle:AccessLevel')
                    ->findBy(['id' => $accessLevelIds]);

                $criteria->where($expr->in('o.accessLevel', $accessLevels));

                $queryBuilder->addCriteria($criteria);
            }
        }

        $builder->setQueryBuilder($queryBuilder);

        $builder
            ->add('id', 'ID', 'hidden')
            ->add('name')
            ->add('supervisor.name', 'Supervisor')
            ->add('accessLevel.name', 'Access Level')
            ->add('isSuperAdmin', 'Super Admin', 'bool')
            ->setLimit($request->get('limit', 300))
            ->setOffset($request->get('offset', 0))
            ->setEntityClass('StaffBundle:Employee')
            ->build();

        return $this->response($builder);
    }

    /**
     * @KXSecureAction(role="ROLE_STAFF_CREATE", title="Create")
     * @Route("/create", name="staff.create")
     */
    public function createAction()
    {
        $formType = $this->container->get('form.staff.employee');
        $form = $this->createForm($formType, new Employee());

        return $this->render('StaffBundle:Default:create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @AjaxOnlyRequest()
     * @KXSecureAction(role="ROLE_STAFF_EDIT", title="Edit")
     * @Route("/edit", name="staff.edit")
     */
    public function editAction(Request $request)
    {
        $employee = $this->getDoctrine()
            ->getManager()
            ->getRepository('StaffBundle:Employee')
            ->find($request->get('id'))
        ;

        if(!$employee){
            throw new NotFoundHttpException('Employee not found!');
        }

        $formType = $this->container->get('form.staff.employee');
        $form = $this->createForm($formType, $employee);

        return $this->render('StaffBundle:Default:create.html.twig', ['form' => $form->createView(), 'id' => $employee->getId()]);
    }

    /**
     * @KXSecureAction(role="ROLE_STAFF_CREATE")
     * @KXSecureAction(role="ROLE_STAFF_EDIT")
     * @Route("/submit", name="staff.submit")
     */
    public function submitAction(Request $request)
    {
        $formType = $this->container->get('form.staff.employee');

        $id = $request->get('id');

        $employee = $this->getDoctrine()->getRepository('StaffBundle:Employee')->find($id);

        if (is_null($employee)) {
            $employee = new Employee();
        }

        $form = $this->createForm($formType, $employee);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $employee = $form->getData();
            if(!$employee instanceof Employee){
                return $this->error('Form filling error');
            }

            $employee->setEnabled(true);

            $em = $this->getDoctrine()->getManager();
            $em->persist($employee);
            $em->flush();

            return $this->response(NULL);
        } else {
            $errors = new FormErrorMapper($form);

            return $this->error('Form filling error', null, $errors);
        }
    }

    /**
     * Build KXSecure access grid
     *
     * @KXSecureAction(role="ROLE_STAFF_EDIT_ACCESS_LEVEL", title="Manage access levels")
     * @Route("/edit-access-level", name="staff.edit_access_level")
     */
    public function editAccessLevelAction()
    {
        return $this->render('StaffBundle:Default:accessLevel.html.twig');
    }

    /**
     * Build KXSecure access grid
     *
     * @KXSecureAction(role="ROLE_STAFF_EDIT_ACCESS_LEVEL")
     * @Route("/access-level-data", name="staff.access_level_data")
     */
    public function accessLevelDataAction(Request $request)
    {
        $accessLevels = [];
        $temp = $this->getDoctrine()
            ->getManager()
            ->getRepository('StaffBundle:AccessLevel')
            ->findAll()
        ;
        foreach($temp as $level){
            if($level->getParent()){
                $accessLevels[$level->getParent()->getRole()]['title'] = $level->getParent()->getName();
                $accessLevels[$level->getParent()->getRole()]['checked'][] = $level->getRole();
            }elseif (empty($accessLevels[$level->getRole()])){
                $accessLevels[$level->getRole()]['title'] = $level->getName();
                $accessLevels[$level->getRole()]['checked'] = [];
            }
        }

        return $this->response([
            'access_levels' => $accessLevels,
            'access_grid' => $this->getAccessGrid(),
        ]);
    }

    /**
     * @KXSecureAction(role="ROLE_STAFF_EDIT_ACCESS_LEVEL")
     * @Route("/submit-access-level-data", name="staff.submit_access_level_data")
     */
    public function submitAccessLevelAction(Request $request)
    {
        $accessGrid = json_decode($request->get('access_grid'), true);
        $em = $this
            ->getDoctrine()
            ->getManager();

        $repo = $em
            ->getRepository('StaffBundle:AccessLevel');

        $access_grid = $this->getAccessGrid(true);

        // Override access level grid
        foreach($accessGrid as $access_level => $roles){
            $access_level = $repo->findOneBy(['role' => $access_level]);
            if(!$access_level){
                return $this->error('Unexpected access level');
            }
            $children = $repo->findBy(['parent' => $access_level]);

            // Truncate roles
            foreach($children as $child){
                $em->remove($child);
            }

            foreach($roles as $role){
                // Search role in access grid and persist
                foreach($access_grid as $row){
                    foreach($row as $item){
                        if($item->getRole() == $role){

                            $temp = new AccessLevel();
                            $temp->setParent($access_level);
                            $temp->setRole($item->getRole());
                            $temp->setName($item->getTitle());
                            $em->persist($temp);
                            continue(3);
                        }
                    }
                }

                return $this->error('Unexpected role ' . $role);
            }

            $em->flush();
        }

        return $this->response([]);
    }

    private function getAccessGrid()
    {
        // Read all controller actions that annotated like KXSecureAction
        $annotationReader = new AnnotationReader();

        // Load all registered bundles
        $bundles = $this->container->getParameter('kernel.bundles');

        foreach ($bundles as $name => $class) {

            $namespaceParts = explode('\\', $class);
            // Remove class name
            array_pop($namespaceParts);
            $bundleNamespace = implode('\\', $namespaceParts);
            $rootPath = $this->container->get('kernel')->getRootDir().'/../src/';
            $controllerDir = $rootPath.$bundleNamespace.'/Controller';

            // Skip vendor bundles and bundles without controllers
            if(!file_exists($controllerDir)){
                continue;
            }

            $files = scandir($controllerDir);
            foreach ($files as $file) {
                list($filename, $ext) = explode('.', $file);
                if ($ext != 'php') continue;

                $class = $bundleNamespace.'\\Controller\\'.$filename;
                $reflectedClass = new \ReflectionClass($class);

                // Read class annotation
                $classAnnotation = $annotationReader->getClassAnnotation($reflectedClass, 'StaffBundle\Annotation\KXSecureClass');
                if($classAnnotation instanceof KXSecureClass){
                    $class = $classAnnotation->getTitle();
                }

                foreach ($reflectedClass->getMethods() as $reflectedMethod) {

                    // The annotations
                    $annotations = $annotationReader->getMethodAnnotations($reflectedMethod);
                    foreach($annotations as $annotation){
                        if($annotation instanceof KXSecureAction){
                            $kxAccessGrid[$class] = empty($kxAccessGrid[$class]) ? [] : $kxAccessGrid[$class];
                            // Ignore annotation if it already included
                            foreach($kxAccessGrid[$class] as $item){
                                if($item->getRole() == $annotation->getRole()){
                                    continue(2);
                                }
                            }

                            $kxAccessGrid[$class][] = $annotation;
                        }
                    }
                }
            }
        }

        return $kxAccessGrid;
    }
}
