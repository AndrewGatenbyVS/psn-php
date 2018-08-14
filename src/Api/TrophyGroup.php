<?php

namespace PlayStation\Api;

use PlayStation\Client;
use PlayStation\Api\User;

class TrophyGroup extends AbstractApi 
{

    private $group;
    private $trophySet;

    public function __construct(Client $client, object $group, TrophySet $trophySet) 
    {
        parent::__construct($client);

        $this->group = $group;
        $this->trophySet = $trophySet;
    }

    public function id()
    {
        return $this->group->trophyGroupId;
    }

    public function name() : string
    {
        return $this->group->trophyGroupName;
    }

    public function detail() : string
    {
        return $this->group->trophyGroupDetail;
    }

    public function iconUrl() : string
    {
        return $this->group->trophyGroupIconUrl;
    }

    public function trophyCount() : int 
    {
        return Trophy::calculateTrophies($this->group->definedTrophies);
    }

    /**
     * Get last TrophyGroup earned DateTIme.
     *
     * @return \DateTime
     */
    public function lastUpdateDate() : \DateTime 
    {
        return new \DateTime($this->group->lastUpdateDate);
    }


    public function trophies() : array 
    {
        $returnTrophies = [];

        $trophies = $this->get(sprintf(Trophy::TROPHY_ENDPOINT . 'trophyTitles/%s/trophyGroups/%s/trophies', $this->trophySet->game()->communicationId(), $this->id()), [
            'fields' => '@default,trophyRare,trophyEarnedRate,hasTrophyGroups,trophySmallIconUrl',
            'iconSize' => 'm',
            'visibleType' => 1,
            'npLanguage' => 'en'
        ]);

        foreach ($trophies->trophies as $trophy) {
            $returnTrophies[] = new Trophy($this->client, $trophy, $this);
        }

        return $returnTrophies;
    }
}