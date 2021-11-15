<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Repository\CustomerRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cart", name="cart_")
 */
class CartController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(SessionInterface $session, ProductRepository $productRepository, OrderRepository $orderRepository){

        //GESTION DU PANIER
        //---------------------------------------------------------------------------------

        $panier = $session->get("panier", []);

        // On "fabrique" les données
        $dataPanier = [];
        $total = 0;

        foreach($panier as $id => $quantite){
            $product = $productRepository->find($id);
            $dataPanier[] = [
                "produit" => $product,
                "quantite" => $quantite,
            ];
            $total += $product->getPrice() * $quantite;
        }

        //AFFICHAGE DES COMMANDES AVEC TRI PAR DATE
        //---------------------------------------------------------------------------------

        $orders = $orderRepository->findBy(
            array('customer' => $this->getUser()),
            array('orderDate'=> 'ASC')
        );

        return $this->render('cart/index.html.twig', compact("dataPanier", "total", "orders"));
    }

    /**
     * @Route("/add/{id}", name="add")
     */
    public function add(Product $product, SessionInterface $session)
    {

        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $product->getId();

        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id] = 1;
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }

    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove(Product $product, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $product->getId();

        if(!empty($panier[$id])){
            if($panier[$id] > 1){
                $panier[$id]--;
            }else{
                unset($panier[$id]);
            }
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Product $product, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $product->getId();

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }

    /**
     * @Route("/order", name="add_order")
     */
    public function addOrder(EntityManagerInterface $emi, ProductRepository $productRepository, CustomerRepository $customerRepository, OrderRepository $orderRepository, SessionInterface $session)
    {
        //On récupère la date du jour
        $now = new \DateTime();

        //On récupère l'ID du user
        $customer = $this->getUser();

        // On récupère le prix total du panier actuel
        $panier = $session->get("panier", []);
        $total = 0;

        foreach($panier as $id => $quantite){
            $product = $productRepository->find($id);
            $dataPanier[] = [
                "produit" => $product,
                "quantite" => $quantite,
            ];
            $total += $product->getPrice() * $quantite;
        }

        // On crée un nouvel order
        $order = new Order();
        $order ->setCustomer($customer)
               ->setStatus("processing")
               ->setOrderDate($now)
               ->setPrice($total);

        // On insère l'order en base de données
        $emi->persist($order);
        $emi->flush();

        // On vide le panier de la session
        $session->remove("panier");
        return $this->redirectToRoute("cart_index");
    }

}
