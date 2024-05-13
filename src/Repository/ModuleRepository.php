<?php

declare(strict_types=1);

namespace Spyck\AuthenticationBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Spyck\AuthenticationBundle\Entity\ModuleInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class ModuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry, #[Autowire(param: 'spyck.authentication.config.module.class')] private readonly string $class)
    {
        parent::__construct($managerRegistry, $this->class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getModuleById(int $id): ?ModuleInterface
    {
        return $this->createQueryBuilder('module')
            ->where('module.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function patchModule(ModuleInterface $module, array $fields, array $parameters): void
    {
        if (in_array('parameters', $fields, true)) {
            $module->setParameter($parameters);
        }

        $this->getEntityManager()->persist($module);
        $this->getEntityManager()->flush();
    }
}
