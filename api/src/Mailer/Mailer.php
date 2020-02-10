<?php declare(strict_types = 1);

namespace App\Mailer;

use App\Utils\ClientHostInterface;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class Mailer implements MailerInterface
{
    /** @var string */
    private $mailUser;

    /** @var \Swift_Mailer */
    private $mailer;

    /** @var EngineInterface */
    private $templating;

    /** @var ClientHostInterface */
    private $host;

    public function __construct(string $mailUser, \Swift_Mailer $mailer, EngineInterface $engine, ClientHostInterface $host)
    {
        $this->mailUser = $mailUser;
        $this->mailer = $mailer;
        $this->templating = $engine;
        $this->host = $host;
    }

    /** {@inheritdoc} */
    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        $url = $this->host->getHost().'/login/confirm/'.$user->getConfirmationToken();
        $rendered = $this->templating->render('mails/confirmation.txt.twig', [
            'user' => $user,
            'confirmationUrl' => $url,
        ]);
        $this->sendEmailMessage($rendered, $this->mailUser, $user->getEmail());
    }

    /** {@inheritdoc} */
    public function sendResettingEmailMessage(UserInterface $user)
    {
        $url = $this->host->getHost().'/resetting/confirm/'.$user->getConfirmationToken();
        $rendered = $this->templating->render('mails/resseting.txt.twig', [
            'user' => $user,
            'confirmationUrl' => $url,
        ]);
        $this->sendEmailMessage($rendered, $this->mailUser, $user->getEmail());
    }

    /**
     * @param string $renderedTemplate
     * @param array|string $fromEmail
     * @param array|string $toEmail
     */
    protected function sendEmailMessage(string $renderedTemplate, $fromEmail, $toEmail): void
    {
        // Render the email, use the first line as the subject, and the rest as the body
        $renderedLines = explode("\n", trim($renderedTemplate));
        $subject = array_shift($renderedLines) ?? "";
        $body = implode("\n", $renderedLines);

        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail)
            ->setBody($body);

        $this->mailer->send($message);
    }
}
