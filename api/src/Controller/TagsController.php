<?php

namespace App\Controller;

use App\Message\RapportNotification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\MessageBusInterface;

use App\Manager\TagsManager;
use App\Entity\Order;

class TagsController extends AbstractController
{
    private $tagsManager;

    public function __construct(TagsManager $tagsManager)
    {
        $this->tagsManager = $tagsManager;
    }


    public function __invoke(Order $data, MessageBusInterface $bus): Order
    {
        //Generate Tags
        $this->tagsManager->addTags($data);

        //Send Rapport via message
        $tags = explode(',', $data->getTags());

        if (in_array(TagsManager::HAS_ISSUES, $tags)) {
            $rapportMessage = array(
                                'order_id' => $data->getId(),
                                'tags' => $data->getTags()
                            );
            
            $bus->dispatch(new RapportNotification(json_encode($rapportMessage)));
        }

        return $data;
    }
}