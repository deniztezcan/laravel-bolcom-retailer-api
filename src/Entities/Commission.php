<?php

namespace DenizTezcan\BolRetailerV3\Entities;

use DenizTezcan\BolRetailerV3\Models\Commission as CommissionModel;
use DenizTezcan\BolRetailerV3\Models\Commissions;
use DenizTezcan\BolRetailerV3\Support\Serialize;

class Commission extends Entity
{
    public function getCommissions(array $commissionQueries): Commissions
    {
        $response = $this->client->authenticatedRequest('POST', 'commission', [
            'commissionQueries' => $commissionQueries,
        ]);
        $deserialized = Serialize::deserialize((string) $response->getBody());

        return Commissions::fromResponse($deserialized);
    }

    public function getCommission(string $ean): CommissionModel
    {
        $response = $this->client->authenticatedRequest('GET', 'commission/'.$ean);
        $deserialized = Serialize::deserialize((string) $response->getBody());

        return CommissionModel::fromResponse($deserialized);
    }
}
