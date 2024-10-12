<?php

declare(strict_types=1);

namespace Context\Shared\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SharedController extends AbstractController
{
    public function __invoke(): Response
    {
        return new Response('Hello! I`m shared context!');
    }
}
