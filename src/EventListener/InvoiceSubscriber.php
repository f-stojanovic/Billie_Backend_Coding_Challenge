<?php

namespace App\EventListener;

use App\Entity\Invoice;
use App\Service\InvoiceService;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;

class InvoiceSubscriber implements EventSubscriberInterface
{
    const CREATED = 'created';

    /**
     * @var InvoiceService
     */
    private $invoiceService;

    /**
     * @param InvoiceService $invoiceService
     */
    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist
        ];
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->handle('persist', $args);
    }

    /**
     * @param string $action
     * @param LifecycleEventArgs $args
     */
    public function handle(string $action, LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof Invoice) {
            $this->invoiceService->generateInvoiceNo(
                $entity
            );
        }
    }
}
