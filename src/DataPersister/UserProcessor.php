<?php
namespace App\DataPersister;

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Metadata\Operation;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserProcessor implements ProcessorInterface
{
    private UserPasswordEncoderInterface $userPasswordEncoder;
    private ProcessorInterface $persistProcessor;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder, ProcessorInterface $persistProcessor)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->persistProcessor = $persistProcessor;
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
    }}
