<?php

namespace App\Entity;

use App\Repository\SubscriptionEntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SubscriptionEntityRepository::class)
 */
class SubscriptionEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $endpoint;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $publickey;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $authtoken;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contentcoding;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEndpoint(): ?string
    {
        return $this->endpoint;
    }

    public function setEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function getPublickey(): ?string
    {
        return $this->publickey;
    }

    public function setPublickey(string $publickey): self
    {
        $this->publickey = $publickey;

        return $this;
    }

    public function getAuthtoken(): ?string
    {
        return $this->authtoken;
    }

    public function setAuthtoken(string $authtoken): self
    {
        $this->authtoken = $authtoken;

        return $this;
    }

    public function getContentcoding(): ?string
    {
        return $this->contentcoding;
    }

    public function setContentcoding(string $contentcoding): self
    {
        $this->contentcoding = $contentcoding;

        return $this;
    }
}
