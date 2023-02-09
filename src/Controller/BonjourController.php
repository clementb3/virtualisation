<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class BonjourController extends AbstractController
{
    #[Route('/bonjour/{aQui}', name: 'bonjour')]
    #[Route('/', name: 'accueil')]
    public function index(string $aQui = "joyeux contribuable"): Response
    {
        return $this->render('bonjour/index.html.twig', [
            'aQui' => $aQui,
            'laDate' => (new \DateTime())->format("Y-m-d"),
        ]);
    }



    #[Route('/message/{aQui}', name: 'message')]
    // Pour illustrer l'utilisation des messages flash Symfony
    public function message(string $aQui = ""): Response
    {
        if (empty($aQui))
        {
            // Pour tester avec deux messages d'alerte
            $this->addFlash('danger', "Vous n'avez pas dit à qui s'adressait le message...");
            $this->addFlash('success', "Mais bonjour quand même !");
            return $this->redirectToRoute('icones');
        } else {
            $this->addFlash(
                'success', // primary, secondary, success, danger, warning, info, light, dark
                "Comment ça va $aQui ?"
            );
            return $this->redirectToRoute('bonjour', ['aQui' => $aQui]);
        }
    }

    #[Route('/icons', name: 'icones')]
    public function icons()
    {
        return $this->render('bonjour/icons.html.twig');
    }


}
