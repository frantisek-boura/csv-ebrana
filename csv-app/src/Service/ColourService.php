<?php

namespace App\Service;

use App\Entity\Colour;
use App\Repository\ColourRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

class ColourService
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
        private ManagerRegistry $managerRegistry
    ) { }

    public function addColour(string $colourName) : ?Colour
    {
        $colourRepository = $this->entityManager->getRepository(Colour::class);

        $colour = $colourRepository->findOneBy(['name' => $colourName]);

        if ($colour != null)
        {
            $this->logger->alert('Colour with name ' . $colourName . ' already exists');

            return $colour;
        }

        $colour = new Colour();
        $colour->setName($colourName);

        try
        {
            if (!$this->entityManager->isOpen()) $this->managerRegistry->resetManager();

            $this->entityManager->persist($colour);
            $this->entityManager->flush();

            $this->logger->info('Created colour ' . $colourName);

            return $colour;
        } catch (\Exception $e)
        {
            $this->logger->error('An error occurred while creating colour with name ' . $colourName . ' : ' . $e->getMessage());

            return null;
        }

    }

}