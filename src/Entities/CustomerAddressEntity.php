<?php

namespace WebImage\Store\Entities;

use DateTime;
use WebImage\Models\Services\ModelEntity;

class CustomerAddressEntity extends ModelEntity
{
	public function getId(): int
	{
		return $this->entity['id'];
	}

	public function setId(int $id)
	{
		$this->entity['id'] = $id;
	}

	public function getAttn(): ?string
	{
		return $this->entity['attn'];
	}

	public function setAttn(string $attn)
	{
		$this->entity['attn'] = $attn;
	}

	public function getStreet1(): ?string
	{
		return $this->entity['street1'];
	}

	public function setStreet1(string $street1)
	{
		$this->entity['street1'] = $street1;
	}

	public function getStreet2(): ?string
	{
		return $this->entity['street2'];
	}

	public function setStreet2(string $street2)
	{
		$this->entity['street2'] = $street2;
	}

	public function getStreet3(): ?string
	{
		return $this->entity['street3'];
	}

	public function setStreet3(string $street3)
	{
		$this->entity['street3'] = $street3;
	}

	public function getCity(): ?string
	{
		return $this->entity['city'];
	}

	public function setCity(string $city)
	{
		$this->entity['city'] = $city;
	}

	public function getCountry(): ?string
	{
		return $this->entity['country'];
	}

	public function setCountry(string $country)
	{
		$this->entity['country'] = $country;
	}

	public function getName(): ?string
	{
		return $this->entity['name'];
	}

	public function setName(string $name)
	{
		$this->entity['name'] = $name;
	}

	public function getSalutation(): ?string
	{
		return $this->entity['salutation'];
	}

	public function setSalutation(string $salutation)
	{
		$this->entity['salutation'] = $salutation;
	}

	public function getState(): ?string
	{
		return $this->entity['state'];
	}

	public function setState(string $state)
	{
		$this->entity['state'] = $state;
	}

	public function getZip(): ?string
	{
		return $this->entity['zip'];
	}

	public function setZip(string $zip)
	{
		$this->entity['zip'] = $zip;
	}

	public function getFax(): ?string
	{
		return $this->entity['fax'];
	}

	public function setFax(string $fax)
	{
		$this->entity['fax'] = $fax;
	}

	public function getPhone(): ?string
	{
		return $this->entity['phone'];
	}

	public function setPhone(string $phone)
	{
		$this->entity['phone'] = $phone;
	}

	public function getEmail(): ?string
	{
		return $this->entity['email'];
	}

	public function setEmail(string $email)
	{
		$this->entity['email'] = $email;
	}

	public function getCreated(): ?DateTime
	{
		return $this->entity['created'];
	}

	public function setCreated(DateTime $created)
	{
		$this->entity['created'] = $created;
	}

	public function getUpdated(): ?DateTime
	{
		return $this->entity['updated'];
	}

	public function setUpdated(DateTime $updated)
	{
		$this->entity['updated'] = $updated;
	}

	public function getType(): ?string
	{
		return $this->entity['type'];
	}

	public function setType(string $type)
	{
		$this->entity['type'] = $type;
	}

	public function getCustomer(): ?CustomerEntity
	{
		return $this->entity['customer'];
	}

	public function setCustomer(CustomerEntity $customer)
	{
		$this->entity['customer'] = $customer;
	}

	public function getCrossStreet(): string
	{
		return $this->entity['crossStreet'];
	}

	public function setCrossStreet(string $crossStreet)
	{
		$this->entity['crossStreet'] = $crossStreet;
	}
	public function getLatitude(): ?float
	{
		return $this->entity['gpsLat'];
	}

	public function setLatitude(?float $gpsLat)
	{
		$this->entity['gpsLat'] = $gpsLat;
	}

	public function getLongitude(): ?float
	{
		return $this->entity['gpsLong'];
	}

	public function setLongitude(?float $gpsLong)
	{
		$this->entity['gpsLong'] = $gpsLong;
	}
}