<?php

namespace App\Service;

use App\Entity\Colour;
use App\Entity\Item;
use App\Exceptions\IncorrectCSVCellFormatException;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ItemService
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
        private ManagerRegistry $managerRegistry,
        private ValidatorInterface $validator
    ) { }

    public function addItem(array $data, Colour $colour) : ?Item
    {
        $itemRepository = $this->entityManager->getRepository(Item::class);

        $item = $itemRepository->findOneBy(['code' => trim($data[0])]);

        if ($item != null) {
            $this->logger->alert('Item with code ' . trim($data[0]) . ' already exists');

            return $item;
        }

        try
        {
            if (!preg_match("/^[0-9.]+$/", trim($data[2])))
                throw new IncorrectCSVCellFormatException('Incorrect Price format');
            if (!preg_match("/^[0-9.]+$/", trim($data[6])))
                throw new IncorrectCSVCellFormatException('Incorrect Weight(kg) format');
            if (!preg_match("/^[0-9]+$/", trim($data[1])))
                throw new IncorrectCSVCellFormatException('Incorrect Carton_Qty format');
            if (!preg_match("/^[0-9]+$/", trim($data[4])))
                throw new IncorrectCSVCellFormatException('Incorrect Size format');
        } catch (IncorrectCSVCellFormatException $e)
        {
            $this->logger->error('An error occurred while creating Item with code ' . trim($data[0]) . ' : ' . $e->getMessage());

            return null;
        }

        $code = trim($data[0]);
        $ean13 = trim($data[7]);
        $dun14 = trim($data[8]);
        $cartonQty = intval(trim($data[1]));
        $price = floatval(trim($data[2]));
        $size = intval(trim($data[4]));
        $desc = trim($data[5]);
        $weight = floatval(trim($data[6]));
        $imagePath = trim($data[9]);

        $item = new Item();
        $item->setCode($code);
        $item->setEan13($ean13);
        $item->setDun14($dun14);
        $item->setCartonQty($cartonQty);
        $item->setPrice($price);
        $item->setWeight($weight);
        $item->setImagePath($imagePath);
        $item->setSize($size);
        $item->setDescription($desc);
        $item->setColour($colour);

        $errors = $this->validator->validate($item);
        if ($errors->count() > 0)
        {
            foreach ($errors as $error)
                $this->logger->error('An error occurred during the validation of Item with code ' . trim($data[0]) . ' : ' . $error->getMessage());

            return null;
        }

        try {

            if (!$this->entityManager->isOpen()) $this->managerRegistry->resetManager();

            $this->entityManager->persist($item);
            $this->entityManager->flush();

            $this->logger->info('Created item ' . trim($data[0]));

            return $item;
        } catch (\Exception $e)
        {
            $this->logger->error('An error occurred while creating Item with code ' . trim($data[0]) . ' : ' . $e->getMessage());

            return null;
        }
    }

}