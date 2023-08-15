<?php

namespace App\Twig\Runtime;

use App\ClassManager\NavbarItem;
use App\ClassManager\NavbarManager;
use Twig\Extension\RuntimeExtensionInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TwigExtensionRuntime implements RuntimeExtensionInterface
{
    private $router;
    private $requestStack;

    public function __construct(
        UrlGeneratorInterface $router, 
        RequestStack $requestStack
    ) {
        $this->router = $router;
        $this->requestStack = $requestStack;
    }

    public function navbarAdmin()
    {
        $currentRoute = $this->requestStack->getCurrentRequest()->attributes->get('_route');

        $navbar = new NavbarManager();
        $navbar->push(new NavbarItem('Storages', $this->router->generate('app_admin_storages'), $currentRoute === 'app_admin_storages'));
        $navbar->push(new NavbarItem('Equipments', $this->router->generate('app_admin_equipments'), $currentRoute === 'app_admin_equipments'));

        return $navbar->getItems();
    }
}
