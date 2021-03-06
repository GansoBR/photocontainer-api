<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Domain;

class Photographer
{
    private $id;
    private $profile_id;
    private $name;

    const APPROVED_PROFILE = 2;

    public function __construct(?int $id, int $profile_id = null, string $name = null)
    {
        $this->changeId($id);

        if ($profile_id !== null) {
            $this->changeProfileId($profile_id);
        }
        $this->changeName($name);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function changeId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getProfileId()
    {
        return $this->profile_id;
    }

    /**
     * @param mixed $profile_id
     */
    public function changeProfileId($profile_id)
    {
        if (self::APPROVED_PROFILE !== $profile_id) {
            throw new \DomainException("Apenas o perfil de fotógrafo possui permissao para executar essa operação.");
        }

        $this->profile_id = $profile_id;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     */
    public function changeName(?string $name = null)
    {
        $this->name = $name;
    }
}
