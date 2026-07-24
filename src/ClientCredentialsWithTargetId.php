<?php

declare(strict_types=1);

namespace Compwright\OAuth2\FieldEdge;

use League\OAuth2\Client\Grant\ClientCredentials;

class ClientCredentialsWithTargetId extends ClientCredentials
{
    /**
     * @return string[]
     */
    protected function getRequiredRequestParameters()
    {
        return [
            ...parent::getRequiredRequestParameters(),
            'target_id',
        ];
    }
}
