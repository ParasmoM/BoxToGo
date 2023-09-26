<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    /**
     * createdForm
     *
     * Cette fonction crée un formulaire en fonction d'une entité et d'une requête données.
     * Elle cherche la classe d'entité et la classe de formulaire correspondantes dans différents namespaces.
     *
     * @param  mixed $entity  Le nom de l'entité pour laquelle créer un formulaire.
     * @param  mixed $request La requête HTTP.
     * @return \Symfony\Component\Form\FormInterface|null Le formulaire créé ou null si la classe de formulaire ou d'entité n'existe pas.
     * @throws \Exception Si la classe de formulaire n'existe pas.
     */
    public function createdForm($entity, $request)
    {
        // Construire le nom complet de la classe d'entité
        $entityClassName =  'App\Entity\\' . ucfirst($entity);
        
        // Vérifier et créer l'instance d'entité
        if (class_exists($entityClassName)) {
            $instance = new $entityClassName();
        } else {
            $instance = null;
        }

        // Construire le nom complet de la classe de formulaire dans le namespace par défaut
        $formTypeClassName = 'App\Form\\' . ucfirst($entity) . 'FormType';

        // Si la classe de formulaire n'existe pas dans le namespace par défaut, chercher dans un autre namespace (ici 'App\Form\Admin')
        if (!class_exists($formTypeClassName)) {
            $formTypeClassName = 'App\Form\Admin\\' . ucfirst($entity) . 'FormType';
        }

        // Vérifier et créer le formulaire
        if (class_exists($formTypeClassName)) {
            $form = $this->createForm($formTypeClassName, $instance);
            $form->handleRequest($request);
        } else {
            throw new \Exception("Le formulaire $formTypeClassName n'existe pas");
        }

        return $form;
    }
}