<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response as OAResponse;

use function PHPSTORM_META\type;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]

    #[OA\Post(
        path: "/login",
        summary: "Effectue la connexion d'un utilisateur",
        tags: ["Security"],
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                type: 'object',
                properties: [
                    new Property(
                        property: "username",
                        type: "string",
                        example: "user@example.com",
                    ),
                    new property(
                        property: "password",
                        type: "string",
                        example: "password"
                    )
                ]
            )
        ),
        responses: [
            new OAresponse(
                response: 200,
                description: "Connexion réussie",
            ),
            new OAresponse(
                response: 401,
                description: "Identifiants invalides",
            )
        ]
    )]

    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    #[OA\Post(
        path: "/logout",
        summary: "Effectue la déconnexion d'un utilisateur",
        tags: ["Security"],
        responses: [
            new OAResponse(
                response: 200,
                description: "Déconnexion réussie",
            ),
            new OAResponse(
                response: 401,
                description: "Non authentifié",
            )
        ]
        
    )]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
