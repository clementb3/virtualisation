<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use ContainerRjSDclD\get_Console_Command_About_LazyService;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Scalar\MagicConst\File;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class userController extends AbstractController
{
    #[Route('/user', name: 'utilisateur')]
	public function contact(Request $request, UtilisateurRepository $userRepository,ManagerRegistry $doctrine): Response
    	{
        $defaultData = ['message' => 'Type your message here'];
        $form = $this->createFormBuilder($defaultData)
            ->add('username', TextType::class)
            ->add('password', TextType::class)
            ->add('displayname', type: TextType::class)
            ->add('role', ChoiceType::class,
                ['choices'  => [
                    'enseignant' => false,
                    'étudiant' => true,
                ]])
            ->add('send', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = new Utilisateur();
            $user->setUsername($form->get('username')->getData());
            $user->setDisplayName($form->get('displayname')->getData());
            $user->setPassword($form->get('password')->getData());
            if($form->get('role')->getData()) {
                $user->setRoles(explode(" ", "ROLE_ETUDIANT"));
            }
            else{
                $user->setRoles(explode(" ", "ROLE_PROF"));
            }
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();
        }
        return $this->render('user/index.html.twig', [
            'form' => ($form),
        ]);
	}


    #[Route('/user/import', name: 'import utilisateur')]
    public function importuser(ManagerRegistry $doctrine, Request $request): Response
    {
        $defaultData = ['message' => 'Type your message here'];
        $form = $this->createFormBuilder($defaultData)
            ->add('role', ChoiceType::class,
                ['choices'  => [
        'enseignant' => false,
        'étudiant' => true,
    ],
])
            ->add('file', FileType::class, [
                'label' => 'File',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
            ])
            ->add('upload', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->get('file')->getData();
            $handle = fopen($data->getpathName(),'r');
            while ( ($datacsv = fgetcsv($handle) ) !== FALSE ) {
                $usercsv = explode(";",$datacsv[0]);
                $user = new Utilisateur();
                $user->setUsername($usercsv[0]);
                $user->setDisplayName($usercsv[1]);
                $user->setPassword($usercsv[2]);
                if($form->get('role')->getData()) {
                    $user->setRoles(explode(" ", "ROLE_ETUDIANT"));
                }
                else{
                    $user->setRoles(explode(" ", "ROLE_PROF"));
                }
                $em = $doctrine->getManager();
                $em->persist($user);
                $em->flush();
            }


        }
        return $this->render('user/import.html.twig', [
            'form' => ($form),
        ]);
    }


}
