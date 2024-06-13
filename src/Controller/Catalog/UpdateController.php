<?php

namespace App\Controller\Catalog;

use App\Messenger\MessageBusAwareInterface;
use App\Messenger\MessageBusTrait;
use App\Messenger\UpdateProductInCatalog;
use App\ResponseBuilder\ErrorBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/products/{id}", methods={"PUT"}, name="product-update")
 */
class UpdateController extends AbstractController implements MessageBusAwareInterface
{
    use MessageBusTrait;

    public function __construct(private ErrorBuilder $errorBuilder) { }

    public function __invoke(Request $request, string $id): Response
    {
        $name = trim($request->get('name'));
        $price = (int)$request->get('price');

        if ($name === '' || $price < 1) {
            return new JsonResponse(
                $this->errorBuilder->__invoke('Invalid name or price.'),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        $this->dispatch(new UpdateProductInCatalog($id, $name, $price));

        return new Response('', Response::HTTP_ACCEPTED);
    }
}
