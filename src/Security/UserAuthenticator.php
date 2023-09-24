<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

class UserAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;
    

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator, private UserRepository $userRepository, private TranslatorInterface $translator)
    {
        $this->userRepository = $userRepository;
        $this->translator = $translator;
    }

    public function onAuthenticationFailure(
        Request $request, 
        AuthenticationException $exception,
        
    ): Response {
        
        $email = $request->request->get('email', '');
        $user = $this->userRepository->findOneByEmail($email);
        
        $error = null;
        // dd($user);
        
        if ($user == null) {
            $error = $this->translator->trans("Unfortunately, this account has been banned. Please contact a moderator for more information.");
            return new RedirectResponse($this->urlGenerator->generate('app_login', ['error' => $error]));
        }

        if (!$user->isBanned()) {
            $failedAuthCount = $user->getFailedAuthCount();
            // dd($failedAuthCount, $user);
            if ($failedAuthCount < 4) {
                $user->setFailedAuthCount($failedAuthCount + 1);
    
                if ($failedAuthCount + 1 === 4) {
                    $user->setBanned(true);
                }
            }

            $this->userRepository->save($user, true);
            $remainingAttempts = 4 - $user->getFailedAuthCount();
            
            if ($remainingAttempts > 0) {
                if ($remainingAttempts === 1) {
                    $error = $this->translator->trans("Be vigilant, you have only one attempt remaining.");
                } else {
                    $error = $this->translator->trans("Be vigilant, you have only %count% remaining attempts.", ['%count%' => $remainingAttempts]);
                }
                return new RedirectResponse($this->urlGenerator->generate('app_login', ['error' => $error]));
            }
        }
        
        if ($user->isBanned()) {
            $error = $this->translator->trans("Unfortunately, this account has been banned. Please contact a moderator for more information.");
            return new RedirectResponse($this->urlGenerator->generate('app_login', ['error' => $error]));
        }

        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        // Récupérer l'utilisateur
        $user = $this->userRepository->findOneByEmail($email);

        // Vérifier si l'utilisateur est banni
        if ($user && $user->isBanned()) {
            throw new AuthenticationException("Votre compte a été banni.");
        }
        
        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        $email = $request->request->get('email', '');

        // Récupérer l'utilisateur
        $user = $this->userRepository->findOneByEmail($email);

        if ($user != null) {
            $user->setFailedAuthCount(0);
            $this->userRepository->save($user, true);
        }

        // For example:
        return new RedirectResponse($this->urlGenerator->generate('public_home'));
        // throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
