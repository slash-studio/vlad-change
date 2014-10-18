<?php

namespace VladChange\StoreBundle\Entity;
use Doctrine\ORM\EntityRepository;

/*
 * PlacemarkRepository
 */
class PlacemarkRepository extends EntityRepository
{
    public function getFullInfo($id)
    {
        $qb = $this->_em->createQueryBuilder();
        $a = $qb->select('p')
                ->from('VladChangeStoreBundle:Placemark', 'p')
                ->where('p.archived = 0')
                ->andWhere('p.dieDate > p.createDate')
                ->andWhere('p.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getArrayResult();
        return !empty($a) ? $a[0] : [];
    }

    public function getCurrentPlacemarks()
    {
        $qb = $this->_em->createQueryBuilder();
        return $qb->select('p.id, p.name, p.lat, p.lon')
                  ->from('VladChangeStoreBundle:Placemark', 'p')
                  ->where('p.archived = 0')
                  ->andWhere('p.dieDate > p.createDate')
                  ->getQuery()
                  ->getArrayResult();
    }
}