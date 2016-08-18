<?php

namespace CommonBundle\Grid;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\PropertyAccess\PropertyAccess;

class GridBuilder implements \JsonSerializable
{
    use ContainerAwareTrait;

    protected $entityClass;

    protected $columns;

    protected $data;

    protected $count;

    protected $limit;

    protected $offset;

    protected $queryBuilder;

    protected $entityManager;

    public function __construct()
    {
        $this->columns = [];
    }

    /**
     * Add column to grid
     *
     * @param $id - entity field name
     * @param null $title
     * @param string $type
     * @param array $options
     * @return $this
     */
    public function add($id, $title = null, $type = 'string', $options = [])
    {
        $title = (!is_null($title)) ? $title : $this->humanize($id);

        $this->columns[] = new GridColumn($id, $title, $type, $options);

        return $this;
    }

    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setOffset($offset)
    {
        $this->offset = $offset;

        return $this;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function setQueryBuilder(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;

        return $this;
    }

    public function getQueryBuilder()
    {
        return $this->queryBuilder;
    }

    public function build()
    {
        if(empty($this->entityClass)){
            throw new \Exception('Entity class not set. Call GridBuilder::setEntityClass() before building');
        }

        if (!$queryBuilder = $this->getQueryBuilder()) {
            $queryBuilder = $this->container->get('doctrine')
                ->getRepository($this->getEntityClass())
                ->createQueryBuilder('o');
        }

        $countQueryBuilder = clone($queryBuilder);

        $this->data = $queryBuilder
            ->setMaxResults($this->getLimit())
            ->setFirstResult($this->getOffset())
            ->getQuery()
            ->getResult()
        ;

        $this->count = $countQueryBuilder
            ->select('COUNT(o)')
            ->getQuery()
            ->getSingleScalarResult();

        return $this;
    }

    public function jsonSerialize()
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $data = [];
        foreach($this->data as $entity){
            $row = [];
            foreach($this->columns as $column){

                $fieldValue = $entity;
                foreach(explode('.', $column->getId()) as $fieldName){
                    $fieldValue = $accessor->getValue($fieldValue, $fieldName);
                    if($fieldValue === null){
                        break;
                    }
                }

                if ($fieldValue instanceof \DateTime) {
                    $row[$column->getId()] = $fieldValue->getTimeStamp();
                } else {
                    $row[$column->getId()] = $fieldValue;
                }
            }

            $data[] = $row;
        }

        return [
            'head' => $this->columns,
            'rows' => $data,
            'current_limit' => $this->getLimit(),
            'current_offset' => $this->getOffset(),
            'count' => $this->count,
        ];
    }

    protected function humanize($text)
    {
        return ucfirst(trim(strtolower(preg_replace(array('/([A-Z])/', '/[_\s]+/'), array('_$1', ' '), $text))));
    }
}
