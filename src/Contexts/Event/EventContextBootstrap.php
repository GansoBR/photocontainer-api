<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event;

use PhotoContainer\PhotoContainer\Contexts\Event\Action\CreateEvent;
use PhotoContainer\PhotoContainer\Contexts\Event\Action\FindCategories;
use PhotoContainer\PhotoContainer\Contexts\Event\Action\FindTags;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Event;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventCategory;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Photographer;
use PhotoContainer\PhotoContainer\Contexts\Event\Persistence\EloquentCategoryRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Persistence\EloquentEventRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Persistence\EloquentTagRepository;
use PhotoContainer\PhotoContainer\Infrastructure\ContextBootstrap;
use PhotoContainer\PhotoContainer\Infrastructure\Web\WebApp;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class EventContextBootstrap implements ContextBootstrap
{
    public function wireSlimRoutes(WebApp $slimApp): WebApp
    {
        $container = $slimApp->app->getContainer();

        $slimApp->app->post('/events', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
            try {
                $data = $request->getParsedBody();

                $user = new Photographer($data['user_id']);

                $allCategories = [];
                foreach ($data['categories'] as $category) {
                    $allCategories[] = new EventCategory(null, $category);

                }

                $event = new Event(null, $user, $data['bride'], $data['groom'], $data['eventDate'], $data['title'],
                    $data['description'], (bool) $data['terms'], (bool) $data['approval_general'],
                    (bool) $data['approval_photographer'], (bool) $data['approval_bride'], $allCategories);

                $action = new CreateEvent(new EloquentEventRepository());
                $actionResponse = $action->handle($event);

                return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
            } catch (\Exception $e) {
                return $response->withJson(['message' => $e->getMessage()], 500);
            }
        });

        $slimApp->app->get('/events/categories', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
            try {
                $action = new FindCategories(new EloquentCategoryRepository());
                $actionResponse = $action->handle();

                return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
            } catch (\Exception $e) {
                return $response->withJson(['message' => $e->getMessage()], 500);
            }
        });

        $slimApp->app->get('/events/tags', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
            try {
                $action = new FindTags(new EloquentTagRepository());
                $actionResponse = $action->handle();

                return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
            } catch (\Exception $e) {
                return $response->withJson(['message' => $e->getMessage()], 500);
            }
        });

        return $slimApp;
    }
}