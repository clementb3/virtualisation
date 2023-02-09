<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use ContainerRjSDclD\get_Console_Command_About_LazyService;
use Doctrine\DBAL\Types\FloatType;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Scalar\MagicConst\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class matiereController extends AbstractController
{
    #[Route('/matiere', name: 'matiere')]
	public function matiere(Request $request, UtilisateurRepository $userRepository,ManagerRegistry $doctrine): Response
    	{
        $defaultData = ['message' => 'Type your message here'];



        $form = $this->createFormBuilder($defaultData)
            ->add('prof', EntityType::class,
                [
                    'class' => Utilisateur::class,
                    'choice_label' => function ($category) {
                        return $category->getDisplayName();

                    }
                ])
            ->add('libelle', TextType::class)
            ->add('send', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = new Matiere();
            $user->setLibelle($form->get('libelle')->getData());
            $user->setProf($form->get('prof')->getData());
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();
        }
        return $this->render('user/index.html.twig', [
            'form' => ($form),
        ]);
	}






}
