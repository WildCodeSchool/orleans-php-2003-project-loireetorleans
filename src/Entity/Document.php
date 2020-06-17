<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=DocumentRepository::class)
 * @Vich\Uploadable
 */
class Document
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez entrer un nom de fichier")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $document;
    /**
     * @Vich\UploadableField(mapping="document_file", fileNameProperty="document")
     * @var File
     * @Assert\File(
     *     mimeTypes={"application/pdf","application/msword","application/vnd.oasis.opendocument.text","text/plain"},
     *     mimeTypesMessage="Veuillez insérer un fichier au format {{ types }}"
     * )
     * @Assert\NotBlank(message="Veuillez insérer un fichier")
     */
    private $documentFile;
    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity=Message::class, mappedBy="document")
     */
    private $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
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

    public function getDocument(): ?string
    {
        return $this->document;
    }

    public function setDocument(?string $document): self
    {
        $this->document = $document;

        return $this;
    }

    public function setDocumentFile(File $image)
    {
        $this->documentFile = $image;
        $this->updatedAt = new DateTime('now');
    }

    public function getDocumentFile(): ?File
    {
        return $this->documentFile;
    }
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }
    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->addDocument($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            $message->removeDocument($this);
        }

        return $this;
    }
}
