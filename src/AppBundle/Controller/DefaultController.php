<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Coche;

class DefaultController extends FOSRestController {

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request) {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
                    'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..'),
        ]);
    }

    /**
     * @Rest\Get("/api")
     */
    public function getAction() {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:Coche')->findAll();
        if ($restresult === null) {
            return new View("No hay coches en la bbdd", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @Rest\Post("/api")
     */
    public function postAction(Request $request) {
        $coche = new Coche;
        $marca = $request->get('marca');
        $coche->setMarca($marca);

        $em = $this->getDoctrine()->getManager();
        $em->persist($coche);
        $em->flush();
        return new View("Coche añadido correctamente", Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/api/{id}")
     */
    public function updateAction($id, Request $request) {
        $marca = $request->get('marca');
        $sn = $this->getDoctrine()->getManager();
        $cocheId = $this->getDoctrine()->getRepository('AppBundle:Coche')->find($id);
        if (empty($cocheId)) {
            return new View("Coche no encontrado", Response::HTTP_NOT_FOUND);
        } elseif (!empty($marca)) {
            $cocheId->setMarca($marca);
            $sn->flush();
            return new View("Marca de coche actualizada correctamente", Response::HTTP_OK);
        }
    }

    /**
     * @Rest\Delete("/api/{id}")
     */
    public function deleteAction($id) {
        $sn = $this->getDoctrine()->getManager();
        $coche = $this->getDoctrine()->getRepository('AppBundle:Coche')->find($id);
        if (empty($coche)) {
            return new View("Marca de coche no encontrada", Response::HTTP_NOT_FOUND);
        } else {
            $sn->remove($coche);
            $sn->flush();
        }
        return new View("Marca borrada correctamente", Response::HTTP_OK);
    }
}
