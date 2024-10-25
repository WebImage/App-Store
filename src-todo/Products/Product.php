<?php

namespace WebImage\Store\Products;

class Product
{
	private string $code;
	private bool   $enable;
	private string $id;
	private string $manufacturer_id;
	private string $metaClassId;
	private string $extendableId;
	private string $name;
	private string $description;
	private string $templateId;

	/**
	 * @param string $code
	 * @param bool $enable
	 * @param int $id
	 * @param string $manufacturerId
	 * @param string $metaClassId
	 * @param string $extendableId
	 * @param string $name
	 * @param string $description
	 * @param string $templateId
	 */
	public function __construct(string $code, bool $enable, int $id, string $manufacturerId, string $metaClassId, string $extendableId, string $name, string $description, string $templateId)
	{
		$this->code            = $code;
		$this->enable          = $enable;
		$this->id              = $id;
		$this->manufacturer_id = $manufacturerId;
		$this->metaClassId     = $metaClassId;
		$this->extendableId    = $extendableId;
		$this->name            = $name;
		$this->description     = $description;
		$this->templateId      = $templateId;
	}

	public function getCode(): string
	{
		return $this->code;
	}

	public function setCode(string $code): void
	{
		$this->code = $code;
	}

	public function isEnable(): bool
	{
		return $this->enable;
	}

	public function setEnable(bool $enable): void
	{
		$this->enable = $enable;
	}

	public function getId(): string
	{
		return $this->id;
	}

	public function setId(string $id): void
	{
		$this->id = $id;
	}

	public function getManufacturerId(): string
	{
		return $this->manufacturer_id;
	}

	public function setManufacturerId(string $manufacturer_id): void
	{
		$this->manufacturer_id = $manufacturer_id;
	}

	public function getMetaClassId(): string
	{
		return $this->metaClassId;
	}

	public function setMetaClassId(string $metaClassId): void
	{
		$this->metaClassId = $metaClassId;
	}

	public function getExtendableId(): string
	{
		return $this->extendableId;
	}

	public function setExtendableId(string $extendableId): void
	{
		$this->extendableId = $extendableId;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function getDescription(): string
	{
		return $this->description;
	}

	public function setDescription(string $description): void
	{
		$this->description = $description;
	}

	public function getTemplateId(): string
	{
		return $this->templateId;
	}

	public function setTemplateId(string $templateId): void
	{
		$this->templateId = $templateId;
	}
}