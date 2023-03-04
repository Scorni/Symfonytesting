<?php

namespace App\Controller;

use App\Entity\Subject;
use App\Entity\User;
use App\Form\SubjectType;
use App\Repository\SubjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/subject')]
class SubjectController extends AbstractController
{
    private $user;

    public function __construct(AuthenticationUtils $authenticationUtils,EntityManagerInterface $entityManager)
    {
        $this->user = $entityManager->getRepository(User::class)->findOneBy(['name' => $authenticationUtils->getLastUsername()]);
    }

    #[Route('/', name: 'app_subject_index', methods: ['GET'])]
    public function index(SubjectRepository $subjectRepository): Response
    {

        return $this->render('subject/index.html.twig', [
            'subjects' => $subjectRepository->findAll(),
            'mySubjects' => $subjectRepository->findby(['Author' => $this->user]),

        ]);
    }

    #[Route('/new', name: 'app_subject_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SubjectRepository $subjectRepository,AuthenticationUtils $authenticationUtils,EntityManagerInterface $entityManager): Response
    {
        $this->user = $entityManager->getRepository(User::class)->findOneBy(['name' => $authenticationUtils->getLastUsername()]);

        $subject = new Subject();
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subject->setAuthor($this->user);
            $subjectRepository->save($subject, true);

            return $this->redirectToRoute('app_subject_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('subject/new.html.twig', [
            'subject' => $subject,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_subject_show', methods: ['GET'])]
    public function show(Subject $subject): Response
    {

        return $this->render('subject/show.html.twig', [
            'subject' => $subject,
            'message' => $subject->getMessages()
        ]);
    }

    #[Route('/{id}/edit', name: 'app_subject_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Subject $subject, SubjectRepository $subjectRepository,AuthenticationUtils $authenticationUtils,EntityManagerInterface $entityManager): Response
    {
        $this->user = $entityManager->getRepository(User::class)->findOneBy(['name' => $authenticationUtils->getLastUsername()]);

        $this->subjectAuthor = $subject->getAuthor();

        if ($this->subjectAuthor === $this->user){
            $form = $this->createForm(SubjectType::class, $subject);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $subjectRepository->save($subject, true);

                return $this->redirectToRoute('app_subject_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('subject/edit.html.twig', [
                'subject' => $subject,
                'form' => $form,
            ]);
        }else{
            return $this->redirectToRoute('app_error', [], Response::HTTP_SEE_OTHER);
        }


    }

    #[Route('/{id}', name: 'app_subject_delete', methods: ['POST'])]
    public function delete(Request $request, Subject $subject, SubjectRepository $subjectRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$subject->getId(), $request->request->get('_token'))) {
            $subjectRepository->remove($subject, true);
        }

        return $this->redirectToRoute('app_subject_index', [], Response::HTTP_SEE_OTHER);
    }
}
