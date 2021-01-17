<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\Request;

class CoreService
{
    public function parseJsonRequest(Request $request) {
        return json_decode($request->getContent(), true);
    }

}