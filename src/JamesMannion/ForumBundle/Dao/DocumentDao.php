<?php

namespace Brokenshire\InsuranceBundle\Dao;

use Brokenshire\InsuranceBundle\SearchParams;
use Brokenshire\InsuranceBundle\Constants;
use Brokenshire\InsuranceBundle\Entity;
use Brokenshire\InsuranceBundle\Interfaces;

class DocumentDao extends BaseDao
{
    protected $entityRepositoryName = Constants\Entity::DOCUMENT;

    public function countUsersWithDocuments()
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $query = $queryBuilder->select('count(DISTINCT user)')
            ->from(Constants\Entity::DOCUMENT, 'document')
            ->join('document.policy', 'policy')
            ->join('policy.owner', 'user');
        return $query->getQuery()->getSingleScalarResult();
    }

    public function countAll()
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $query = $queryBuilder->select('count(document)')
            ->from(Constants\Entity::DOCUMENT, 'document');
        return $query->getQuery()->getSingleScalarResult();
    }
}
