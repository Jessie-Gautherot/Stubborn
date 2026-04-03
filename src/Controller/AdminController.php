<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\AddProductType;
use App\Form\EditProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class AdminController
 *
 * Gestion de l'administration des produits : ajout, modification et suppression.
 * - Affiche le formulaire d'ajout de produit.
 * - Prépare les formulaires d'édition pour chaque produit.
 */
class AdminController extends AbstractController
{
  /**
     * Page d'administration des produits.
     *
     * - Gère l'ajout d'un produit.
     * - Gère la modification et la suppression d'un produit.
     *
     * @param Request $request Requête HTTP
     * @param EntityManagerInterface $em Gestionnaire d'entités Doctrine
     * @param ProductRepository $productRepository Repository pour récupérer les produits
     *
     * @return Response La réponse HTTP avec le rendu du template admin
     */
    #[Route('/admin', name: 'app_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(
        Request $request,
        EntityManagerInterface $em,
        ProductRepository $productRepository
    ): Response {
        // FORMULAIRE AJOUT PRODUIT
        $addProductForm = $this->createForm(AddProductType::class, new Product());
        $addProductForm->handleRequest($request);

        if ($addProductForm->isSubmitted() && $addProductForm->isValid()) {
            $product = $addProductForm->getData();
            // gérer l'upload de l'image
    $imageFile = $addProductForm->get('image')->getData();
    if ($imageFile) {
        $newFilename = uniqid().'.'.$imageFile->guessExtension();

        try {
            $imageFile->move(
                $this->getParameter('images_directory'),
                $newFilename
            );
        } catch (FileException $e) {
    $this->addFlash('error', 'Erreur upload image : '.$e->getMessage());
}

        // stocker le nom en base
        $product->setImage($newFilename);
    }
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Produit ajouté avec succès !');

            return $this->redirectToRoute('app_admin');
        }

        // Récupération des produits 
        $products = $productRepository->findAllProducts();

        // Préparer les formulaires d'édition
        $productForms = [];
        foreach ($products as $product) {
            $productForms[$product->getId()] = $this->createForm(EditProductType::class, $product)->createView();
        }

        // Gestion formulaire soumis 
        $submittedId = $request->request->get('action_id'); 
        $action = $request->request->get('action');

        if ($submittedId && $action) {
            $product = $productRepository->find($submittedId);

            if ($product) {
                $form = $this->createForm(EditProductType::class, $product);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {

                     // Gestion upload image si nouveau fichier
                $imageFile = $form->get('image')->getData();
                if ($imageFile) {
                    $newFilename = uniqid().'.'.$imageFile->guessExtension();
                    try {
                        $imageFile->move(
                            $this->getParameter('images_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                       
                    }
                    $product->setImage($newFilename);
                }
                    if ($action === 'update') {
                        $em->flush();
                        $this->addFlash('success', "Produit '{$product->getName()}' modifié avec succès !");
                    } elseif ($action === 'delete') {
                      // Supprimer l'image si elle existe
                    if ($product->getImage() && file_exists($this->getParameter('images_directory').'/'.$product->getImage())) {
                        unlink($this->getParameter('images_directory').'/'.$product->getImage());
                    }
                        $em->remove($product);
                        $em->flush();
                        $this->addFlash('success', "Produit '{$product->getName()}' supprimé !");
                    }

                    return $this->redirectToRoute('app_admin');
                }
            }
        }

        //Envoi à Twig
        return $this->render('admin.html.twig', [
            'addProductForm' => $addProductForm->createView(),
            'products' => $products,
            'productForms' => $productForms,
        ]);
    }
}