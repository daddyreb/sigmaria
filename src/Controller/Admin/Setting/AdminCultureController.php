<?php

namespace App\Controller\Admin\Setting;

use App\Controller\Base\BaseController;
use App\Entity\Setting\Culture;
use App\Form\Setting\CultureType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class AdminCultureController extends BaseController
{
    /**
     * @Route("/admin/setting/culture/create", name="culture_create")
     * @Template("setting/culture/create.html.twig")
     */
    public function createCultureAction(Request $request)
    {
        $form = $this->createForm(CultureType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $culture = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($culture);
            $entityManager->flush();

            $this->addFlash('success', 'Pochodzenie stworzone!');

            return $this->redirectToRoute('culture_list');
        }

        $templateData = [
            'form' => $form->createView(),
            'entityName' => 'culture',
        ];

        return array_merge($templateData, $this->getTemplateData(BaseController::NAV_TAB_RULES));
    }

    /**
     * @Route("/admin/setting/culture/{id}/edit", name="culture_edit")
     * @Template("setting/culture/edit.html.twig")
     */
    public function editCultureAction(Request $request, Culture $culture)
    {
        $form = $this->createForm(CultureType::class, $culture);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $culture = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($culture);
            $entityManager->flush();

            $this->addFlash('success', 'Pochodzenie zmienione!');

            return $this->redirectToRoute('culture_show', ['id' => $culture->getId()]);
        }

        $templateData = [
            'form' => $form->createView(),
            'entityName' => 'culture',
        ];

        return array_merge($templateData, $this->getTemplateData(BaseController::NAV_TAB_RULES));
    }

    /**
     * @Route("/admin/setting/culture/{id}/kill", name="culture_kill")
     */
    public function killCultureAction(Culture $culture)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $culture->setIsActive(false);

        $entityManager->persist($culture);
        $entityManager->flush();

        $this->addFlash('warning', 'Pochodzenie zabite!');

        return $this->redirectToRoute('culture_list');
    }

    /**
     * @Route("/admin/setting/culture/{id}/revive", name="culture_revive")
     */
    public function reviveCultureAction(Culture $culture)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $culture->setIsActive(true);

        $entityManager->persist($culture);
        $entityManager->flush();

        $this->addFlash('success', 'Pochodzenie wskrzeszone!');

        return $this->redirectToRoute('culture_list');
    }

    /**
     * @Route("/admin/setting/culture/{id}/delete", name="culture_delete")
     */
    public function deleteCultureAction(Culture $culture)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($culture);
        $entityManager->flush();

        $this->addFlash('danger', 'Pochodzenie usunięte!');

        return $this->redirectToRoute('culture_list');
    }

    /**
     * @Route("/admin/setting/culture/{id}/stage", name="culture_stage")
     */
    public function stageCultureAction(Culture $culture)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $culture->setIsToBeReleased(true);

        $entityManager->persist($culture);
        $entityManager->flush();

        $this->addFlash('success', 'Pochodzenie oznaczone do wydania!');

        return $this->redirectToRoute('culture_list');
    }

    /**
     * @Route("/admin/setting/culture/{id}/unstage", name="culture_unstage")
     */
    public function unstageCultureAction(Culture $culture)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $culture->setIsToBeReleased(false);

        $entityManager->persist($culture);
        $entityManager->flush();

        $this->addFlash('warning', 'Pochodzenie wyłączone z wydania!');

        return $this->redirectToRoute('culture_list');
    }
}