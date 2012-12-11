<?php

namespace Teclliure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as Sf2Controller;

use Knp\Menu\MenuFactory as MenuFactory;
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
     * The router instance
     *
     * @var RouteGeneratorInterface
     */
    protected $routeGenerator;

    /**
     * Generates the breadcrumbs array
     *
     * @param string                       $action
     * @param \Knp\Menu\ItemInterface|null $menu
     *
     * @return array
     */
    public function buildBreadcrumbs($action, MenuItemInterface $menu = null)
    {
        if (!$this->menuFactory) {
            $this->menuFactory = new MenuFactory();
        }

        if (!$menu) {
            $menu = $this->menuFactory->createItem('root');
        }

        $child = $menu->addChild(
            $this->trans('dashboard', array(), 'SonataAdminBundle'),
            array('uri' => $this->routeGenerator->generate('sonata_admin_dashboard'))
        );

        $child = $child->addChild(
            $this->trans('List'),
            array('uri' => $this->generateUrl('list'))
        );

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
        array_shift($breadcrumbs);

        return $this->breadcrumbs = breadcrumbs;
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
     * {@inheritdoc}
     */
    public function setRouteGenerator(RouteGeneratorInterface $routeGenerator)
    {
        $this->routeGenerator = $routeGenerator;
    }

    /**
     * @return \Sonata\AdminBundle\Route\RouteGeneratorInterface
     */
    public function getRouteGenerator()
    {
        return $this->routeGenerator;
    }

}