<?php

namespace Yandex\Marketplace\Partner\Clients;

use GuzzleHttp\Exception\GuzzleException;
use Yandex\Common\Exception\ForbiddenException;
use Yandex\Common\Exception\UnauthorizedException;
use Yandex\Marketplace\Partner\Models\Response\PostResponse;
use Yandex\Marketplace\Partner\Models\Response\StocksResponse;

class HobotixStocksClient extends StocksClient 
{

	 public function updateStocks($campaignId, array $params = [], $dbgKey = null)
    {
        $resource = 'campaigns/' . $campaignId . '/offers/stocks.json';
        $resource = $this->addDebugKey($resource, $dbgKey);
        $response = $this->sendRequest(
            'PUT',
            $this->getServiceUrl($resource),
            ['json' => $params]
        );
		
        $decodedResponseBody = $this->getDecodedBody($response->getBody());

        return new PostResponse($decodedResponseBody);
    }

}