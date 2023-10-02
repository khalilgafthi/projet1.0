<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class first
{
    #[Route('/', name : 'app_first')]
    public function homepage() : Response
    {
        return new Response('khalil m3akk aaa');
    }
    #[Route('/browse')]
    public function browse() : Response
    {
        return new Response('ahla khalil ');
    }
    
}
// v templates
// m entity