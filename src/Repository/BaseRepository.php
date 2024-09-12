<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class BaseRepository extends ServiceEntityRepository
{
    abstract protected function getEntityClass(): string;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, $this->getEntityClass());
    }

    public function findOrFail($id)
    {
        $entity = $this->find($id);
        if (!$entity) {
            throw new NotFoundHttpException(sprintf('%s with ID %d not found', $this->getClassName(), $id));
        }

        return $entity;
    }
}
