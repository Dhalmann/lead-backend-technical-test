<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\Controller\TagsController;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
#[ApiResource(
    itemOperations: [
        'get',
        'put',
        'patch',
        'delete',
        'addTags' =>[
            'method' => 'POST',
            'path' => '/order/{id}/addTags',
            'controller' => TagsController::class,
            'openapi_context' => [
                'summary' => 'generate tags for an order',
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type'       => 'object'
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
)]
class Order
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private string $contactEmail;

    #[ORM\Column(type: 'string', length: 255)]
    private string $shippingAddress;

    #[ORM\Column(type: 'string', length: 255)]
    private string $shippingZipcode;

    #[ORM\Column(type: 'string', length: 255)]
    private string $shippingCountry;

    #[ORM\Column(type: 'integer', nullable: true)]
    private int $total;

    #[ORM\Column(type: 'string', length: 255, options:['default'=>''])]
    private string $tags='';

    #[ORM\OneToMany(targetEntity: 'OrderLine', mappedBy: 'order', fetch: 'EAGER')]
    private $lines;

    public function __construct()
    {
        $this->lines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(?int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getContactEmail(): string
    {
        return $this->contactEmail;
    }

    public function setContactEmail(string $contactEmail): void
    {
        $this->contactEmail = $contactEmail;
    }

    public function getShippingAddress(): string
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(string $shippingAddress): void
    {
        $this->shippingAddress = $shippingAddress;
    }


    public function getShippingZipcode(): string
    {
        return $this->shippingZipcode;
    }

    public function setShippingZipcode(string $shippingZipcode): void
    {
        $this->shippingZipcode = $shippingZipcode;
    }

    public function getShippingCountry(): string
    {
        return $this->shippingCountry;
    }

    public function setShippingCountry(string $shippingCountry): void
    {
        $this->shippingCountry = $shippingCountry;
    }

    public function getTags(): string
    {
        return $this->tags;
    }

    public function setTags(?string $tags): void
    {
        $this->tags = $tags;
    }

    public function getLines(): Collection
    {
        return $this->lines;
    }
}
