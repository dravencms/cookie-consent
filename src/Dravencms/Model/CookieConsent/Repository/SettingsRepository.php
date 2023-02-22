<?php declare(strict_types = 1);
/**
 * Copyright (C) 2023 Adam Schubert <adam.schubert@sg1-game.net>.
 */

namespace Dravencms\Model\CookieConsent\Repository;

use Dravencms\Model\CookieConsent\Entities\Settings;
use Dravencms\Database\EntityManager;

class SettingsRepository
{
    /** @var \Doctrine\Persistence\ObjectRepository|Settings */
    private $settingsRepository;

    /** @var EntityManager */
    private $entityManager;

    /**
     * MenuRepository constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->settingsRepository = $entityManager->getRepository(Settings::class);
    }

    /**
     * @param int $id
     * @return null|Settings
     */
    public function getOneById(int $id): Settings
    {
        return $this->settingsRepository->find($id);
    }

    /**
     * @param $id
     * @return Settings[]
     */
    public function getById($id)
    {
        return $this->settingsRepository->findBy(['id' => $id]);
    }

    /**
     * @return QueryBuilder
     */
    public function getSettingsQueryBuilder()
    {
        $qb = $this->settingsRepository->createQueryBuilder('r')
            ->select('r');
        return $qb;
    }

    /**
     * @param $identifier
     * @param Settings|null $settingsIgnore
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function isIdentifierFree(string $identifier, Settings $settingsIgnore = null): bool
    {
        $qb = $this->settingsRepository->createQueryBuilder('s')
            ->select('s')
            ->where('s.identifier = :identifier')
            ->setParameters([
                'identifier' => $identifier
            ]);

        if ($robotsIgnore)
        {
            $qb->andWhere('s != :settingsIgnore')
                ->setParameter('settingsIgnore', $settingsIgnore);
        }

        return (is_null($qb->getQuery()->getOneOrNullResult()));
    }

    /**
     * @return Settings[]
     */
    public function getAll()
    {
        return $this->settingsRepository->findAll();
    }

    /**
     * @param bool $isActive
     * @return array
     */
    public function getActive(bool $isActive = true)
    {
        return $this->settingsRepository->findBy(['isActive' => $isActive]);
    }
}