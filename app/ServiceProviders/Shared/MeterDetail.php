<?php

namespace App\ServiceProviders\Shared;

use App\Models\Property;

class MeterDetail
{
    private ?Property $property = null;
    private ?string $customer_name = null;
    private ?string $customer_phone = null;
    private ?string $customer_address = null;
    private ?float $price = null;
    private ?float $vat = null;
    private ?string $currency = null;
    private ?string $unit = null;
    private ?string $provider = null;

    public function setProperty(Property $property): void {
        $this->property = $property;
    }
    public function getProperty(): Property | null {
        return $this->property;
    }

    public function setCustomerName(string $name): void {
        $this->customer_name = $name;
    }
    public function getCustomerName(): string | null {
        return $this->customer_name;
    }

    public function setCustomerPhone(string $phone): void {
        $this->customer_phone = $phone;
    }
    public function getCustomerPhone(): string | null {
        return $this->customer_phone;
    }

    public function setCustomerAddress(string $address): void {
        $this->customer_address = $address;
    }
    public function getCustomerAddress(): string | null {
        return $this->customer_address;
    }

    public function setPrice(float $price): void {
        $this->price = $price;
    }
    public function getPrice(): float | null {
        return $this->price;
    }

    public function setVat(float $vat): void {
        $this->vat = $vat;
    }
    public function getVat(): float | null {
        return $this->vat;
    }

    public function setCurrency(string $currency): void {
        $this->currency = $currency;
    }
    public function getCurrency(): string | null {
        return $this->currency;
    }

    public function setUnit(string $unit): void {
        $this->unit = $unit;
    }
    public function getUnit(): string | null {
        return $this->unit;
    }

    public function setProvider(string $provider): void {
        $this->provider = $provider;
    }
    public function getProvider(): string | null {
        return $this->provider;
    }

    public function toArray(): array
    {
        return [
            'customerName' => $this->getCustomerName(),
            'customerPhone' => $this->getCustomerPhone(),
            'customerAddress' => $this->getCustomerAddress(),
            'price' => $this->getPrice(),
            'vat' => $this->getVat(),
            'currency' => $this->getCurrency(),
            'unit' => $this->getUnit(),
            'provider' => $this->getProvider()
        ];
    }
}
