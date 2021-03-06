<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Action;

use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Notification;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\NotificationRepository;
use PhotoContainer\PhotoContainer\Contexts\Search\Response\NotificationResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class GetNotifications
{
    protected $repository;

    public function __construct(NotificationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(int $user_id)
    {
        try {
            $notification = new Notification();
            $notification->setApprovalWaitList($this->repository->approvalWaitList($user_id));

            return new NotificationResponse($notification);
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }
}
