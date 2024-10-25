<?php

namespace WebImage\Store\Entities;

use App\Entities\UserEntity;
use WebImage\Models\Services\ModelEntity;

class OrderStatusEntity extends ModelEntity
{
	public function getId(): ?int
	{
		return $this->entity['id'];
	}

	public function getName(): ?string
	{
		return $this->entity['name'];
	}

	public function setName(string $name): void
	{
		$this->entity['name'] = $name;
	}

	public function getInternalName(): ?string
	{
		return $this->entity['internalName'];
	}

	public function setInternalName(): ?string
	{
		return $this->entity['internalName'];
	}

	public function getDescription(): ?string
	{
		return $this->entity['description'];
	}

	public function setDescription(string $description): void
	{
		$this->entity['description'] = $description;
	}

	public function getLookupKey(): ?string
	{
		return $this->entity['lookupKey'];
	}

	public function setLookupKey(string $lookupKey): void
	{
		$this->entity['lookupKey'] = $lookupKey;
	}

	public function getCreated(): ?\DateTime
	{
		return $this->entity['created'];
	}

	public function getCreatedBy(): ?UserEntity
	{
		$entity = $this->entity['createdBy'];
		return $entity ? new UserEntity($entity) : null;
	}

	public function getUpdated(): ?\DateTime
	{
		return $this->entity['updated'];
	}

	public function getUpdatedBy(): ?UserEntity
	{
		$entity = $this->entity['updatedBy'];
		return $entity ? new UserEntity($entity) : null;
	}

	public function isCustomerVisible(): bool
	{
		return (bool)$this->entity['customerVisible'];
	}

	public function setCustomerVisible(bool $customerVisible): void
	{
		$this->entity['customerVisible'] = $customerVisible;
	}

	public function isInternallyVisible(): bool
	{
		return (bool)$this->entity['internallyVisible'];
	}

	public function setInternallyVisible(bool $internallyVisible): void
	{
		$this->entity['internallyVisible'] = $internallyVisible;
	}
}