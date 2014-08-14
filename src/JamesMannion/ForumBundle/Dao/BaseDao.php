<?php
/**
 * Created by PhpStorm.
 * User: James
 * Date: 11/02/14
 * Time: 19:45
 */

namespace JamesMannion\ForumBundle\Dao;


use Doctrine\DBAL\Driver\OCI8\OCI8Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use JamesMannion\ForumBundle\Constants\AppConfig;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Pagerfanta\Exception\NotValidCurrentPageException;

class BaseDao
{
    protected $entityManager;
    protected $entityRepository;
    protected $entityRepositoryName;
    protected $sortableService;

    protected $defaultSortField;

    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function setSortableService(SortableService $sortableService)
    {
        $this->sortableService = $sortableService;
    }

    public function setEntityRepository()
    {
        $this->entityRepository = $this->getEntityRepository($this->entityRepositoryName);
    }

    /**
     * @param $entityName
     * @return EntityRepository
     */
    protected function getEntityRepository($entityName)
    {
        return $this->entityManager->getRepository($entityName);
    }

    /**
     * @param $entityToPersist
     * @param bool $immediateFlush
     * @return bool
     */
    public function createEntity($entityToPersist, $immediateFlush = true)
    {
        try {
            $this->entityManager->persist($entityToPersist);
        } catch (OCI8Exception $e) {
            return false;
        }

        if (true === $immediateFlush) {
            return $this->flushEntity();
        }

        return true;
    }

    /**
     * @param $entityToDelete
     * @return bool
     */
    public function deleteEntity($entityToDelete)
    {
        try {
            $this->entityManager->remove($entityToDelete);
            $this->entityManager->flush();
        } catch (OCI8Exception $e) {
            return false;
        }
        return true;
    }

    public function updateEntity($entityToUpdate)
    {
        $this->entityManager->persist($entityToUpdate);
        $this->entityManager->flush();
    }

    /**
     * @param $idToFind
     * @return null|object
     */
    public function findEntityById($idToFind)
    {
        return $this->entityRepository
            ->find($idToFind);
    }

    /**
     * @param $searchParams
     * @return array|bool
     */
    public function findEntitiesByCriteria($searchParams)
    {
        $where = $this->buildWhereClause($searchParams);

        $orderBy = $searchParams->getOrderBy();
        $limit = $searchParams->getLimit();
        $offset = $searchParams->getOffset();

        try {
            $entities = $this->entityRepository
            ->findBy(
                $where,
                $orderBy,
                $limit,
                $offset
            );
            return $entities;
        } catch (OCI8Exception $e) {
            return false;
        }
    }

    /**
     * @param $searchParams
     * @return bool
     */
    public function findEntityByCriteria($searchParams)
    {
        $where = $this->buildWhereClause($searchParams);

        $orderBy = $searchParams->getOrderBy();
        $limit = $searchParams->getLimit();
        $offset = $searchParams->getOffset();

        try {
            $entities = $this->entityRepository
                ->findOneBy(
                    $where,
                    $orderBy,
                    $limit,
                    $offset
                );
            return $entities;
        } catch (OCI8Exception $e) {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function findAllEntities()
    {
        try {
            $entities = $this->entityRepository->findAll();
            return $entities;
        } catch (OCI8Exception $e) {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function flushEntity()
    {
        try {
            $this->entityManager->flush();
        } catch (OCI8Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * @param $entityToDetatch
     * @return bool
     */
    public function detatchEntity($entityToDetatch)
    {
        try {
            $this->entityManager->detach($entityToDetatch);
        } catch (OCI8Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * @param array $where
     * @param array $binds
     * @param QueryBuilder $queryBuilder
     * @return QueryBuilder
     */
    public function applyBinds(array $where, array $binds, QueryBuilder $queryBuilder)
    {
        if (count($where) > 0) {
            $queryBuilder
                ->where(implode(" AND ", $where))
                ->setParameters( $binds );
        }
        return $queryBuilder;
    }

    /**
     * @param $query
     * @param null $page
     * @return Pagerfanta
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function paginate($query, $page = null) {

        $pagerFanta = new Pagerfanta(new DoctrineORMAdapter($query));
        $pagerFanta->setMaxPerPage(AppConfig::ITEMS_PER_PAGE);

        $page = $this->validateCurrentPage($page);

        try {
            $pagerFanta->setCurrentPage($page);
        } catch (NotValidCurrentPageException $e) {
            throw new NotFoundHttpException();
        }

        return $pagerFanta;
    }

    protected function paginateFromArray($array, $page = null) {

        $pagerFanta = new Pagerfanta(new ArrayAdapter($array));
        $pagerFanta->setMaxPerPage(AppConfig::ITEMS_PER_PAGE);

        $page = $this->validateCurrentPage($page);

        try {
            $pagerFanta->setCurrentPage($page);
        } catch (NotValidCurrentPageException $e) {
            throw new NotFoundHttpException();
        }

        return $pagerFanta;
    }

    /**
     * @param $defaultSortField
     * @return mixed
     */
    protected function getSortField($defaultSortField)
    {
        $orderByFromSession
            = $this->sortableService->getEntitySorting($this->entityRepositoryName);

        if (null !== $orderByFromSession) {
            return $orderByFromSession['field'];
        }
        return $defaultSortField;
    }

    /**
     * @param string $defaultSortOrientation
     * @return string
     */
    protected function getSortOrientation($defaultSortOrientation = 'ASC')
    {
        $orderByFromSession = $this->sortableService->getEntitySorting($this->entityRepositoryName);

        if (null !== $orderByFromSession) {
            return $orderByFromSession['order'];
        }
        return $defaultSortOrientation;
    }

    /**
     * @param $page
     * @return int
     */
    private function validateCurrentPage($page)
    {
        if (null == $page || true == empty($page)) {
            return 1;
        }

        if(!is_numeric($page)) {
            return 1;
        }

        if ((int)$page != $page) {
            return 1;
        }

        if (1 > $page) {
            return 1;
        }

        return $page;
    }

    public function getDefaultSortField()
    {
        return $this->defaultSortField;
    }

}
