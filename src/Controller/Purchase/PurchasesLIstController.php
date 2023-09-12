<?php

namespace App\Controller\Purchase;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\User;
use Twig\Environment;

class PurchasesLIstController extends AbstractController
{
    protected $security;

    public function __construct(Security $security, Environment $twig)
    {
        $this->security = $security;
    }

    /**
     * @Route("/purchases", name="purchases_index")
     */

    public function index()
    {
        /** @var User */
        $user = $this->security->getUser();

        if(!$user) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('purchase/index.html.twig', ['purchases' => $user->getCommande()]);
    }
}