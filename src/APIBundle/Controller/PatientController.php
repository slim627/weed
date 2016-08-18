<?php

namespace APIBundle\Controller;

use CommonBundle\Form\Type\ContactMethodType;
use CommonBundle\Utils\ContactMethod;
use CommonBundle\Utils\ResponseTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PatientController
 * @Route("/patient")
 * @package APIBundle\Controller
 */
class PatientController extends Controller
{
    use ResponseTrait;

    /**
     * Register new patient
     *
     * @Route("/register", name="api.patient.register_patient")
     * @param Request $request
     * @return JsonResponse
     */
    public function registerAction(Request $request)
    {
        if($request->getMethod() != 'POST'){
            return $this->error('Invalid request method. You MUST use POST method');
        }

        $em = $this->getDoctrine()->getManager();
        $con = $this->getDoctrine()->getConnection();

        $con->beginTransaction();

        // Retrieve doctor by license number or create new doctor
        $doctorData = $request->get('patient');
        if(!empty($doctorData['licenseNumber'])){
            $doctor = $em->getRepository('DoctorBundle:Doctor')
                ->findOneBy(['licenseNumber' => $doctorData['licenseNumber']]);

            $doctorId = $doctor->getId();
        }
        else{
            $doctor = $this->container->get('kx.doctor.controller')->submitAction($request);
            $doctor = json_decode($doctor->getContent());
            if($doctor->status == ResponseTrait::$STATUS_ERROR){

                $con->rollback();
                return $this->error('Doctor creation error', '', $doctor->response->data);
            }

            $doctorId = $doctor->response->id;
        }

        // Associate doctor and feature patient
        $patientData = $request->get('patient');
        $patientData['doctor'] = $doctorId;
        $patientData['onlineAccount'] = 0;
        $patientData['preferredContactMethod'] = ContactMethodType::PHONE;
        $request->request->set('patient', $patientData);

        // Create new patient
        $patient = $this->container->get('kx.patient.controller')->submitAction($request);
        $patient = json_decode($patient->getContent());
        if($patient->status == ResponseTrait::$STATUS_ERROR){

            $con->rollback();
            return $this->error('Patient creation error', '', $patient->response->data);
        }
        $patientId = $patient->response->id;

        // Associate patient and feature prescription
        $prescriptionData = $request->get('prescription');
        $prescriptionData['prescriptionStatus'] = 0;
        $request->request->set('prescription', $prescriptionData);

        // Create new prescription
        $prescription = $this->container->get('kx.prescription.controller')->submitAction($request, $patientId);
        $prescription = json_decode($prescription->getContent());
        if($prescription->status == ResponseTrait::$STATUS_ERROR){

            $con->rollback();
            return $this->error('Prescription creation error', '', $prescription->response->data);
        }

        $con->commit();

        return $this->response(null);
    }
}
