<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Action;

use PhotoContainer\PhotoContainer\Contexts\Search\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Search\Response\EventResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class FindEventPhotosPhotographer
{
    protected $repository;

    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(int $id)
    {
        try {
            $result = $this->repository->findEventPhotosPhotographer($id);

            return new EventResponse($result, 'gallery_photos_photographer');
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }
}