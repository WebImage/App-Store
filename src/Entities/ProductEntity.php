<?php

namespace WebImage\Store\Entities;

use DateTime;
use WebImage\Models\Services\ModelEntity;
use WebImage\Security\Db\Entities\UserEntity;

class ProductEntity extends ModelEntity
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

	public function getName(): ?string
	{
		return $this->entity['name'];
    }

	public function setName(string $name): void
	{
		$this->entity['name'] = $name;
	}

    public function getDescription(): ?string
	{
		return $this->entity['description'];
    }

	public function setDescription(string $description): void
	{
		$this->entity['description'] = $description;
	}

	public function getStatus(): ?string
	{
		return $this->entity['status'];
	}

	public function setStatus(string $status): void
	{
		$this->entity['status'] = $status;
	}

	public function getType(): ?string
	{
		return $this->entity['type'];
	}

	public function setType(string $type): void
	{
		$this->entity['type'] = $type;
	}

	public function getUpdated(): ?DateTime
	{
		return $this->entity['updated'];
	}

	public function getUpdatedBy(): ?UserEntity
	{
		$entity = $this->entity['updatedBy'];
		return $entity ? new UserEntity($entity) : null;
	}
}