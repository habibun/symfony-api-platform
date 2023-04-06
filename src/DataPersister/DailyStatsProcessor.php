<?php
namespace App\DataPersister;

use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class DailyStatsProcessor implements ProcessorInterface
{
    private ProcessorInterface $persistProcessor;
    private LoggerInterface $logger;
    private Security $security;

    public function __construct(
        ProcessorInterface $persistProcessor,
        LoggerInterface $logger,
        Security $security
    )
    {
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
        if (($context['item_operation_name'] ?? null) === 'put') {
            $this->logger->info(sprintf('Update the visitors to "%d"', $data->totalVisitors));
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
