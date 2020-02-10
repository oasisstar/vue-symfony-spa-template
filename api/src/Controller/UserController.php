<?php declare(strict_types = 1);

namespace App\Controller;

use App\Utils\MailerDispatcherInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use FOS\UserBundle\Form\Type\ResettingFormType;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Rest\Route("/api/users")
 */
class UserController extends AbstractFOSRestController
{
    /**
     * 'data' is a required wrapper for websanova auth library
     *
     * @Rest\View()
     * @Rest\Get()
     * @return array|View
     */
    public function getCurrentUser()
    {
        return [ 'data' => $this->getUser() ];
    }

    /**
     * @Rest\View()
     * @Rest\Post("/email", name="edit_user")
     * @return FormInterface|View
     */
    public function editUserEmail(
        Request $request,
        MailerDispatcherInterface $mailerDispatcher,
        UserManagerInterface $userManager
    ) {
        $user = $this->getUser();
        $form = $this->createFormBuilder($user, ['validation_groups' => ["changeEmail"]])
            ->add('tempEmail', EmailType::class)
            ->getForm();

        $form->submit($request->request->getIterator()->getArrayCopy());

        if (!$form->isValid()) {
            return $form;
        }

        $tmpUser = clone $user;
        $tmpUser->setEmail($user->getTempEmail());
        $mailerDispatcher->sendEmailConfirmation($tmpUser);
        $user->setConfirmationToken($tmpUser->getConfirmationToken());
        $userManager->updateUser($user);

        return $this->view(null, 200);
    }

    /**
     * @Rest\View()
     * @Rest\Patch("/password")
     * @return View|FormInterface
     */
    public function editUserPassword(Request $request, UserManagerInterface $userManager)
    {
        $user = $this->getUser();
        $form = $this->createForm(ResettingFormType::class, $user);

        $form->submit($request->request->getIterator()->getArrayCopy());

        if (!$form->isValid()) {
            return $form;
        }

        $userManager->updatePassword($user);

        return $this->view(null, 200);
    }
}
