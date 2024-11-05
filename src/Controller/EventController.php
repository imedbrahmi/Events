<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/', name: 'app_event')]
    public function index(EventRepository $er): Response
    {
        $ListEvents = $er->findAll();
        return $this->render('event/list.html.twig',
            ['listeEvents' => $ListEvents]
        );
    }

#[Route('/new', name: 'app_new')]

    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute('app_event');
        }
        return $this->render('event/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/{id}', name: 'event_delete')]
    public function delete(EntityManagerInterface $em, EventRepository $eventR, $id): Response
    {
        $event = $eventR->find($id);
        $em->remove($event);
        $em->flush();
        return $this->redirectToRoute('app_event');
    }

    #[Route('/{id}/edit', name: 'event_update')]
    public function edit(Request $request, EntityManagerInterface $em, EventRepository $eventR, $id): Response{
        $event = $eventR->find($id);
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute('app_event');
        }
        return $this->render('event/edit.html.twig', ['form' => $form->createView()]);
    }
}