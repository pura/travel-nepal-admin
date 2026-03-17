<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Travel Nepal Admin');
    }

    public function configureAssets(): Assets
    {
        return Assets::new()
            ->addCssFile('css/admin.css');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Content');
        yield MenuItem::linkTo(DestinationCrudController::class, 'Destinations', 'fa fa-map-marker-alt');
        yield MenuItem::linkTo(HotelCrudController::class, 'Hotels', 'fa fa-hotel');
        yield MenuItem::linkTo(GuideCrudController::class, 'Guides', 'fa fa-user-tie');
        yield MenuItem::linkTo(TransportServiceCrudController::class, 'Transport', 'fa fa-car');
        yield MenuItem::section('Suppliers & Regions');
        yield MenuItem::linkTo(SupplierCrudController::class, 'Suppliers', 'fa fa-handshake');
        yield MenuItem::linkTo(RegionCrudController::class, 'Regions', 'fa fa-globe-asia');
        yield MenuItem::section('Itineraries');
        yield MenuItem::linkTo(ItineraryTemplateCrudController::class, 'Itinerary templates', 'fa fa-route');
        yield MenuItem::section('Contacts');
        yield MenuItem::linkTo(RepresentativeCrudController::class, 'Representatives', 'fa fa-address-book');
    }
}
