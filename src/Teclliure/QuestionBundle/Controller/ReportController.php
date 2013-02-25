<?php

namespace Teclliure\QuestionBundle\Controller;

use Teclliure\DashboardBundle\Controller\Controller;
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
            $this->buildBreadcrumbs('edit_report', array(
                array('id'=>$entity->getId()),
                array('personId'=>$entity->getId())
            ));
        }
        else {
            $report = new Report();
            $report->setPatient($entity);
            $this->buildBreadcrumbs('create_report', array(
                array('id'=>$entity->getId()),
                array('personId'=>$entity->getId())
            ));
        }


        $this->checkPerms($entity);

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
     * Deletes a report
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
        $this->checkPerms($patient);

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

    public function printReportAction($id) {
        $em = $this->getDoctrine()->getManager();

        $reportRepository = $em->getRepository('TeclliureQuestionBundle:Report');

        $entity = $reportRepository->findWithResults($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Report entity.');
        }

        $this->checkPerms($entity->getPatient());

        return $this->render('TeclliureQuestionBundle:Report:report.html.twig', array(
            'report' => $entity,
            'hidePdf' => false
        ));
    }

    /*
     * @Pdf()
     */
    public function printReportPdfAction($id) {
        $em = $this->getDoctrine()->getManager();

        $reportRepository = $em->getRepository('TeclliureQuestionBundle:Report');

        $entity = $reportRepository->findWithResults($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Report entity.');
        }

        $html = $this->renderView('TeclliureQuestionBundle:Report:report.html.twig', array(
            'report' => $entity,
            'hidePdf' => true
        ));
        $this->checkPerms($entity->getPatient());

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="report'.$entity->getId().'.pdf"'
            )
        );
    }

    public function getBreadcrumbsRoutes() {
        return array(
            'create_report' => array(
                array('route'=>'patient_show', 'label'=>'Person show'),
                array('route'=>'create_report', 'label'=>'Create report')
            ),
            'edit_report' => array(
                array('route'=>'patient_show', 'label'=>'Person show'),
                array('route'=>'create_report', 'label'=>'Edit report')
            )

        );
    }
}
