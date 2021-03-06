<?php

namespace PhotoContainer\PhotoContainer\Contexts\Approval\Email;

use PhotoContainer\PhotoContainer\Infrastructure\Email\Email;

class ApprovedEmail extends Email
{
    public function __construct(array $data, array $to, array $from)
    {
        $file = __DIR__."/templates/approved.html";
        $subject = "Acesso Aprovado.";

        parent::__construct($data, $file, $subject, $to, $from);
    }
}
