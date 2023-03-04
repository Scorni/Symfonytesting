<?php

namespace App\Controller;

use App\Entity\Subject;
use App\Entity\User;
use App\Form\CreateSubjectType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OldSubjectController extends AbstractController
{
    #[Route('/Oldsubject', name: 'app_subject')]
    public function subject(Request $request, EntityManagerInterface $entityManager): Response
    {
        $subject = new Subject();
        $user = $entityManager->getRepository(User::class)->findOneBy(['name' => Security::LAST_USERNAME]);
//        $user = $this->getUser();
        $form = $this->createForm(CreateSubjectType::class, $subject);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password

            $subject->setAuthor($user);
            $subject->setTitle(
                $form->get('Title')->getData()
            );
            $subject->setDescription(
                $form->get('Description')->getData()
            );

            $entityManager->persist($subject);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_home',['code','123']);
        }
        return $this->render('subject/subject.html.twig', [
            'subjectForm' => $form->createView(),
        ]);
    }
    public function getSubject(Request $request, EntityManagerInterface $entityManager): Response
    {
//        $request->getSession()->set(Security::LAST_USERNAME, $name);
        $user = $entityManager->getRepository(User::class)->findOneBy(['name' => 'patate']);
//        $user = $this->getUser();
        $subject = $entityManager->getRepository(Subject::class)->findAll();
        $form = $this->createForm(CreateSubjectType::class, $subject);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password

            $subject->setAuthor($user);
            $subject->setTitle(
                $form->get('Title')->getData()
            );
            $subject->setDescription(
                $form->get('Description')->getData()
            );

            $entityManager->persist($subject);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_home',['code','123']);
        }
        return $this->render('subject/subject.html.twig', [
            'subjectForm' => $form->createView(),
        ]);
    }
}
