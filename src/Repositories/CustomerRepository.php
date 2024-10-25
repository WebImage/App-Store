<?php

namespace WebImage\Store\Repositories;

use WebImage\Models\Entities\EntityStub;
use WebImage\Models\Services\Db\EntityCollection;
use WebImage\Models\Services\ModelRepository;
use WebImage\Models\Services\RepositoryInterface;
use WebImage\Store\Entities\CustomerEntity;

class CustomerRepository extends ModelRepository
{
	public function __construct(RepositoryInterface $repo, string $model = 'customer')
	{
		parent::__construct($repo, $model);
	}

	public function create(): CustomerEntity
	{
		return $this->entityToCustomer(parent::createEntity());
	}

	public function get(int $id): ?CustomerEntity
	{
		$entity = $this->query()->get($id);

		return $entity === null ? null : $this->entityToCustomer($entity);
	}

	public function getCustomers(int $limit = null, int $offset = null): EntityCollection
	{
		$query = $this->query();
		if ($limit !== null) $query->limit($limit);
		if ($offset !== null) $query->offset($offset);

		return $query->execute()
					 ->map(function (EntityStub $entity) {
						 return $this->entityToCustomer($entity);
					 });
	}

	/**
	 * Allow overriding classes to use their own version of customer entity
	 * @param EntityStub $entity
	 * @return CustomerEntity
	 */
	protected function entityToCustomer(EntityStub $entity): CustomerEntity
	{
		return new CustomerEntity($entity);
	}
}