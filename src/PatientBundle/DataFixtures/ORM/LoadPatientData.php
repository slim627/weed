<?php

namespace PatientBundle\DataFixtures\ORM;

use CommonBundle\Form\Type\ContactMethodType;
use CommonBundle\Utils\ContactMethod;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PatientBundle\Entity\Complaint;
use DoctorBundle\Entity\Doctor;
use PatientBundle\Entity\File;
use PatientBundle\Entity\NoteAndTask;
use PatientBundle\Entity\Patient;
use PatientBundle\Entity\Prescription;
use PatientBundle\Form\Type\ComplaintPriorityType;
use StaffBundle\Entity\Employee;

class LoadPatientData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $doctor = new Doctor();
        $doctor->setFirstName('Mike');
        $doctor->setLastName('Smith');
        $doctor->setLicenseNumber('32554567392937');
        $doctor->setLicenseNumberVerified(true);
        $doctor->setDesignationDate(new \DateTime());
        $doctor->setOfficeAddress('431 Doctors Lane apt. 24, Toronto, ON, M5F 1F1');
        $doctor->setPhone('(416) 562-1234');
        $doctor->setRecommendationsNotes('Paperwork received by mail');
        $doctor->setPrimaryCenter('');

        $manager->persist($doctor);

        $user = new Employee();
        $user->setUsername('admin');
        $user->setEmail('admin@admin.com');
        $user->setPlainPassword("admin");
        $user->setSuperAdmin(true);
        $user->setEnabled(true);
        $user->setFirstName('Vasia');
        $user->setLastName('Pupkin');
        $manager->persist($user);

        $counter = 0;
        while($counter < 200) {
            $patient = new Patient();
            $patient->setName('Alex');
            $patient->setLastOrder(5);
            $patient->setPhone(3232435);
            $patient->setHealthNumber('24143243544512342');
            $patient->setVerified(1);
            $patient->setCreatedAt(new \DateTime());
            $patient->setDateOfBirth(new \DateTime());
            $patient->setOnlineAccount(1);
            $patient->setLastVisit(new \DateTime());
            $patient->setMailingAddress('Monkan st 22/32');
            $patient->setDeliveryAddress('Same as mailing address');
            $patient->setEmail('alex@gmail.com');
            $patient->setPreferredContactMethod(ContactMethodType::PHONE);
            $patient->setTaxExemption('Exempt from salex tax');
            $patient->setDiagnosis('Arthritis');
            $patient->setAccountManager($user);
            $patient->setMemberSince(new \DateTime());
            $patient->setMemberExpire(new \DateTime());
            $patient->setDoctor($doctor);

            $manager->persist($patient);

            $counter2 = 0;
            while ($counter2 < 3) {

                $complaint = new Complaint();
                $complaint->setProductName('N/A');
                $complaint->setSourceOfComplaint('Phone');
                $complaint->setReceivedBy('N/A');
                $complaint->setLotBatchNumber('N/A');
                $complaint->setDueDate(new \DateTime());
                $complaint->setPackageSize('N/A');
                $complaint->setTitle('Waiting Patient Reply');
                $complaint->setDateSampleReceived(new \DateTime());
                $complaint->setPriority(ComplaintPriorityType::NORMAL);
                $complaint->setSampleAvailable(0);
                $complaint->setQuantityReceived(12);
                $complaint->setDescription(
                    'Lorem Ipsum is simply dummy text of the printing and
                    typesetting industry. Lorem Ipsum has been the industry\'s standard dummy
                    text ever since the 1500s, when an unknown printer took a galley of type and
                    scrambled it to make a type specimen book. It has survived not only five centuries,
                    but also the leap into electronic typesetting, remaining essentially unchanged.
                    It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,
                    and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.'
                );
                $complaint->setPreferredContactMethod(ContactMethodType::PHONE);
                $complaint->setAssignedTo('Anthony Sparks');
                $complaint->setStatus('Waiting for patient reply');
                $complaint->setNote(
                    'Lorem Ipsum is simply dummy text of the printing and
                    typesetting industry. Lorem Ipsum has been the industry\'s standard dummy
                    text ever since the 1500s, when an unknown printer took a galley of type and
                    scrambled it to make a type specimen book. It has survived not only five centuries,
                    but also the leap into electronic typesetting, remaining essentially unchanged.
                    It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,
                    and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.'
                );
                $complaint->setPatient($patient);
                $manager->persist($complaint);


                $notesAndTasks = new NoteAndTask();
                $notesAndTasks->setTitle('Shipping address change');
                $notesAndTasks->setAccountPhone('12344321');
                $notesAndTasks->setAccountStatus('status1');
                $notesAndTasks->setActionType('Update');
                $notesAndTasks->setAssignedTo($user);
                $notesAndTasks->setCompleteDate(new \DateTime());
                $notesAndTasks->setContactPhone('09876678990');
                $notesAndTasks->setComplaint($complaint);
                $notesAndTasks->setDescription(
                    'Lorem Ipsum is simply dummy text of the printing and
                    typesetting industry. Lorem Ipsum has been the industry\'s standard dummy
                    text ever since the 1500s, when an unknown printer took a galley of type and
                    scrambled it to make a type specimen book. It has survived not only five centuries,
                    but also the leap into electronic typesetting, remaining essentially unchanged.
                    It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,
                    and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.'
                );
                $notesAndTasks->setStartTime(new \DateTime());
                $notesAndTasks->setEndTime(new \DateTime());
                $notesAndTasks->setIsComplete(1);
                $notesAndTasks->setNote(
                    'Lorem Ipsum is simply dummy text of the printing and
                    typesetting industry. Lorem Ipsum has been the industry\'s standard dummy
                    text ever since the 1500s, when an unknown printer took a galley of type and
                    scrambled it to make a type specimen book. It has survived not only five centuries,
                    but also the leap into electronic typesetting, remaining essentially unchanged.
                    It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,
                    and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.'
                );
                $notesAndTasks->setPatient($patient);
                $manager->persist($notesAndTasks);


                $prescription = new Prescription();
                $prescription->setPatient($patient);
                $prescription->setCycleStartDate(new \DateTime());
                $prescription->setCycleEndDate(new \DateTime());
                $prescription->setPrescriptionStart(new \DateTime());
                $prescription->setPrescriptionEnd(new \DateTime);
                $prescription->setDailyLimit(10);
                $prescription->setMonthlyLimit(600);
                $prescription->setDoctor($doctor);
                $prescription->setPrescriptionStatus('NOT SENT');
                $prescription->setGramsLeft(150);
                $prescription->setNote(
                    'Lorem Ipsum is simply dummy text of the printing and
                    typesetting industry. Lorem Ipsum has been the industry\'s standard dummy
                    text ever since the 1500s, when an unknown printer took a galley of type and
                    scrambled it to make a type specimen book. It has survived not only five centuries,
                    but also the leap into electronic typesetting, remaining essentially unchanged.
                    It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,
                    and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.'
                );
                $manager->persist($prescription);

                $counter2++;
            }

            $counter++;

            $file = new File();
            $file->setFile('application.pdf');
            $file->setSize(1000000);
            $file->setTitle('File title');
            $file->setDescription('Mailed from patient');
            $file->setPatient($patient);
            $manager->persist($file);
        }

        $manager->flush();
    }
}