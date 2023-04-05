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
use Symfony\Component\Security\Core\Security;

class UserProcessor implements ProcessorInterface
{
    private UserPasswordEncoderInterface $userPasswordEncoder;
    private ProcessorInterface $persistProcessor;
    private LoggerInterface $logger;
    private Security $security;

    public function __construct(
        UserPasswordEncoderInterface $userPasswordEncoder,
        ProcessorInterface $persistProcessor,
        LoggerInterface $logger,
        Security $security
    )
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->persistProcessor = $persistProcessor;
        $this->logger = $logger;
        $this->security = $security;
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

        // now handled in a listener
//        $data->setIsMe($this->security->getUser() === $data);

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
