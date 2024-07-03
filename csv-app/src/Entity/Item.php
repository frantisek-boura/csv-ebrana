<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use App\Validator\DUN14;
use App\Validator\EAN13;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
#[UniqueEntity(['code', 'ean13', 'dun14'])]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // code, ean13 a dun14 mohly tvorit composite primary key
    // ale tohle postaci
    #[ORM\Column(name: 'code', unique: true, nullable: false)]
    #[Assert\NotBlank]
    private ?string $code = null;

    #[ORM\Column(name: 'ean13', unique: true, nullable: false)]
    #[EAN13]
    private ?string $ean13 = null;

    #[ORM\Column(name: 'dun14', unique: true, nullable: false)]
    #[DUN14]
    private ?string $dun14 = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive]
    private ?int $carton_qty = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive]
    private ?float $price = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Colour $colour = null;

    #[ORM\Column(nullable: true)]
    private ?int $size = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(allowNull: false)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive]
    private ?float $weight = null;

    #[ORM\Column(length: 512, nullable: true)]
    private ?string $image_path = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getEan13(): ?string
    {
        return $this->ean13;
    }

    public function setEan13(string $ean13): static
    {
        $this->ean13 = $ean13;

        return $this;
    }

    public function getDun14(): ?string
    {
        return $this->dun14;
    }

    public function setDun14(string $dun14): static
    {
        $this->dun14 = $dun14;

        return $this;
    }

    public function getCartonQty(): ?int
    {
        return $this->carton_qty;
    }

    public function setCartonQty(?int $carton_qty): static
    {
        $this->carton_qty = $carton_qty;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getColour(): ?Colour
    {
        return $this->colour;
    }

    public function setColour(?Colour $colour): static
    {
        $this->colour = $colour;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->image_path;
    }

    public function setImagePath(?string $image_path): static
    {
        $this->image_path = $image_path;

        return $this;
    }
}
