<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Image;
use App\Entity\Produit;
use App\Form\ProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produits", name="tousproduits")
     */
    public function tousproduits(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
    {
        $repository = $entityManager->getRepository(Produit::class);
        $listeProduits = $repository->findAll();

        $pagination = $paginator->paginate(
            $listeProduits,
            $request->query->getInt('page', 1),
            5
        );

        if (!$listeProduits) {
            return $this->redirectToRoute('ajoutproduit');
        }

        return $this->render('produit/index.html.twig', [
            'produits' => $listeProduits,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/ajoutproduit", name="ajoutproduit")
     * @IsGranted("ROLE_VENDEUR", message="Vous devez être vendeur pour accéder à cette page")
     * @IsGranted("ROLE_ADMIN", message="Vous devez être admin pour accéder à cette page")
     */

    public function ajoutproduit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imagesFiles = $request->files->get('produit')['images_files'];
            if (!empty($imagesFiles)) {
                foreach ($imagesFiles as $imageFile) {
                    $image = new Image();
                    $fileName = md5(uniqid()) . '.' . $imageFile->guessExtension();
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                    $image->setNom($fileName);
                    $produit->addImage($image);
                    $entityManager->persist($image);
                }
            }

            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('tousproduits');
        }
        dump($form);
        return $this->renderForm('produit/ajoutproduit.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @Route("/detailproduit/{id}", name="detailproduit")
     */

    public function detailproduit($id, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Produit::class);
        $produit = $repository->find($id);

        $repository = $entityManager->getRepository(Categorie::class);
        $categorie = $repository->find(['id' => $produit->getCategorie()]);

        return $this->render('produit/detailproduit.html.twig', [
            'produit' => $produit,
            'categorie' => $categorie
        ]);
    }

    /**
     * @Route("//detailproduit/{id}/supprimer", name="supprimerproduit")
     * @IsGranted("ROLE_ADMIN", message="Vous devez être admin pour accéder à cette page")
     */

    public function supprimerproduit(int $id, EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(Produit::class);
        $produit = $repository->find($id);

        $entityManager->remove($produit);
        $entityManager->flush();

        return $this->redirectToRoute('tousproduits');
    }

    /**
     * @Route("/detailproduit/{id}/modifier", name="modifierproduit")
     * @IsGranted("ROLE_VENDEUR", message="Vous devez être vendeur pour accéder à cette page")
     */

    public function modifierproduit(int $id, Request $request, EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(Produit::class);
        $produit = $repository->find($id);
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produit = $form->getData();
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('tousproduits');
        }
        return $this->renderForm('produit/modifierproduit.html.twig', [
            'form' => $form,
            'produit' => $produit
        ]);
    }
}
