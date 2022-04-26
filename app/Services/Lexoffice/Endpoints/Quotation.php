<?php

namespace App\Services\Lexoffice\Endpoints;

use App\Models\Offer;
use App\Services\Lexoffice\Lexoffice;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class Quotation extends Lexoffice
{
    public const ENDPOINT = 'quotations';

    protected ?string $contactId = null;

    protected Carbon $voucherDate;

    protected Carbon $expirationDate;

    protected Collection $lineItems;

    protected array $totalPrice;

    protected array $taxConditions;

    public function createQuotation(Offer $offer) {
        $this->createLineItems($offer);

        $quotationData = [
            'voucherDate' => self::buildLexofficeDate(now()),
            'expirationDate' => self::buildLexofficeDate(now()->addMonth()),
            'address' => [
                'contactId' => $offer->contact_id
            ],
            'lineItems' => $this->lineItems->toArray(),
            'totalPrice' => [
                'currency' => 'EUR'
            ],
            'taxConditions' => [
                'taxType' => 'net'
            ]
        ];
        return $this->store($quotationData);
    }

    public function createLineItems(Offer $offer) {
        $this->lineItems = new Collection();

        $offer->vendorProducts->each(function($vendorProduct) {
            $this->lineItems->add([
                'type' => 'custom',
                'name' => $vendorProduct->product->name,
                'description' => $vendorProduct->product->description,
                'quantity' => $vendorProduct->getOriginal()['pivot_quantity'],
                'unitName' => 'Stück',
                'unitPrice' => [
                    'currency' => 'EUR',
                    'netAmount' => $vendorProduct->price,
                    'taxRatePercentage' => 19
                ]
            ]);
        });
    }

    /**
     * @return string|null
     */
    public function getContactId(): ?string {
        return $this->contactId;
    }

    /**
     * @param  string|null  $contactId
     */
    public function setContactId(?string $contactId): void {
        $this->contactId = $contactId;
    }

    /**
     * @return Carbon
     */
    public function getVoucherDate(): Carbon {
        return $this->voucherDate;
    }

    /**
     * @param  Carbon  $voucherDate
     */
    public function setVoucherDate(Carbon $voucherDate): void {
        $this->voucherDate = $voucherDate;
    }

    /**
     * @return Carbon
     */
    public function getExpirationDate(): Carbon {
        return $this->expirationDate;
    }

    /**
     * @param  Carbon  $expirationDate
     */
    public function setExpirationDate(Carbon $expirationDate): void {
        $this->expirationDate = $expirationDate;
    }

    /**
     * @return Collection
     */
    public function getLineItems(): Collection {
        return $this->lineItems;
    }

    /**
     * @param  Collection  $lineItems
     */
    public function setLineItems(Collection $lineItems): void {
        $this->lineItems = $lineItems;
    }

    /**
     * @return array
     */
    public function getTotalPrice(): array {
        return $this->totalPrice;
    }

    /**
     * @param  array  $totalPrice
     */
    public function setTotalPrice(array $totalPrice): void {
        $this->totalPrice = $totalPrice;
    }

    /**
     * @return array
     */
    public function getTaxConditions(): array {
        return $this->taxConditions;
    }

    /**
     * @param  array  $taxConditions
     */
    public function setTaxConditions(array $taxConditions): void {
        $this->taxConditions = $taxConditions;
    }
}