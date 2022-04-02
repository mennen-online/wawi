<?php

namespace App\Services\Lexoffice\Endpoints;

use App\Services\Lexoffice\Lexoffice;
use Illuminate\Support\Arr;

class Contacts extends Lexoffice
{
    public const ENDPOINT = 'Contacts';

    protected ?int $maxPageSize = 500;

    public function __construct(
        protected ?string $email = null,
        protected ?string $name = null,
        protected ?int $number = null,
        protected ?bool $customer = null,
        protected ?bool $vendor = null
    ) {
        parent::__construct();
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string {
        return $this->email;
    }

    /**
     * @param  string|null  $email
     * @return Contacts
     */
    public function setEmail(?string $email): self {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string {
        return $this->name;
    }

    /**
     * @param  string|null  $name
     * @return Contacts
     */
    public function setName(?string $name): self {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNumber(): ?int {
        return $this->number;
    }

    /**
     * @param  int|null  $number
     * @return Contacts
     */
    public function setNumber(?int $number): self {
        $this->number = $number;

        return $this;
    }

    /**
     * @return Contacts
     */
    public function onlyVendor(): self {
        $this->vendor = true;

        $this->customer = false;

        return $this;
    }

    /**
     * @return Contacts
     */
    public function onlyCustomer(): self {
        $this->customer = true;

        $this->vendor = false;

        return $this;
    }
}