<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Price;
use App\Entity\Product;
use App\Form\CategoryForm;
use App\Services\ProductService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/onliner", name="onliner_")
 */
class OnlinerController extends AbstractController
{
    public ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @Route ("/products", name="show_all_products")
     */
    public function showAllProducts()
    {
        return $this->render('all_products.html.twig', [
            'products' => $this->productService->getAllProducts(),
        ]);
    }

//    /**
//     * @Route ("/load", name="load")
//     */
//    public function load(Request $request, EntityManagerInterface $em): Response
//    {
//        $json = $this->parserService->parseAllProducts();
//
//        $data = $this->jsonService->getData($json);
//        $this->itemService->insertRecords($data);
//
//        return $this->render('status_of_load.html.twig', [
//            'products' => $em->getRepository(Product::class)->findAll(),
//        ]);
//    }

    /**
     * @Route ("/products/{id}", name="get_product_by_id")
     * @Entity("product", expr="repository.getByOnlinerId(id)")
     */
    public function getProduct(Product $product, Request $request)
    {
        $form = $this->createForm(CategoryForm::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();
        }

        return $this->renderForm('product.html.twig', [
            'form' => $form,
            'product' => $product,
        ]);
    }

    /**
     * @Route ("/info", name="get_info")
     */
    public function getInfo()
    {
        $doctrine = $this->getDoctrine();

        $priceRepository = $doctrine->getRepository(Price::class);
        $categoryRepository = $doctrine->getRepository(Category::class);

        return $this->renderForm('info.html.twig', [
            'info' => [
                'max_price' => $priceRepository->getMaxPrice(),
                'min_price' => $priceRepository->getMinPrice(),
                'categories' => $categoryRepository->getCategoryWithAmountProducts(),
            ]
        ]);
    }
}