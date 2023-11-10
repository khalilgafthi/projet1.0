<?php

    namespace App\Entity;

    use App\Repository\BookRepository;
    use Cassandra\Date;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\DBAL\Types\DateType;
    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity(repositoryClass: BookRepository::class)]
    class Book
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;

        #[ORM\Column(length: 255)]
        private ?string $ref = null;

        #[ORM\Column(length: 255)]
        private ?string $title = null;

        #[ORM\Column(length: 255)]
        private ?string $category = null;

        #[ORM\Column(length: 255)]
        private ?\DateTime $publicationdate = null;

        #[ORM\Column]
        private ?bool $published = true;

        #[ORM\ManyToOne(inversedBy: 'books')]
        private ?Author $Author = null;

        #[ORM\ManyToMany(targetEntity: Reader::class, mappedBy: 'book')]
        private Collection $readers;

        public function __construct()
        {
            $this->readers = new ArrayCollection();
        }

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getRef(): ?string
        {
            return $this->ref;
        }

        public function setRef(string $ref): static
        {
            $this->ref = $ref;

            return $this;
        }

        public function getTitle(): ?string
        {
            return $this->title;
        }

        public function setTitle(string $title): static
        {
            $this->title = $title;

            return $this;
        }

        public function getCategory(): ?string
        {
            return $this->category;
        }

        public function setCategory(string $category): static
        {
            $this->category = $category;

            return $this;
        }

        public function getPublicationdate(): ?\DateTime
        {
            return $this->publicationdate;
        }

        public function setPublicationdate(?\DateTime $publicationdate): self
        {
            $this->publicationdate = $publicationdate;

            return $this;
        }
        public function isPublished(): ?bool
        {
            return $this->published;
        }

        public function setPublished(bool $published): static
        {
            $this->published = $published;

            return $this;
        }

        public function getAuthor(): ?Author
        {
            return $this->Author;
        }

        public function setAuthor(?Author $Author): static
        {
            $this->Author = $Author;

            return $this;
        }

        /**
         * @return Collection<int, Reader>
         */
        public function getReaders(): Collection
        {
            return $this->readers;
        }

        public function addReader(Reader $reader): static
        {
            if (!$this->readers->contains($reader)) {
                $this->readers->add($reader);
                $reader->addBook($this);
            }

            return $this;
        }

        public function removeReader(Reader $reader): static
        {
            if ($this->readers->removeElement($reader)) {
                $reader->removeBook($this);
            }

            return $this;
        }
    }
