<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Entity\User;
use App\Form\PinType;
use App\Repository\PinRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{


    /**
     * @Route("/", name="app_home", methods={"GET"})
     * @Route("/", name="app_pins_index", methods={"GET"})
     * @param PinRepository $pinRepository
     * @return Response
     */
    public function index(PinRepository $pinRepository): Response
    {
        $pins = $pinRepository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('pins/index.html.twig', compact('pins'));
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_show", methods={"GET"})
     * @param Pin $pin
     * @return Response
     */
    public function show(Pin $pin): Response
    {
        return $this->render('pins/show.html.twig', compact('pin'));
    }

    /**
     * @Route("/pins/create", name="app_pins_create", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserRepository $userRepository
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em, UserRepository $userRepository): Response
    {
        $pin = new Pin();
        $form = $this->createForm(PinType::class, $pin);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $janinedoe = $userRepository->findOneBy(['email' => 'janinedoe@hotmail.fr']);
            $pin->setUser($janinedoe);
            $em->persist($pin);
            $em->flush();

            $this->addFlash('success', 'pin crée avec succes');
            return $this->redirectToRoute('app_pins_show', ['id' => $pin->getId()]);
        }

        return $this->render('pins/create.html.twig', [
            'form' => $form->createView()
        ]);

    }





    /**
     * @Route("/pins/{id<[0-9]+>}/edit", name="app_pins_edit", methods={"GET", "PUT"})
     * @param Request $request
     * @param Pin $pin
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function edit(Request $request, Pin $pin, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(PinType::class, $pin, [
            'method' => 'PUT'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'pin modifié avec succes');
            return $this->redirectToRoute('app_pins_show', ['id' => $pin->getId()]);
        }

        return $this->render('pins/edit.html.twig', [
            'pin' => $pin,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_delete", methods={"DELETE"})
     * @param Request $request
     * @param Pin $pin
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function delete(Request $request, Pin $pin, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('pins_delete' . $pin->getId(), $request->request->get('csrf_token'))){
            $em->remove($pin);
            $em->flush();
            $this->addFlash('info', 'pin supprimé avec succes');
        }
        return $this->redirectToRoute('app_home');
    }


}
