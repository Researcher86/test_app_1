<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function delele(Post $post)
    {
        $this->getEntityManager()->remove($post);
        $this->getEntityManager()->flush();
    }

    public function save(Post $post)
    {
        $this->getEntityManager()->persist($post);
        $this->getEntityManager()->flush();
    }

    public function search(array $tagIds)
    {
        return $this->createQueryBuilder('p')
            ->addSelect('count(p) as HIDDEN cnt')
            ->innerJoin('p.tags', 't')
            ->andWhere('t in (:tagIds)')
            ->setParameter('tagIds', $tagIds)
            ->groupBy('p.id')
            ->having('cnt = ' . count($tagIds))
            ->getQuery()
            ->getResult()
            ;
    }
}
