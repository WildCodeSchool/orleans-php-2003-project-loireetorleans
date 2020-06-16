<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository", repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"login"}, message="There is already an account with this login")
 * @Vich\Uploadable
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(
     *     message="L'identifiant est obligtoire"
     * )
     * @Assert\Length(
     *     max = 50,
     *     maxMessage = "Le nom de l'identifiant ne doit pas faire plus de {{ limit }} caractères",
     * )
     */
    private $login;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(
     *     message="Le mot de passe est obligatoire"
     * )
     * @Assert\Length(
     *     max = 20,
     *     maxMessage = "Le mot de passe ne doit pas faire plus de {{ limit }} caractères",
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(
     *     message="Le nom de famille est obligatoire"
     * )
     * @Assert\Length(
     *     max = 100,
     *     maxMessage = " Le nom de famille ne doit pas faire plus de {{ limit }} caractères",
     * )
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(
     *     message="Le prénom est obligatoire"
     * )
     * @Assert\Length(
     *     max = 100,
     *     maxMessage =" Le prénom ne doit pas faire plus de {{ limit }} caractères",
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message="L'adresse email est obligatoire"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message="La description est requise"
     * )
     * @Assert\Length(
     *     max = 255,
     *     maxMessage = "La description ne doit pas faire plus de {{ limit }} caractères",
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\NotBlank(
     *     message="Un numéro de téléphone est obligatoire"
     * )
     * @Assert\Length(
     *     max = 15,
     *     maxMessage = "Le numéro de téléphone ne doit pas faire plus de {{ limit }} caractères",
     * )
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(
     *     message="Le nom de l'entreprise est obligatoire"
     * )
     * @Assert\Length(
     *     max = 100,
     *     maxMessage = "Le nom de l'entreprise ne doit pas faire plus de {{ limit }} caractères",
     * )
     */
    private $company;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message="Le choix d'un champs est obligatoire"
     * )
     */
    private $activity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $picture;

    /**
     * @Vich\UploadableField(
     *     mapping = "picture_file",
     *     fileNameProperty = "picture",
     * )
     * @var File
     * @Assert\NotBlank(
     *     message = "Une photo est obligatoire"
     * )
     * @Assert\File(
     *     mimeTypes = {"image/jpg", "image/jpeg", "image/png"},
     *     mimeTypesMessage = "Veuillez insérer un fichier au format {{ types }} "
     * )
     */
    private $pictureFile;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(
     *     message="Le nom de la ville est obligatoire"
     * )
     * @Assert\Length(
     *     max = 100,
     *     maxMessage = "Le nom de la ville ne doit pas faire plus de {{ limit }} caractères",
     * )
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message="Le nom de la rue est obligatoire"
     * )
     * @Assert\Length(
     *     max = 255,
     *     maxMessage = "Le nom de la rue ne doit pas faire plus de {{ limit }} caractères",
     * )
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(
     *     message="Le code postal est obligatoire"
     * )
     * @Assert\Length(
     *     max = 20,
     *     maxMessage = "Le code postal ne doit pas faire plus de {{ limit }} caractères",
     * )
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message="Le choix d'un bassin d'emploi est obligatoire"
     * )
     */
    private $employmentArea;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->login;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getActivity(): ?string
    {
        return $this->activity;
    }

    public function setActivity(string $activity): self
    {
        $this->activity = $activity;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getEmploymentArea(): ?string
    {
        return $this->employmentArea;
    }

    public function setEmploymentArea(string $employmentArea): self
    {
        $this->employmentArea = $employmentArea;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return File
     */
    public function getPictureFile(): ?File
    {
        return $this->pictureFile;
    }

    /**
     * @param File $pictureFile
     * @return User
     */
    public function setPictureFile(File $pictureFile): User
    {
        $this->pictureFile = $pictureFile;
        if (!empty($this)) {
            $this->updatedAt = new DateTime('now');
        }
        return $this;
    }
}
