<?php

namespace APIBundle\Tests\Controller;

use CommonBundle\Utils\ResponseTrait;
use DoctorBundle\Form\Type\DoctorFormType;
use PatientBundle\Form\Type\PatientType;
use PatientBundle\Form\Type\PrescriptionType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PatientControllerTest extends WebTestCase
{
    public function testRegister()
    {
        $client = static::createClient();

        $doctorForm = new DoctorFormType();
        $patientForm = new PatientType();
        $prescriptionForm = new PrescriptionType();

        $params = [
            $doctorForm->getBlockPrefix() => [
                'firstName' => 'Test',
                'lastName' => 'Doctor',
                'phone' => '(416) 562-1234',
                'licenseNumber' => 'd3132312312',
                'officeAddress' => '431 Doctors Lane apt. 24, Toronto, ON, M5F 1F1',
                'designationDate' => '2016-02-12 13:34',
            ],
            $patientForm->getBlockPrefix() => [
                'name' => 'Test patient',
                'dateOfBirth' => '1985-02-12 00:00',
                'healthNumber' => '0987654321',
                'email' => rand(0, 1000).'@gmail.com',
                'phone' => '(416) 123-1234',
                'mailingAddress' => 'Toronto downtown',
                'deliveryAddress' => ':)',
                'diagnosis' => 'Sadness',
            ],
            $prescriptionForm->getBlockPrefix() => [
                'prescriptionStart' => '2016-02-12 00:00',
                'prescriptionEnd' => '2016-04-12 00:00',
                'cycleStartDate' => '2016-02-15 00:00',
                'cycleEndDate' => '2016-02-15 00:00',
                'monthlyLimit' => 300,
                'dailyLimit' => 30,
            ],
        ];

        $route = static::$kernel->getContainer()->get('router')->generate('api.patient.register_patient');
        $crawler = $client->request('POST', $route, $params);
        $content = json_decode($client->getResponse()->getContent());

        $this->assertAttributeEquals(ResponseTrait::$STATUS_SUCCESS, 'status', $content);
    }
}
