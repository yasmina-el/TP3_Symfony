<?php

namespace App\Entity;

use App\Repository\ColorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ColorRepository::class)]
class Color
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('color:read')]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Groups('pen:read')]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Pen::class, mappedBy: 'colors')]
    private Collection $pens;

    public function __construct()
    {
        $this->pens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Pen>
     */
    public function getPens(): Collection
    {
        return $this->pens;
    }

    public function addPen(Pen $pen): static
    {
        if (!$this->pens->contains($pen)) {
            $this->pens->add($pen);
            $pen->addColor($this);
        }

        return $this;
    }

    public function removePen(Pen $pen): static
    {
        if ($this->pens->removeElement($pen)) {
            $pen->removeColor($this);
        }

        return $this;
    }
}
