<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Item>
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function findWithColours() : array
    {
        $entityManager = $this->getEntityManager();

        $items = $entityManager->createQuery('
            select i, c
            from App\Entity\Item i
            left join i.colour c
        ')->getResult();

        foreach ($items as &$item)
        {
            $item = [
                'code' => $item->getCode(),
                'ean13' => $item->getEan13(),
                'dun14' => $item->getDun14(),
                'cartonQty' => $item->getCartonQty(),
                'price' => $item->getPrice(),
                'colour' => $item->getColour()->getName(),
                'size' => $item->getSize(),
                'description' => $item->getDescription(),
                'weight' => $item->getWeight(),
                'imagePath' => $item->getImagePath()];
        }
        unset($item);

        return $items;
    }

    //    /**
    //     * @return Item[] Returns an array of Item objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Item
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
