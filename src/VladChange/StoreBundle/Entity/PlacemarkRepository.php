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
                    'id'      => !empty($author) ? $author->getId() : '',
                    'name'    => !empty($author) ? $author->getName() : '',
                    'surname' => !empty($author) ? $author->getSurname() : ''
                ],
                // 'images' => $e->getImages(),
                'comments' => $e->getComments()->toArray(),
                'voices'  => $e->getLikes()->count(),
                'dieDate' => $e->getDieDate()->format('d.m.Y'),
                'shortDesc' => $e->getShortDesc(),
                'createDate' => $e->getCreateDate()->format('d.m.Y'),
            ];
        }
        return $arry;
    }

    public function getCurrentPlacemarks($uid)
    {
        $qb = $this->_em->createQueryBuilder();
        $arry = $qb->select('p')
                  ->from('VladChangeStoreBundle:Placemark', 'p')
                  ->where('p.archived = 0')
                  ->andWhere('p.dieDate > p.createDate')
                  ->getQuery()
                  ->getResult();
        $result = [];
        $setRelation = function(&$e) use($uid) {
            $v = 0;
            if (!empty($e->getUser()) && $e->getUser()->getId() == $uid) {
                $v = 1;
            } else {
                foreach ($e->getLikes()->toArray() as &$like) {
                    if ($like->getId() == $uid) {
                        $v = 2;
                        break;
                    }
                }
            }
            return $v;
        };
        foreach ($arry as &$e) {
            $author = $e->getUser();
            $result[] = [
                'id' => $e->getId(),
                'name' => $e->getName(),
                'lat' => $e->getLat(),
                'lon' => $e->getLon(),
                'shortDesc' => $e->getShortDesc(),
                'relation' => $setRelation($e)
            ];
        }
        return $result;
    }
}