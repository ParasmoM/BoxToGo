<?php 
namespace App\Security;

use App\Entity\User;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class GoogleAuthenticator extends OAuth2Authenticator implements AuthenticationEntryPointInterface
{
    private $clientRegistry;
    private $entityManager;
    private $router;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $entityManager, RouterInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    public function supports(Request $request): ?bool
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->attributes->get('_route') === 'connect_google_check';
    }

    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient('google_main');
        $accessToken = $this->fetchAccessToken($client);
        $googleUser = $client->fetchUserFromToken($accessToken);
    
        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['googleId' => $googleUser->getId()]);
        
        if (!$existingUser) {
            $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $googleUser->getEmail()]);
            
            if (!$existingUser) {
                // Si l'utilisateur n'existe pas, créez-le dans la base de données
                $existingUser = new User();
                $existingUser->setGoogleId($googleUser->getId())
                            ->setGivenName($googleUser->toArray()['given_name'])
                            ->setFamilyName($googleUser->toArray()['family_name'])
                            ->setEmail($googleUser->getEmail())
                            ->setPassword('oauth')
                            // ->setIsVerified($googleUser->toArray()['email_verified'])
                            ->getLanguage($googleUser->toArray()['locale']);
        
                $this->entityManager->persist($existingUser);
                $this->entityManager->flush();
            }
        }
    
        // Utilisez l'ID de l'utilisateur (de votre base de données) comme identifiant pour le UserBadge
        return new SelfValidatingPassport(
            new UserBadge($existingUser->getId(), function() use ($existingUser) {
                return $existingUser;
            })
        );
    }    

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // change "app_homepage" to some route in your app
        $targetUrl = $this->router->generate('public_home');

        return new RedirectResponse($targetUrl);
    
        // or, on success, let the request continue to be handled by the controller
        //return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }
    
    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     */
    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new RedirectResponse(
            '/connect/', // might be the site, where users choose their oauth provider
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }
}