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

    // public function currentRoute()
    // {
    //     return $this->requestStack->getCurrentRequest()->attributes->get('_route');
    // }

    public function navbarAdmin()
    {
        $currentRoute = $this->requestStack->getCurrentRequest()->attributes->get('_route');

        $navbar = new NavbarManager();
        $navbar->push(new NavbarItem('Admin', $this->router->generate('admin_admin'), $currentRoute === 'admin_admin'));
        $navbar->push(new NavbarItem('Storages', $this->router->generate('admin_storages'), $currentRoute === 'admin_storages'));
        $navbar->push(new NavbarItem('Equipments', $this->router->generate('admin_equipments'), $currentRoute === 'admin_equipments'));

        return $navbar->getItems();
    }
}
