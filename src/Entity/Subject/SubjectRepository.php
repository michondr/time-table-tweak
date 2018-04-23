<?php

namespace App\Entity\Subject;

use Doctrine\ORM\EntityRepository;

class SubjectRepository extends EntityRepository
{
    public function findAll()
    {
//        return $this->findBy([], ['indent' => 'ASC']);
        return $this->createQueryBuilder('o')
            ->where('o.indent LIKE \'4IZ%\'')
//            ->and
            ->getQuery()
            ->getResult();
    }
}
