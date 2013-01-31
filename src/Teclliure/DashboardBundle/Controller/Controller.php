<?php

namespace Teclliure\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as Sf2Controller;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Sonata\AdminBundle\Route\RouteGeneratorInterface;
use Sonata\AdminBundle\Route\DefaultRouteGenerator;

use Knp\Menu\MenuFactory;
use Knp\Menu\FactoryInterface as MenuFactoryInterface;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Knp\Menu\MenuItem;

/**
 * Base controller.
 *
 */
class Controller extends Sf2Controller
{
    protected $menu;

    /**
     * @var \Knp\Menu\FactoryInterface
     */
    protected $menuFactory;

    /**
     * The generated breadcrumbs
     *
     * @var array
     */
    protected $breadcrumbs = array();

    /**
     * The translation domain to be used to translate messages
     *
     * @var string
     */
    protected $translationDomain = 'messages';

    /**
     * The translator component
     *
     * @var \Symfony\Component\Translation\TranslatorInterface
     */
    protected $translator;

    /**
     * Generates the breadcrumbs array
     *
     * @param string                       $action
     * @param \Knp\Menu\ItemInterface|null $menu
     *
     * @return array
     */
    public function buildBreadcrumbs($action, $params = array(), MenuItemInterface $menu = null)
    {
        $breadcrumbsRoutes = $this->getBreadcrumbsRoutes();
        if (!$this->menuFactory) {
            $this->menuFactory = new MenuFactory();
        }

        if (!$menu) {
            $menu = $this->menuFactory->createItem('root');
        }

        $child = $menu->addChild(
            $this->trans('Dashboard'),
            array('uri' => $this->generateUrl('sonata_admin_dashboard'))
        );

        if ($action == 'list' || $action == 'edit' || $action == 'show' || $action == 'new') {
            $child = $child->addChild(
                $this->trans($breadcrumbsRoutes['list']['label']),
                array('uri' => $this->generateUrl($breadcrumbsRoutes['list']['route'], $params))
            );

            $url = $this->generateUrl($breadcrumbsRoutes[$action]['route'], $params);
            $child = $child->addChild(
                $this->trans($breadcrumbsRoutes[$action]['label']), array('uri' => $url)
            );
        }
        else {
            if (is_array($breadcrumbsRoutes[$action]) && isset($breadcrumbsRoutes[$action][0]) && is_array($breadcrumbsRoutes[$action][0])) {
                foreach ($breadcrumbsRoutes[$action] as $key=>$route) {
                    if (isset($params[$key])) {
                        $paramsTmp = $params[$key];
                    }
                    else {
                        $paramsTmp = null;
                    }
                    $url = $this->generateUrl($route['route'], $paramsTmp);
                    $child = $child->addChild(
                        $this->trans($route['label']), array('uri' => $url)
                    );
                }
            }
            else {
                $url = $this->generateUrl($breadcrumbsRoutes[$action]['route'], $params);
                $child = $child->addChild(
                    $this->trans($breadcrumbsRoutes[$action]['label']), array('uri' => $url)
                );
            }
        }




        /*$childAdmin = $this->getCurrentChildAdmin();

        if ($childAdmin) {
            $id = $this->request->get($this->getIdParameter());

            $child = $child->addChild(
                (string) $this->getSubject(),
                array('uri' => $this->generateUrl('edit', array('id' => $id)))
            );

            return $childAdmin->buildBreadcrumbs($action, $child);

        } elseif ($this->isChild()) {
            if ($action != 'list') {
                $menu = $menu->addChild(
                    $this->trans($this->getLabelTranslatorStrategy()->getLabel(sprintf('%s_list', $this->getClassnameLabel()), 'breadcrumb', 'link')),
                    array('uri' => $this->generateUrl('list'))
                );
            }

            if ($action != 'create' && $this->hasSubject()) {
                $breadcrumbs = $menu->getBreadcrumbsArray((string) $this->getSubject());
            } else {
                $breadcrumbs = $menu->getBreadcrumbsArray(
                    $this->trans($this->getLabelTranslatorStrategy()->getLabel(sprintf('%s_%s', $this->getClassnameLabel(), $action), 'breadcrumb', 'link'))
                );
            }

        } else if ($action != 'list') {
            $breadcrumbs = $child->getBreadcrumbsArray(
                $this->trans($this->getLabelTranslatorStrategy()->getLabel(sprintf('%s_%s', $this->getClassnameLabel(), $action), 'breadcrumb', 'link'))
            );
        } else {
            $breadcrumbs = $child->getBreadcrumbsArray();
        }
        */

        // the generated $breadcrumbs contains an empty element
        $breadcrumbs = $child->getBreadcrumbsArray();
        array_shift($breadcrumbs);

        return $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * {@inheritdoc}
     */
    public function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        $domain = $domain ?: $this->translationDomain;

        if (!$this->translator) {
            return $id;
        }

        return $this->translator->trans($id, $parameters, $domain, $locale);
    }

    /**
     * translate a message id
     *
     * @param string  $id
     * @param integer $count
     * @param array   $parameters
     * @param null    $domain
     * @param null    $locale
     *
     * @return string the translated string
     */
    public function transChoice($id, $count, array $parameters = array(), $domain = null, $locale = null)
    {
        $domain = $domain ?: $this->translationDomain;

        if (!$this->translator) {
            return $id;
        }

        return $this->translator->transChoice($id, $count, $parameters, $domain, $locale);
    }


    /**
     * set the translation domain
     *
     * @param string $translationDomain the translation domain
     *
     * @return void
     */
    public function setTranslationDomain($translationDomain)
    {
        $this->translationDomain = $translationDomain;
    }

    /**
     * Returns the translation domain
     *
     * @return string the translation domain
     */
    public function getTranslationDomain()
    {
        return $this->translationDomain;
    }

    /**
     * {@inheritdoc}
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return \Symfony\Component\Translation\TranslatorInterface
     */
    public function getTranslator()
    {
        return $this->translator;
    }


    /**
     * @param string   $view
     * @param array    $parameters
     * @param Response $response
     *
     * @return Response
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        if (isset($this->breadcrumbs) && !isset($parameters['breadcrumbs'])) {
            $parameters['breadcrumbs'] = $this->breadcrumbs;
        }

        return parent::render($view, $parameters, $response);
    }

    public function checkPerms ($entity) {
        if ($this->getUser()->getIsAdmin()) {
            return;
        }

        $userId = null;
        $className = get_class($entity);
        if ($className == 'Teclliure\PatientBundle\Entity\Patient') {
            $userId = $entity->getUser()->getId();
        }
        else if ($className == 'Teclliure\QuestionBundle\Entity\PatientQuestionary') {
            $userId = $entity->getPatient()->getUser()->getId();
        }

        if ($userId != $this->getUser()->getId()) {
            throw new AccessDeniedException();
        }
    }
}