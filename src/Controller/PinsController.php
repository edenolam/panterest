<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Repository\PinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     * @Route("/", name="app_pins_index")
     * @param PinRepository $pinRepository
     * @return Response
     */
    public function index(PinRepository $pinRepository):Response
    {
        $pins = $pinRepository->findBy([], ['createdAt' => 'DESC'], 3);
        return $this->render('pins/index.html.twig', compact('pins'));
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_show")
     * @param Pin $pin
     * @return Response
     */
    public function show(Pin $pin):Response
    {
        return $this->render('pins/show.html.twig', compact('pin'));
    }
}
