<?php
namespace App\OpenApi;
use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model;
use ApiPlatform\Core\OpenApi\OpenApi;

class OpenApiFactory implements OpenApiFactoryInterface {
    public function __construct(private OpenApiFactoryInterface $decorated) {}

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);
        /** @var Model\PathItem $path */
        foreach($openApi->getPaths()->getPaths() as $key => $path){
            if($path->getGet() && $path->getGet()->getSummary() === 'hidden') {
                $openApi->getPaths()->addPath($key, $path->withGet(null));
            }
        }

        $securitySchemes = $openApi->getComponents()->getSecuritySchemes();

        // https://swagger.io/docs/specification/authentication/api-keys/
        $securitySchemes['bearerAuth'] = new \ArrayObject([
            'type' => 'http',
            'scheme' => 'bearer',
            'bearerFormat' => 'JWT'
        ]);

        // https://swagger.io/docs/specification/authentication/oauth2/
        /*$securitySchemes['oauth2'] = new \ArrayObject([
            'type' => 'oauth2',
            'description' => "This API uses OAuth 2 with the implicit grant flow. [More info](https://api.example.com/docs/auth)",
            'flows' => [
                'clientCredentials' => [
                    'tokenUrl' => '/token',
                    'scopes' => [
                        'read:user:permission'=> "L'utilisateur peut utiliser les requêtes GET n'exigeant pas la permission admin",
                        'read:admin:permission'=> "L'utilisateur peut utiliser l'ensemble des requêtes GET",
                        'write:admin:permission'=> "L'utilisateur peut utiliser l'ensemble des requêtes POST, PATCH, DELETE"
                    ]
                ]
            ]
        ]);*/

        $openApiSchemas = $openApi->getComponents()->getSchemas();

        /* Appliquer le schéma de sécurité globalement
        $openApi = $openApi->withSecurity([['schema' => []]]);
        */

        $openApi = $openApi->withSecurity([['bearerAuth' => []]]);

        $openApiSchemas['Token'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'token' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
            ],
        ]);
        $openApiSchemas['Credentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'username' => [
                    'type' => 'string',
                    'example' => 'compte@email.com',
                ],
                'password' => [
                    'type' => 'string',
                    'example' => 'mot de passe',
                ],
            ],
        ]);
        $pathItem = new Model\PathItem(
            ref: 'JWT Token',
            post: new Model\Operation(
                operationId: 'postCredentialsItem',
                tags: ['Token'],
                responses: [
                    '200' => [
                        'description' => 'Get JWT token',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Token',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Get JWT token to login.',
                requestBody: new Model\RequestBody(
                    description: 'Generate new JWT Token',
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/Credentials',
                            ],
                        ],
                    ]),
                ),
            ),
        );
        $openApi->getPaths()->addPath('/api/login', $pathItem);

        //dd($openApi);
        return $openApi;
    }
}
?>