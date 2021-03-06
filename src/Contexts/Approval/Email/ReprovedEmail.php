<?php

namespace PhotoContainer\PhotoContainer\Contexts\Approval\Email;

use PhotoContainer\PhotoContainer\Infrastructure\Email\Email;

class ReprovedEmail extends Email
{
    public function __construct(array $data, array $to, array $from)
    {
        $file = __DIR__."/templates/reproved.html";
        $subject = "Acesso negado.";

        parent::__construct($data, $file, $subject, $to, $from);
    }
}
