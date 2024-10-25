<?php

namespace WebImage\Store\Entities;

use DateTime;
use WebImage\Models\Services\ModelEntity;
use WebImage\Security\Db\Entities\UserEntity;

class SkuEntity extends ModelEntity
{
	public function getCreated(): ?DateTime
	{
		return $this->entity['created'];
	}

	public function getCreatedBy(): ?UserEntity
	{
		$entity = $this->entity['createdBy'];
		return $entity ? new UserEntity($entity) : null;
	}

	public function isEnabled(): bool
	{
		return (bool)$this->entity['enable'];
	}
	public function setIsEnabled(bool $enabled): void
	{
		$this->entity['enable'] = $enabled;
	}

	public function getId(): ?int
	{
		return $this->entity['id'];
	}

	public function getCode(): ?string
		{
		return $this->entity['code'];
	}

	public function setCode(string $code): void
	{
		$this->entity['code'] = $code;
	}

	public function getProduct(): ?ProductEntity
	{
		$entity = $this->entity['product'];
		return $entity ? new ProductEntity($entity) : null;
	}

	public function setProduct(ProductEntity $product): void
	{
		$this->entity['product'] = $product->entity;
	}

	public function getStatus(): ?string
	{
		return $this->entity['status'];
	}

	public function setStatus(string $status): void
	{
		$this->entity['status'] = $status;
	}

	public function getPrice(): ?float
	{
		return $this->entity['price'];
	}

	public function setPrice(float $price): void
	{
		$this->entity['price'] = $price;
	}

	public function getCycleLength(): ?int
	{
		return $this->entity['cycleLength'];
	}

	public function setCycleLength(int $cycleLength): void
	{
		$this->entity['cycleLength'] = $cycleLength;
	}

	public function getCycleMode(): ?int
	{
		return $this->entity['cycleMode'];
	}

	public function setCycleMode(int $cycleMode): void
	{
		$this->entity['cycleMode'] = $cycleMode;
	}

	public function getDescription(): ?string
	{
		return $this->entity['description'];
	}

	public function setDescription(string $description): void
	{
		$this->entity['description'] = $description;
	}

	public function getName(): ?string
	{
		return $this->entity['name'];
	}

	public function setName(string $name): void
	{
		$this->entity['name'] = $name;
	}

	public function getUpdated(): ?DateTime
	{
		return $this->entity['updated'];
	}

	public function setUpdated(DateTime $updated): void
	{
		$this->entity['updated'] = $updated;
	}

	public function getUpdatedBy(): ?UserEntity
	{
		$entity = $this->entity['updatedBy'];
		return $entity ? new UserEntity($entity) : null;
	}

	public function setUpdatedBy(UserEntity $updatedBy): void
	{
		$this->entity['updatedBy'] = $updatedBy->entity;
	}

	public function isVisible(): ?bool
	{
		return $this->entity['visible'];
	}

	public function setVisible(bool $visible): void
	{
		$this->entity['visible'] = $visible;
	}
}