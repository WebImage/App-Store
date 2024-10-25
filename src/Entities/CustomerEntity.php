<?php

namespace WebImage\Store\Entities;

use App\Entities\CompanyEntity;
use DateTime;
use WebImage\Models\Services\ModelEntity;

class CustomerEntity extends ModelEntity
{
	public function getId(): int
	{
		return $this->entity['id'];
	}

	public function setId(int $id)
	{
		$this->entity['id'] = $id;
	}

	public function getType(): string
	{
		return $this->entity['type'];
	}

	public function setType(string $type)
	{
		$this->entity['type'] = $type;
	}

	public function getAccountNumber(): ?string
	{
		return $this->entity['accountNo'];
	}

	public function setAccountNumber(string $accountNo)
	{
		$this->entity['accountNo'] = $accountNo;
	}

//	public function getStreet1(): ?string
//	{
//		return $this->entity['street1'];
//	}

//	public function setStreet1(string $street1)
//	{
//		$this->entity['street1'] = $street1;
//	}
//
//	public function getStreet2(): ?string
//	{
//		return $this->entity['street2'];
//	}
//
//	public function setStreet2(string $street2)
//	{
//		$this->entity['street2'] = $street2;
//	}
//
//	public function getStreet3(): ?string
//	{
//		return $this->entity['street3'];
//	}
//
//	public function setStreet3(string $street3)
//	{
//		$this->entity['street3'] = $street3;
//	}

//	public function getAttention(): ?string
//	{
//		return $this->entity['attn'];
//	}
//
//	public function setAttention(string $attn)
//	{
//		$this->entity['attn'] = $attn;
//	}

	public function getBillingAddress(): ?CustomerAddressEntity
	{
		return $this->entity['billingAddress'] === null ? null : new CustomerAddressEntity($this->entity['billingAddress']);
	}

	public function setBillingAddress(?CustomerAddressEntity $billingAddress)
	{
		$this->entity['billingAddress'] = $billingAddress->getEntity();
	}

	public function getShippingAddress(): ?CustomerAddressEntity
	{
		return $this->entity['shippingAddress'] === null ? null : new CustomerAddressEntity($this->entity['shippingAddress']);
	}

	public function setShippingAddress(?CustomerAddressEntity $shippingAddress)
	{
		$this->entity['shippingAddress'] = $shippingAddress->getEntity();
	}
//
//	public function getBillStreet1(): ?string
//	{
//		return $this->entity['billStreet1'];
//	}
//
//	public function setBillStreet1(string $billStreet1)
//	{
//		$this->entity['billStreet1'] = $billStreet1;
//	}
//
//	public function getBillStreet2(): ?string
//	{
//		return $this->entity['billStreet2'];
//	}
//
//	public function setBillStreet2(string $billStreet2)
//	{
//		$this->entity['billStreet2'] = $billStreet2;
//	}
//
//	public function getBillAttention(): ?string
//	{
//		return $this->entity['billAttn'];
//	}
//
//	public function setBillAttention(string $billAttn)
//	{
//		$this->entity['billAttn'] = $billAttn;
//	}
//
//	public function getBillCity(): ?string
//	{
//		return $this->entity['billCity'];
//	}
//
//	public function setBillCity(string $billCity)
//	{
//		$this->entity['billCity'] = $billCity;
//	}
//
//	public function getBillCountry(): ?string
//	{
//		return $this->entity['billCountry'];
//	}
//
//	public function setBillCountry(string $billCountry)
//	{
//		$this->entity['billCountry'] = $billCountry;
//	}
//
//	public function getBillFax(): ?string
//	{
//		return $this->entity['billFax'];
//	}
//
//	public function setBillFax(string $billFax)
//	{
//		$this->entity['billFax'] = $billFax;
//	}
//
//	public function getBillName(): ?string
//	{
//		return $this->entity['billName'];
//	}
//
//	public function setBillName(string $billName)
//	{
//		$this->entity['billName'] = $billName;
//	}

//	public function getBillPhone(): ?string
//	{
//		return $this->entity['billPhone'];
//	}
//
//	public function setBillPhone(string $billPhone)
//	{
//		$this->entity['billPhone'] = $billPhone;
//	}
//
//	public function getBillSalutation(): ?string
//	{
//		return $this->entity['billSalutation'];
//	}
//
//	public function setBillSalutation(string $billSalutation)
//	{
//		$this->entity['billSalutation'] = $billSalutation;
//	}
//
//	public function getBillRegion(): ?string
//	{
//		return $this->entity['billRegion'];
//	}
//
//	public function setBillRegion(string $billRegion)
//	{
//		$this->entity['billRegion'] = $billRegion;
//	}
//
//	public function getBillZip(): ?string
//	{
//		return $this->entity['billZip'];
//	}
//
//	public function setBillZip(string $billZip)
//	{
//		$this->entity['billZip'] = $billZip;
//	}
//
//	public function getCity(): ?string
//	{
//		return $this->entity['city'];
//	}
//
//	public function setCity(string $city)
//	{
//		$this->entity['city'] = $city;
//	}
//
//	public function getCountry(): ?string
//	{
//		return $this->entity['country'];
//	}
//
//	public function setCountry(string $country)
//	{
//		$this->entity['country'] = $country;
//	}

	public function getCreated(): ?string
	{
		return $this->entity['created'];
	}

	public function setCreated(string $created)
	{
		$this->entity['created'] = $created;
	}

	public function getDunMsg(): ?string
	{
		return $this->entity['dunMsg'];
	}

	public function setDunMsg(string $dunMsg)
	{
		$this->entity['dunMsg'] = $dunMsg;
	}

	public function getEmail(): ?string
	{
		return $this->entity['email'];
	}

	public function setEmail(string $email)
	{
		$this->entity['email'] = $email;
	}

	public function getFax(): ?string
	{
		return $this->entity['fax'];
	}

	public function setFax(string $fax)
	{
		$this->entity['fax'] = $fax;
	}

	public function getUpdated(): ?DateTime
	{
		return $this->entity['updated'];
	}

	public function setUpdated(DateTime $updated)
	{
		$this->entity['updated'] = $updated;
	}

	public function getName(): ?string
	{
		return $this->entity['name'];
	}

	public function setName(string $name)
	{
		$this->entity['name'] = $name;
	}

	public function getPhone(): ?string
	{
		return $this->entity['phone'];
	}

	public function setPhone(string $phone)
	{
		$this->entity['phone'] = $phone;
	}

	public function getSalutation(): ?string
	{
		return $this->entity['salutation'];
	}

	public function setSalutation(string $salutation)
	{
		$this->entity['salutation'] = $salutation;
	}

//	public function getRegion(): ?string
//	{
//		return $this->entity['region'];
//	}
//
//	public function setRegion(string $region)
//	{
//		$this->entity['region'] = $region;
//	}

	public function getStatus(): ?string
	{
		return $this->entity['status'];
	}

	public function setStatus(string $status)
	{
		$this->entity['status'] = $status;
	}

//	public function getZip(): ?string
//	{
//		return $this->entity['zip'];
//	}
//
//	public function setZip(string $zip)
//	{
//		$this->entity['zip'] = $zip;
//	}
//
//	public function getWebsite(): string
//	{
//		return $this->entity['website'];
//	}
//
//	public function setWebsite(string $website)
//	{
//		$this->entity['website'] = $website;
//	}
//
//	public function getCrossStreet(): string
//	{
//		return $this->entity['crossStreet'];
//	}
//
//	public function setCrossStreet(string $crossStreet)
//	{
//		$this->entity['crossStreet'] = $crossStreet;
//	}

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