<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Profile;
use App\Form\EditProfileFormType;
use App\Repository\ProfileRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Rest\Route("/api/profiles")
 */
class ProfileController extends AbstractFOSRestController
{
    /**
     * @Rest\View()
     * @Rest\Patch(name="profile_edit")
     * @return View|FormInterface
     */
    public function editProfile(Request $request)
    {
        $profile = $this->getProfileRepository()->getProfileByUser($this->getUser());
        $form = $this->createForm(EditProfileFormType::class, $profile);

        $form->submit($request->request->getIterator()->getArrayCopy());

        if (!$form->isValid()) {
            return $form;
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($profile);
        $em->flush();

        return $this->view(null, 200);
    }

    private function getProfileRepository(): ProfileRepository
    {
        return $this->getDoctrine()->getRepository(Profile::class);
    }
}
