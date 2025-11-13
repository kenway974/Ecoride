<?php

namespace App\Controller\Admin;

use App\Repository\TripRepository;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin_dashboard')]
class DashboardController extends AbstractDashboardController
{
    private TripRepository $tripRepository;

    public function __construct(TripRepository $tripRepository)
    {
        $this->tripRepository = $tripRepository;
    }

    public function index(): Response
    {
        // Nombre total de trips terminés
        $totalTrips = $this->tripRepository->getTripsGroupedByDay();
        
        // Nombre total de trips crédits encaissés
        $completedTrips = $this->tripRepository->getCompletedTripsGroupedByDay();
        $totalCredits = count($completedTrips) * 2;

        // On peut rendre un template custom pour le dashboard
        return $this->render('admin/dashboard.html.twig', [
            'totalTrips' => $totalTrips,
            'totalCredits' => $totalCredits,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Backend');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        // Exemple : liens vers tes CRUD
        // yield MenuItem::linkToCrud('Trips', 'fas fa-car', Trip::class);
        // yield MenuItem::linkToCrud('Users', 'fas fa-users', User::class);
    }
}
