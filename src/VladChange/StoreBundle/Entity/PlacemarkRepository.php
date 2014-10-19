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
        $arry = $qb->select('p')
                   ->from('VladChangeStoreBundle:Placemark', 'p')
                   ->where('p.archived = 0')
                   ->andWhere('p.dieDate > p.createDate')
                   ->andWhere('p.id = :id')
                   ->setParameter('id', $id)
                   ->getQuery()
                   ->getResult();
        $arry = !empty($arry) ? $arry[0] : [];
        if (!empty($arry)) {
            $e = $arry;
            $author = $e->getUser();
            $arry = [
                'id' => $e->getId(),
                'name' => $e->getName(),
                'desc' => $e->getDesc(),
                'author' => [
                    'id'      => $author->getId(),
                    'name'    => $author->getName(),
                    'surname' => $author->getSurname()
                ],
                'dieDate' => $e->getDieDate()->format('d.m.Y'),
                'shortDesc' => $e->getShortDesc(),
                'createDate' => $e->getCreateDate()->format('d.m.Y'),
                'limitVoice' => $e->getLimitVoice(),
            ];
        }
        return $arry;
    }

    public function getCurrentPlacemarks()
    {
        $qb = $this->_em->createQueryBuilder();
        return $qb->select('p.id, p.name, p.lat, p.lon, p.shortDesc')
                  ->from('VladChangeStoreBundle:Placemark', 'p')
                  ->where('p.archived = 0')
                  ->andWhere('p.dieDate > p.createDate')
                  ->getQuery()
                  ->getArrayResult();
    }
}