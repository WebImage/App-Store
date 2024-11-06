<?php

namespace WebImage\Store\Repositories;

use WebImage\Models\Entities\EntityStub;
use WebImage\Models\Services\Db\EntityCollection;
use WebImage\Models\Services\ModelRepository;
use WebImage\Models\Services\RepositoryInterface;
use WebImage\Store\Entities\CustomerAddressEntity;
use WebImage\Store\Entities\CustomerEntity;

abstract class CustomerAddressRepository extends ModelRepository
{
	public function __construct(RepositoryInterface $repo, string $model = 'customerAddress')
	{
		parent::__construct($repo, $model);
	}

	public function create(string $type): CustomerAddressEntity
	{
		$address = $this->entityToCustomerAddress(parent::createEntity());
		$address->setType($type);
		return $address;
	}

	public function get(int $id): ?CustomerAddressEntity
	{
		$entity = $this->query()->get($id);

		return $entity === null ? null : $this->entityToCustomerAddress($entity);
	}

	public function getAddressesByCustomer(CustomerEntity $customer, int $limit = null, int $offset = null): EntityCollection
	{
		$query = $this->query()->where('customer', $customer);
		if ($limit !== null) $query->limit($limit);
		if ($offset !== null) $query->offset($offset);

		return $query->execute()
					 ->map(function (EntityStub $entity) {
						 return $this->entityToCustomerAddress($entity);
					 });
	}

	/**
	 * Allow overriding classes to convert entity to a CustomerEntity inheriting class
	 * @param EntityStub $entity
	 * @return CustomerAddressEntity
	 */
	protected function entityToCustomerAddress(EntityStub $entity): CustomerAddressEntity
	{
		return new CustomerAddressEntity($entity);
	}
}