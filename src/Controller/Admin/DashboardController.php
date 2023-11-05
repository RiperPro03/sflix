<?php

namespace App\Controller\Admin;

use App\Entity\Serie;
use App\Entity\Utilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();

        $totalUsers = $em->getRepository(Utilisateur::class)->count([]);
        $totalSeries = $em->getRepository(Serie::class)->count([]);

        // Get the number of series liked by each user
        $usersWithSeriesCount = $em->createQuery(
            'SELECT u.username, COUNT(s.id) as seriesCount
            FROM App\Entity\Utilisateur u
            JOIN u.series s
            GROUP BY u.id'
        )->getResult();

        $topSeries = $em->createQuery(
            'SELECT s.title, COUNT(u.id) as likesCount
                FROM App\Entity\Serie s
                JOIN s.utilisateurs u
                GROUP BY s.id
                ORDER BY likesCount DESC'
            )->setMaxResults(10)
            ->getResult();

        return $this->render('admin/dashboard.html.twig', [
            'totalUsers' => $totalUsers,
            'totalSeries' => $totalSeries,
            'seriesPerUser' => $usersWithSeriesCount,
            'topSeries' => $topSeries,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Sflix');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateur', 'fa fa-user', Utilisateur::class);
        yield MenuItem::linkToCrud('Series', 'fas fa-list', Serie::class);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
