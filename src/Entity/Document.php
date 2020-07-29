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
     * @Assert\NotBlank(message="Veuillez entrer un nom de fichier", groups={"default"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $document;

    /**
     * @Vich\UploadableField(mapping="document_file", fileNameProperty="document")
     * @var ?File
     * @Assert\File(
     *     mimeTypes={"application/pdf","application/msword","application/vnd.oasis.opendocument.text","text/plain",
     *     "application/vnd.openxmlformats-officedocument.wordprocessingml.document"},
     *     mimeTypesMessage="Veuillez insérer un fichier au format {{ types }}", groups={"default"}
     * )
     * @Assert\NotBlank(message="Veuillez joindre un fichier", groups={"add"})
     */
    private $documentFile;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Conversation::class, mappedBy="document", orphanRemoval=true)
     */
    private $conversations;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ext;

    public function __construct()
    {
        $this->conversations = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
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

    public function setDocumentFile(?File $image)
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
     * @return Collection|Conversation[]
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(Conversation $conversation): self
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations[] = $conversation;
            $conversation->setDocument($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversations->contains($conversation)) {
            $this->conversations->removeElement($conversation);
            // set the owning side to null (unless already changed)
            if ($conversation->getDocument() === $this) {
                $conversation->setDocument(null);
            }
        }

        return $this;
    }

    public function getExt(): ?string
    {
        return $this->ext;
    }

    public function setExt(string $ext): self
    {
        $this->ext = $ext;

        return $this;
    }
}
