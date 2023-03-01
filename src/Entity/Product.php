<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Serializer\Filter\PropertyFilter;
use App\Repository\ProductRepository;
use Carbon\Carbon;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new Put(),
        new Patch(),
        new Delete(),
        new GetCollection(),
        new Post(),
    ],
    formats: ['jsonld', 'json', 'html', 'jsonhal', 'csv' => ['text/csv']],
    normalizationContext: ['groups' => ['product:read'], 'swagger_definition_name' => 'Read'],
    denormalizationContext: ['groups' => ['product:write'], 'swagger_definition_name' => 'Write'],
    paginationItemsPerPage: 10
)]
#[ApiFilter(BooleanFilter::class, properties: ['isActive'])]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'partial', 'description' => 'partial'])]
#[ApiFilter(RangeFilter::class, properties: ['price'])]
#[ApiFilter(PropertyFilter::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[Groups(['product:read','product:write'])]
    #[ORM\Column(length: 255)]
    private string $name;

    #[Groups(['product:read', 'product:write'])]
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0')]
    private float $price = 0;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created;

    #[Groups(['product:read'])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private bool $isActive = true;

    public function __construct()
    {
        $this->created = new \DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    #[Groups(['product:read'])]
    public function getCreatedAgo(): string
    {
        return Carbon::instance($this->getCreated())->diffForHumans();
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    #[Groups('product:read')]
    public function getShortDescription(): ?string
    {
        if (strlen($this->description) < 40) {
            return $this->description;
        }

        return substr($this->description, 0, 40) . '...';
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /** Description of product as raw text. */
    #[Groups(['product:write'])]
    #[SerializedName('description')]
    public function setTextDescription(?string $description): self
    {
        $this->description = nl2br($description);

        return $this;
    }

    public function isIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
}
