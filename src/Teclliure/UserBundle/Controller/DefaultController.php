<?php

namespace Teclliure\UserBundle\Controller;

use Teclliure\UserBundle\Entity\User;
use Teclliure\UserBundle\Form\UserRegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Teclliure\UserBundle\Model\UserManager;


class DefaultController extends Controller
{
    public function loginAction()
    {
        if (false !== $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->get('session')->setFlash('error',
                'User already logged in. Logging out ...'
            );

            return $this->redirect($this->generateUrl('logout'));
        }
        $request = $this->getRequest();
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render('TeclliureUserBundle:Default:login.html.twig', array(
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error' => $error
        ));
    }

    /**
     * Register new user
     */
    public function registerAction()
    {
        $request = $this->getRequest();
        $entity = new User();
        $form   = $this->createForm (new UserRegisterType(), $entity);

        if (false !== $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->get('session')->setFlash('error',
                'User already logged in. Logging out ...'
            );

            return $this->redirect($this->generateUrl('logout'));
        }

        if ($request->isMethod('post')) {
            $em = $this->getDoctrine()->getManager();
            $form->bind($request);

            if ($form->isValid()) {
                $userManager = new UserManager($this->get('security.encoder_factory'));
                $userManager->updatePassword($entity);

                $em->persist($entity);
                $em->flush();

                $translator = $this->get('translator');

                $message = \Swift_Message::newInstance()
                    ->setSubject($translator->trans('New user registered').': '.$entity->getEmail())
                    ->setFrom($this->container->getParameter('teclliure_user.email.from'))
                    ->setTo($this->container->getParameter('teclliure_user.email.admin'))
                    ->setBody(
                        $this->renderView(
                            'TeclliureUserBundle:Default:registerNotice.txt.twig',
                            array('user' => $entity)
                        )
                    )
                ;
                $this->get('mailer')->send($message);

                $this->get('session')->setFlash('info',
                    'User registered correctly. You will receive a welcome email when activated.'
                );

                return $this->redirect($this->generateUrl('login'));
            }
        }

        return $this->render('TeclliureUserBundle:Default:register.html.twig', array(
            'form'   => $form->createView(),
        ));

    }
}
