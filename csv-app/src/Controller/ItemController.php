<?php

namespace App\Controller;

use App\Entity\Item;
use App\Service\ColourService;
use App\Service\ItemService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/items')]
class ItemController extends AbstractController
{

    public function __construct(
        private ItemService $itemService,
        private ColourService $colourService,
        private LoggerInterface $logger,
        private EntityManagerInterface $entityManager,
        private ManagerRegistry $managerRegistry
    ) { }

    #[Route('', name: 'add_items', methods: ['POST'])]
    public function addItems(Request $request) : Response
    {
        $file = $request->files->get('file');

        if (!$file) {
            $this->logger->error('Request is missing a file');
            return new Response('Request is missing a file', 400);
        } else $this->logger->info('File received');

        $saved = 0;
        $batchSize = 1000;

        if (($handle = fopen($file->getPathname(), "r")) !== FALSE)
        {
            //column header skip
            $data = fgetcsv($handle, 5000, ",");
            while (($data = fgetcsv($handle, 5000, ",")) !== FALSE)
            {

                $colour = $this->colourService->addColour(trim($data[3]));
                $item = $this->itemService->addItem($data, $colour);

                if ($item != null) $saved++;

                if (!$this->entityManager->isOpen()) $this->managerRegistry->resetManager();

                if ($saved % $batchSize == 0)
                {
                    $this->entityManager->flush();
                    $this->entityManager->clear();
                }
            }

            $this->entityManager->flush();
            $this->entityManager->clear();

        }

        $this->logger->info($saved . ' items saved');

        return new Response($saved . ' items saved', 201);
    }

    #[Route('', name: 'get_items', methods: ['GET'])]
    public function getItems() : JsonResponse
    {
        $itemRepository = $this->entityManager->getRepository(Item::class);

        $items = $itemRepository->findWithColours();

        return new JsonResponse($items, 200);
    }

}