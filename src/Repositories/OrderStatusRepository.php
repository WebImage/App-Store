<?php

namespace WebImage\Store\Repositories;

use WebImage\Core\Dictionary;
use WebImage\Models\Entities\EntityStub;
use WebImage\Models\Services\ModelRepository;
use WebImage\Store\Entities\OrderStatusEntity;

class OrderStatusRepository extends ModelRepository
{
	public function __construct(\WebImage\Models\Services\RepositoryInterface $repo, string $model = 'orderStatus')
	{
		parent::__construct($repo, $model);
	}

	public function get(int $id): ?OrderStatusEntity
	{
		$entity = $this->query()->get($id);

		return $entity ? new OrderStatusEntity($entity) : null;
	}

	public function create(string $lookupKey, string $name, string $description = '', bool $customerVisible = true): OrderStatusEntity
	{
		$status = new OrderStatusEntity($this->createEntity());
		$status->setLookupKey($lookupKey);
		$status->setName($name);
		$status->setDescription($description);
		$status->setInternalName($name);
		$status->setInternallyVisible(true);
		$status->setCustomerVisible($customerVisible);

		return $status;
	}

	public function getByLookupKey(string $lookupKey): ?OrderStatusEntity
	{
		$entity = $this->query()->where('lookupKey', $lookupKey)->get();

		return $entity ? new OrderStatusEntity($entity) : null;
	}


	public function save(OrderStatusEntity $statusEntity): OrderStatusEntity
	{
		$entity = $this->getRepo()->saveEntity($statusEntity->getEntity());

		return new OrderStatusEntity($entity);
	}

	public function createLookup(): Dictionary
	{
		return $this->query()
					->sort('sortorder')
					->sort('name')
					->execute()
					->map(function (EntityStub $entity) {
						return new OrderStatusEntity($entity);
					})
					->createLookup(function (OrderStatusEntity $status) {
						return $status->getLookupKey();
					});
	}
}