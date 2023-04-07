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
use App\ApiPlatform\ProductSearchFilter;
use App\Dto\ProductOutput;
use App\Repository\ProductRepository;
use App\Validator as AppValidator;
use Carbon\Carbon;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['product:read', 'product:item:get']], security: 'is_granted("ROLE_USER")'),
        new Put(security: 'is_granted("PRODUCT_EDIT", previous_object)', securityMessage: 'You do not have permission to edit this product.'),
        new Patch(security: 'is_granted("ROLE_USER")'),
        new Delete(security: 'is_granted("ROLE_ADMIN")'),
        new GetCollection(),
        new Post(security: 'is_granted("ROLE_USER")'),
    ],
    formats: ['jsonld', 'json', 'html', 'jsonhal', 'csv' => ['text/csv']],
    normalizationContext: ['groups' => ['product:read'], 'swagger_definition_name' => 'Read'],
    denormalizationContext: ['groups' => ['product:write'], 'swagger_definition_name' => 'Write'],
    output: ProductOutput::class,
    paginationItemsPerPage: 10
)]
#[ApiFilter(BooleanFilter::class, properties: ['isActive'])]
#[ApiFilter(SearchFilter::class, properties: [
    'name' => 'partial',
    'description' => 'partial',
    'manufacturer' => 'exact',
    'manufacturer.username' => 'partial',
])]
#[ApiFilter(RangeFilter::class, properties: ['price'])]
#[ApiFilter(PropertyFilter::class)]
#[ApiFilter(ProductSearchFilter::class, arguments: ['useLike'=> true])]
#[ORM\EntityListeners(['App\Doctrine\ProductSetManufacturerListener'])]
#[AppValidator\ValidIsActive()]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[Assert\NotBlank]
    #[Assert\Length(
        [
            'min' => 2,
            'max' => 50,
            'maxMessage'=>"Describe your name in 50 chars or less"
        ]
    )]
    #[Groups(['product:read','product:write', 'user:read', 'user:write'])]
    #[ORM\Column(length: 255)]
    private string $name;

    #[Assert\NotBlank]
    #[Groups(['product:read', 'product:write', 'user:read', 'user:write'])]
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0')]
    private float $price = 0;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created;

    #[Groups(['product:read', 'user:write'])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[Groups('product:write')]
    #[ORM\Column]
    private bool $isActive = false;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'products')]
    #[ORM\JoinColumn(name: 'manufacturer_id', referencedColumnName: 'id', nullable: false)]
    #[Groups(['product:read', 'product:collection:post'])]
    #[AppValidator\isValidManufacturer()]
    private ?User $manufacturer = null;

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

    public function setDescription(?string $description): Product
    {
        $this->description = $description;

        return $this;
    }

    /** Description of product as raw text. */
    #[Groups(['product:write', 'user:write'])]
    #[SerializedName('description')]
    public function setTextDescription(?string $description): self
    {
        $this->description = nl2br($description);

        return $this;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getManufacturer(): ?User
    {
        return $this->manufacturer;
    }

    public function setManufacturer(?User $manufacturer): self
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }
}
