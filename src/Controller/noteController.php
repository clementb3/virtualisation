<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Entity\Note;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use ContainerRjSDclD\get_Console_Command_About_LazyService;
use Doctrine\DBAL\Types\FloatType;
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

class noteController extends AbstractController
{
    #[Route('/note', name: 'note')]
	public function note(Request $request, UtilisateurRepository $userRepository,ManagerRegistry $doctrine): Response
    	{
        $defaultData = ['message' => 'Type your message here'];
        $form = $this->createFormBuilder($defaultData)
            ->add('etudiant', EntityType::class,
                [
                    'class' => Utilisateur::class,
                    'choice_label' => function ($category) {
                        return $category->getDisplayName();

                    }
                ])
            ->add('matiere', EntityType::class,
                [
                    'class' => Matiere::class,
                    'choice_label' => function ($category) {
                        return $category->getLibelle();

                    }
                ])
            ->add('note', NumberType::class)
            ->add('send', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $note = new Note();
            $note->setEtudiant($form->get('etudiant')->getData());
            $note->setMatiere($form->get('matiere')->getData());
            $note->setValeur($form->get('note')->getData());

            $em = $doctrine->getManager();
            $em->persist($note);
            $em->flush();
        }
        return $this->render('user/index.html.twig', [
            'form' => ($form),
        ]);
	}


    #[Route('/note/import', name: 'import note')]
    public function importnote(ManagerRegistry $doctrine, Request $request): Response
    {
        $defaultData = ['message' => 'Type your message here'];
        $form = $this->createFormBuilder($defaultData)
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
            ->add('matiere', EntityType::class,
                [
                    'class' => Matiere::class,
                    'choice_label' => function ($category) {
                        return $category->getLibelle();

                    }
                ])
            ->add('upload', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->get('file')->getData();
            $handle = fopen($data->getpathName(),'r');
            while ( ($datacsv = fgetcsv($handle) ) !== FALSE ) {
                $notecsv = explode(";",$datacsv[0]);
                $note = new Note();

                $repo = $doctrine->getRepository(Utilisateur::class);
                $note->setEtudiant($repo->find($notecsv[0]));
                $note->setMatiere($form->get('matiere')->getData());
                $note->setValeur($notecsv[1]);

                $em = $doctrine->getManager();
                $em->persist($note);
                $em->flush();
            }


        }
        return $this->render('user/import.html.twig', [
            'form' => ($form),
        ]);
    }

    #[Route('/note/liste', name: 'liste note')]
    public function listenote(ManagerRegistry $doctrine, Request $request): Response{
        $repo = $doctrine->getRepository(Note::class);
        $lesNote = $repo->findAll();
        return $this->render('note/liste.html.twig', [
            'note' => $lesNote,
        ]);
    }


}
