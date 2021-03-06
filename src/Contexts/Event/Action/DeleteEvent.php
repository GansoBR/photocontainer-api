<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Action;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Response\EventRemovedResponse;
use PhotoContainer\PhotoContainer\Contexts\User\Response\DomainExceptionResponse;

class DeleteEvent
{
    protected $repository;

    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(int $id)
    {
        try {
            $removed = $this->repository->delete($id);
            return new EventRemovedResponse($id);
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }
}
