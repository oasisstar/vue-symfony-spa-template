<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Utils\MailerDispatcherInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use FOS\UserBundle\Form\Type\ResettingFormType;
use FOS\UserBundle\Model\UserManagerInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Rest\Route("/api/auth")
 */
class AuthController extends AbstractFOSRestController
{
    /**
     * @Rest\View()
     * @Rest\Post("/register")
     * @return FormInterface|View
     */
    public function register(
        Request $request,
        UserManagerInterface $userManager,
        MailerDispatcherInterface $mailerDispatcher
    ) {
        $user = $userManager->createUser();
        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->submit($request->request->getIterator()->getArrayCopy());

        if (!$form->isValid()) {
            return $form;
        }

        $user->setEnabled(false);
        $user->setUsername($user->getEmail());
        $mailerDispatcher->sendEmailConfirmation($user);
        $userManager->updateUser($user);

        return $this->view(null, 200);
    }

    /**
     * @Rest\View()
     * @Rest\Post("/logout")
     * @Rest\RequestParam(name="refresh_token", nullable=false, description="Refresh token to dump")
     */
    public function logout(ParamFetcherInterface $fetcher): View
    {
        $manager = $this->getRefreshTokenManager();
        $token = $manager->get($fetcher->get('refresh_token'));

        if (null !== $token) {
            $manager->delete($token);
        } else {
            return $this->view(null, 400);
        }

        return $this->view(null, 200);
    }

    /**
     * @Rest\View()
     * @Rest\Post("/register/confirm")
     * @Rest\RequestParam(name="token", nullable=false, description="Registration confirmation token")
     * @return View
     */
    public function confirmEmail(ParamFetcherInterface $fetcher, UserManagerInterface $userManager): View
    {
        $token = $fetcher->get('token');
        /** @var User|null $user */
        $user = $userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            return $this->view(null, 400);
        }

        $user->setEnabled(true);
        $user->setConfirmationToken(null);

        if (null !== $user->getTempEmail()) {
            $user->setEmail($user->getTempEmail());
            $user->setTempEmail(null);
        }

        $userManager->updateUser($user);

        return $this->view(null, 200);
    }

    /**
     * @Rest\View()
     * @Rest\Post("/resetting")
     * @Rest\RequestParam(name="email", nullable=false, description="User email")
     */
    public function resetting(
        ParamFetcherInterface $fetcher,
        UserManagerInterface $userManager,
        MailerDispatcherInterface $mailerDispatcher
    ): View {
        $email = $fetcher->get('email');
        $user = $userManager->findUserByEmail($email);

        if (null !== $user) {
            $mailerDispatcher->sendResettingEmail($user);
        }

        return $this->view(null, 200);
    }

    /**
     * @Rest\View()
     * @Rest\Patch("/resetting/confirm")
     * @Rest\RequestParam(name="data", nullable=false, description="form-data")
     * @Rest\RequestParam(name="token", nullable=false, description="token")
     * @return View|FormInterface
     */
    public function confirmPassword(ParamFetcherInterface $fetcher, UserManagerInterface $userManager)
    {
        $data = $fetcher->get('data');
        $token = $fetcher->get('token');

        $user = $userManager->findUserByConfirmationToken($token);
        $limit = 2 * 3600;

        if (null === $user || !$user->isPasswordRequestNonExpired($limit)) {
            return $this->view(null, 400);
        }

        $form = $this->createForm(ResettingFormType::class, $user);
        $form->submit($data);

        if (!$form->isValid()) {
            return $form;
        }

        $userManager->updatePassword($user);
        $user->setConfirmationToken(null);
        $userManager->updateUser($user);

        return $this->view(null, 200);
    }

    private function getRefreshTokenManager(): RefreshTokenManagerInterface
    {
        return $this->get('gesdinet.jwtrefreshtoken.refresh_token_manager');
    }
}
