<?php

namespace CivPlanet\Bundle\Repository;

use Doctrine\ORM\EntityRepository;

class SessionRepository extends EntityRepository
{
    public function findSessions()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT s FROM CPBundle:Session s
                ORDER BY s.loginTimestamp DESC'
            )
            ->getResult();
    }

    public function findSessionsByParams($params)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $query = $qb->select('s')
            ->from('CPBundle:Session', 's')
            ->innerJoin('s.player', 'p')
            ->orderBy('s.loginTimestamp', 'DESC');

        if (isset($params['username'])) {
            $qb->andWhere('p.username = :username')
                ->setParameter('username', $params['username']);
        }

        if (isset($params['login'])) {
            $qb->andWhere('s.loginEvent = :login')
                ->setParameter('login', $params['login']);
        }

        if (isset($params['logout'])) {
            $qb->andWhere('s.logoutEvent = :logout')
                ->setParameter('logout', $params['logout']);
        }

        if (isset($params['at'])) {
            $qb->andWhere('s.loginTimestamp <= :at
                AND s.logoutTimestamp >= :at')
                ->setParameter('at', $params['at']);
        } else if (isset($params['from']) && isset($params['to'])) {
            $qb->andWhere('(s.loginTimestamp >= :from AND s.logoutTimestamp <= :to)
                OR (s.loginTimestamp <= :from AND s.logoutTimestamp >= :from)
                OR (s.loginTimestamp <= :to AND s.logoutTimestamp >= :to)')
                ->setParameter('from', $params['from'])
                ->setParameter('to', $params['to']);
        } else if (isset($params['from'])) {
            $qb->andWhere('s.loginTimestamp >= :from')
                ->setParameter('from', $params['from']);
        } else if (isset($params['to'])) {
            $qb->andWhere('s.logoutTimestamp <= :to')
                ->setParameter('to', $params['to']);
        }

        return $query->getQuery()->getResult();
    }
}