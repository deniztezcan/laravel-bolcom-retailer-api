<?php

namespace DenizTezcan\BolRetailer\Entities;

use DenizTezcan\BolRetailer\Models\Commission as CommissionModel;
use DenizTezcan\BolRetailer\Models\Commissions;
use DenizTezcan\BolRetailer\Support\Serialize;

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
