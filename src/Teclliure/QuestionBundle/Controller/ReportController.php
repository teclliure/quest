<?php

namespace Teclliure\QuestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Teclliure\QuestionBundle\Entity\Report;
use Teclliure\QuestionBundle\Form\ReportType;
use Ps\PdfBundle\Annotation\Pdf;

use Knp\Menu\FactoryInterface as MenuFactoryInterface;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Knp\Menu\MenuItem;

/**
 * Report controller.
 *
 */
class ReportController extends Controller
{
    /**
     * Create report
     */
    public function createEditReportAction($personId, $reportId)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        $patientRepository = $em->getRepository('TeclliurePatientBundle:Patient');
        $reportRepository = $em->getRepository('TeclliureQuestionBundle:Report');

        $entity = $patientRepository->find($personId);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Person entity.');
        }

        if ($reportId) {
            $report = $reportRepository->find($reportId);

            if (!$report) {
                throw $this->createNotFoundException('Unable to find Report entity.');
            }
        }
        else {
            $report = new Report();
            $report->setPatient($entity);
        }

        $reportForm = $this->createForm(new ReportType(), $report);

        if ($request->isMethod('POST')) {
            $reportForm->bind($request);
            if ($reportForm->isValid()) {
                $em->persist($report);
                $em->flush();

                $this->get('session')->setFlash('info',
                   'Report saved correctly'
                );
            }
            else {
                $this->get('session')->setFlash('error',
                    'Error saving Report'
                );
            }
        }

        return $this->render('TeclliureQuestionBundle:Report:createEditReport.html.twig', array(
            'patient'                   => $entity,
            'report'                    => $report,
            'reportForm'                => $reportForm->createView()
        ));
    }

    /**
     * Saves a validation
     *
     */
    public function saveValidationAction($id, $validationId)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        $questionary = $em->getRepository('TeclliureQuestionBundle:Questionary');

        $entity = $questionary->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Questionary entity.');
        }

        if ($request->isXmlHttpRequest()) {
            if ($validationId) {
                $validation = $em->getRepository('TeclliureQuestionBundle:Validation')->find($validationId);
            }
            else {
                $validation = new Validation();
            }

            $validation->setQuestionary($entity);
            $validationForm = $this->createForm(new ValidationType(), $validation);
            $validationForm->bind($request);

            if ($validationForm->isValid()) {
                $em->persist($validation);
                $em->flush();

                $validationForm = $this->createForm(new ValidationType(), new Validation());


                $validationFormError = false;
            }
            else {
                $this->get('session')->setFlash('error',
                    'Error on validation save'
                );
                $validationFormError = true;
            }


            $validations = $questionary->findValidations($entity);

            return $this->render(':ajax:base_ajax.html.twig', array(
                'template'             => 'TeclliureQuestionBundle:Validation:validation.html.twig',
                'entity'               => $entity,
                'validations'          => $validations,
                'validationForm'       => $validationForm->createView(),
                'validationFormError'  => $validationFormError
            ));
        }
        else {
            return $this->render(':msg:error.html.twig', array(
                'msg' => 'Error: Not ajax call'
            ));
        }
    }

    /**
     * Deletes a validation
     *
     */
    public function deleteReportAction($id)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        $reportRepository = $em->getRepository('TeclliureQuestionBundle:Report');

        $entity = $reportRepository->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Report entity.');
        }
        $patient = $entity->getPatient();

        $em->remove($entity);

        try {
            $em->flush();

            $this->get('session')->setFlash('info',
                'Report deleted correctly'
            );
        }
        catch (\Exception $e) {
            $this->get('session')->setFlash('error',
                'Report could not be deleted.'
            );
        }

        return $this->redirect($this->generateUrl('patient_show', array('id' => $patient->getId())));
    }

    /*
     * @Pdf()
     */
    public function printReportAction($id) {
        $em = $this->getDoctrine()->getManager();

        $reportRepository = $em->getRepository('TeclliureQuestionBundle:Report');

        $entity = $reportRepository->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Report entity.');
        }

        $html = $this->renderView('TeclliureQuestionBundle:Report:report.html.twig', array(
            'report' => $entity
        ));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="report'.$entity->getId().'.pdf"'
            )
        );
    }
}
