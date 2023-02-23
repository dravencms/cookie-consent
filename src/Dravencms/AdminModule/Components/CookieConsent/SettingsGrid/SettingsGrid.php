<?php declare(strict_types = 1);

/*
 * Copyright (C) 2023 Adam Schubert <adam.schubert@sg1-game.net>.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301  USA
 */

namespace Dravencms\AdminModule\Components\CookieConsent\SettingsGrid;

use Dravencms\Components\BaseControl\BaseControl;
use Dravencms\Components\BaseGrid\BaseGridFactory;
use Dravencms\Components\BaseGrid\Grid;
use Dravencms\Locale\CurrentLocaleResolver;
use Dravencms\Model\CookieConsent\Entities\Settings;
use Dravencms\Model\CookieConsent\Repository\SettingsRepository;
use Dravencms\Database\EntityManager;
use Dravencms\Model\Locale\Entities\Locale;
use Nette\Security\User;
use Ublaboo\DataGrid\Column\Action\Confirmation\StringConfirmation;

/**
 * Description of SettingsGrid
 *
 * @author Adam Schubert <adam.schubert@sg1-game.net>
 */
class SettingsGrid extends BaseControl
{

    /** @var BaseGridFactory */
    private $baseGridFactory;

    /** @var SettingsRepository */
    private $settingsRepository;

    /** @var EntityManager */
    private $entityManager;

    /** @var User */
    private $user;

    /** @var Locale */
    private $currentLocale;

    /**
     * @var array
     */
    public $onDelete = [];

    /**
     * SettingsGrid constructor.
     * @param SettingsRepository $settingsRepository
     * @param BaseGridFactory $baseGridFactory
     * @param EntityManager $entityManager
     * @param User $user
     * @param CurrentLocaleResolver $currentLocaleResolver
     * @throws \Exception
     */
    public function __construct(
        SettingsRepository $settingsRepository, 
        BaseGridFactory $baseGridFactory, 
        EntityManager $entityManager,
        User $user,
        CurrentLocaleResolver $currentLocaleResolver
    )
    {
        $this->baseGridFactory = $baseGridFactory;
        $this->settingsRepository = $settingsRepository;
        $this->entityManager = $entityManager;
        $this->user = $user;
        $this->currentLocale = $currentLocaleResolver->getCurrentLocale();
    }


    /**
     * @param string $name
     * @return Grid
     * @throws \Ublaboo\DataGrid\Exception\DataGridException
     */
    public function createComponentGrid(string $name): Grid
    {
        /** @var Grid $grid */
        $grid = $this->baseGridFactory->create($this, $name);

        $grid->setDataSource($this->settingsRepository->getSettingsQueryBuilder());

        $grid->setDefaultSort(['createdAt' => 'ASC']);
        $grid->addColumnText('identifier', 'Identifier')
            ->setFilterText();

        $grid->addColumnDateTime('createdAt', 'Created')
            ->setFormat($this->currentLocale->getDateTimeFormat())
            ->setAlign('center')
            ->setSortable()
            ->setFilterDate();

        $grid->addColumnDateTime('updatedAt', 'Last edit')
            ->setFormat($this->currentLocale->getDateTimeFormat())
            ->setAlign('center')
            ->setSortable()
            ->setFilterDate();

        $grid->addColumnBoolean('isActive', 'Active');

        if ($this->user->isAllowed('cookieConsent', 'edit'))
        {
            $grid->addAction('edit', '')
                ->setIcon('pencil')
                ->setTitle('Upravit')
                ->setClass('btn btn-xs btn-primary');
        }

        if ($this->user->isAllowed('cookieConsent', 'delete'))
        {
            $grid->addAction('delete', '', 'delete!')
                ->setIcon('trash')
                ->setTitle('Smazat')
                ->setClass('btn btn-xs btn-danger ajax')
                ->setConfirmation(new StringConfirmation('Do you really want to delete row %s?', 'identifier'));

            $grid->addGroupAction('Smazat')->onSelect[] = [$this, 'handleDelete'];
        }

        $grid->addExportCsvFiltered('Csv export (filtered)', 'acl_resource_filtered.csv')
            ->setTitle('Csv export (filtered)');

        $grid->addExportCsv('Csv export', 'acl_resource_all.csv')
            ->setTitle('Csv export');

        return $grid;
    }

    /**
     * @param $id
     * @throws \Exception
     * @isAllowed(settings, delete)
     */
    public function handleDelete($id): void
    {
        $rows = $this->settingsRepository->getById($id);
        foreach ($rows AS $settings)
        {
            $this->entityManager->remove($settings);
        }

        $this->entityManager->flush();

        $this->onDelete();
    }

    public function render(): void
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/SettingsGrid.latte');
        $template->render();
    }
}
