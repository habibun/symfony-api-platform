<?php
namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\ProductNotification;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProductProcessor implements ProcessorInterface
{
    private ProcessorInterface $persistProcessor;
    private EntityManagerInterface $entityManager;

    public function __construct(
        ProcessorInterface     $persistProcessor,
        EntityManagerInterface $entityManager,
    )
    {
        $this->persistProcessor = $persistProcessor;
        $this->entityManager = $entityManager;
    }

    /**
     * @param $data
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     *
     * @return mixed
     */
    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $originalData = $this->entityManager->getUnitOfWork()->getOriginalEntityData($data);
        $wasAlreadyPublished = ($originalData['isPublished'] ?? false);
        if ($data->getIsPublished() &&  !$wasAlreadyPublished) {
            $notification = new ProductNotification($data, 'Product listing was created!');
            $this->entityManager->persist($notification);
            $this->entityManager->flush();
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
