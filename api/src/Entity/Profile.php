<?php declare(strict_types = 1);

namespace App\Entity;

use App\Validator\Constraints as AppAssert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProfileRepository")
 * @ORM\Table(name="profile")
 */
class Profile
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank()
     * @Assert\Regex("/^[a-zA-Z0-9 ]+$/")
     * @Groups({"API"})
     * @var string|null
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank()
     * @Assert\Regex("/^[a-zA-Z0-9 ]+$/")
     * @Groups({"API"})
     * @var string|null
     */
    protected $lastName;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Url()
     * @AppAssert\IsFacebookUrl()
     * @Groups({"API"})
     * @var string|null
     */
    protected $facebookUrl;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"API"})
     * @var string|null
     */
    protected $description;

    /**
     * @ORM\OneToOne(targetEntity="User", orphanRemoval=true)
     * @var User
     */
    protected $user;

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getFacebookUrl(): ?string
    {
        return $this->facebookUrl;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function setFacebookUrl(?string $facebookUrl): self
    {
        $this->facebookUrl = $facebookUrl;

        return $this;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }
}
