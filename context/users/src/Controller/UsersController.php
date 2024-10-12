<?php

declare(strict_types=1);

namespace Context\Users\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends AbstractController
{
    public function __invoke(): Response
    {
        return new Response('Hello! I`m users context!');
    }
}
