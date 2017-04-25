<?php

namespace PhotoContainer\PhotoContainer\Contexts\Contact;

use PhotoContainer\PhotoContainer\Infrastructure\ContextBootstrap;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\Contact;
use PhotoContainer\PhotoContainer\Infrastructure\Web\WebApp;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ContactContextBootstrap implements ContextBootstrap
{
    public function wireSlimRoutes(WebApp $slimApp): WebApp
    {
        $container = $slimApp->app->getContainer();

        $slimApp->app->post('/contact', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
            try {
                $data = $request->getParsedBody();

                if (empty($data)) {
                    throw new \Exception("Dados não enviados");
                }

                if (!isset($data['name'])) {
                    throw new \Exception("Dados não enviados: nome.");
                }

                if (!isset($data['email'])) {
                    throw new \Exception("Dados não enviados: email");
                }

                if (!isset($data['phone'])) {
                    throw new \Exception("Dados não enviados: telefone.");
                }

                if (!isset($data['profile'])) {
                    throw new \Exception("Dados não enviados: perfil.");
                }

                if ($data['profile'] == "publisher" && !isset($data['blog'])) {
                    throw new \Exception("Dados não enviados: blog.");
                }

                $contact = new Contact();
                $contact->name = $data['name'] ?? "";
                $contact->email = $data['email'] ?? "";
                $contact->phone = $data['phone'] ?? "";
                $contact->profile = $data['profile'] ?? "";
                $contact->blog = $data['blog'] ?? "";
                $contact->save();

                return $response->withJson(['msg' => 'Salvo com sucesso'], 200);
            } catch (\Exception $e) {
                return $response->withJson(['message' => $e->getMessage()], 500);
            }
        });

        $slimApp->app->get('/contact/total', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
            try {
                $total =Contact::all()->count();
                if ($total > 200) {
                    throw new \Exception('As vagas já foram preenchidas.');
                }

                return $response->withJson(['msg' => 'Possui vaga', 'total' => $total], 200);
            } catch (\Exception $e) {
                return $response->withJson(['message' => $e->getMessage()], 500);
            }
        });

        return $slimApp;
    }
}