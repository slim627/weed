<?php

namespace StaffBundle\Controller;

use CommonBundle\Annotation\AjaxOnlyRequest;
use CommonBundle\Utils\ResponseTrait;
use StaffBundle\Annotation\KXSecureAction;
use StaffBundle\Annotation\KXSecureClass;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class NotificationController
 * @KXSecureClass("Notifications")
 * @package StaffBundle\Controller
 */
class NotificationController extends Controller
{
    use ResponseTrait;

    /**
     * @AjaxOnlyRequest()
     * @KXSecureAction(role="ROLE_NOTIFICATION_LISTING", title="Listing")
     * @Route("/list", name="notification.list")
     */
    public function indexAction()
    {
        return $this->render('StaffBundle:Notification:index.html.twig');
    }

    /**
     * @KXSecureAction(role="ROLE_NOTIFICATION_LISTING")
     * @Route("/list-data", name="notification.list_data")
     */
    public function indexDataAction(Request $request)
    {
        $builder = $this->container->get('kx.grid_builder');

        $queryBuilder = $this->getDoctrine()
            ->getRepository('StaffBundle:Notification')
            ->createQueryBuilder('o')
            ->where('o.employee = :employee')
            ->setParameter('employee', $this->getUser())
        ;

        if (!empty($request->get('filterString'))) {

            parse_str($request->get('filterString'), $params);

            $queryBuilder
                ->andwhere('o.text LIKE :search')
                ->setParameter('search', '%' . $params['searchString'] . '%')
            ;
        }

        $builder
            ->add('id', 'ID')
            ->add('text', 'Notification text')
            ->add('createdAt', 'Created At')
            ->add('isViewed', 'Is Viewed', 'bool')
            ->setLimit($request->get('limit', 300))
            ->setOffset($request->get('offset', 0))
            ->setEntityClass('StaffBundle:Notification')
            ->setQueryBuilder($queryBuilder)
            ->build();

        return $this->response($builder);
    }
}
