<?php
namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserProcessor implements DataPersisterInterface
{
    private UserPasswordEncoderInterface $userPasswordEncoder;
    private ProcessorInterface $persistProcessor;
    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;

    public function __construct(
        UserPasswordEncoderInterface $userPasswordEncoder,
        ProcessorInterface $persistProcessor,
        LoggerInterface $logger,
        EntityManagerInterface $entityManager,
    )
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->persistProcessor = $persistProcessor;
        $this->logger = $logger;
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
        if ($data->getPlainPassword()) {
            $data->setPassword(
                $this->userPasswordEncoder->encodePassword($data, $data->getPlainPassword())
            );
            $data->eraseCredentials();
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }

    /**
     * @param $data
     * @param array $context
     *
     * @return bool
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    /**
     * @param $data
     * @param array $context
     *
     * @return object|void
     */
    public function persist($data, array $context = [])
    {
        if (!$data->getId()) {
            // take any actions needed for a new user
            // send registration email
            // integrate into some CRM or payment system
            $this->logger->info(sprintf('User %s just registered! Eureka!', $data->getEmail()));
        }

        if (($context['item_operation_name'] ?? null) === 'put') {
            $this->logger->info(sprintf('User "%s" is being updated!', $data->getId()));
        }

        if ($data->getPlainPassword()) {
            $data->setPassword(
                $this->userPasswordEncoder->encodePassword($data, $data->getPlainPassword())
            );
            $data->eraseCredentials();
        }

        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    /**
     * @param $data
     * @param array $context
     *
     * @return mixed
     */
    public function remove($data, array $context = [])
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}
