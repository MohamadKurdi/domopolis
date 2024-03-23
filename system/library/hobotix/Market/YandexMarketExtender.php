<?php
    
    namespace Yandex\Marketplace\Partner\Clients;
    
    use Yandex\Marketplace\Partner\Models\Response\GetDeliveryLabelsDataResponse;
    use Yandex\Marketplace\Partner\Models\Response\GetUpdateOrderResponse;
    use Yandex\Marketplace\Partner\Models\Response\GetInfoOrderBoxesResponse;
    use Yandex\Marketplace\Partner\Models\Response\GetOrdersResponse;
    use Yandex\Marketplace\Partner\Models\Response\GetOrderResponse;
    use Yandex\Marketplace\Partner\Models\Response\GetDeliveryServiceResponse;
    use Yandex\Marketplace\Partner\Models\Response\PostResponse;
    use Yandex\Marketplace\Partner\Models\Response\UpdateOrdersStatusesResponse;
    
    use Yandex\Marketplace\Partner\Models\Response\ShipmentsResponse;
    use Yandex\Marketplace\Partner\Models\Response\GetShipmentsResponse;
    use Yandex\Marketplace\Partner\Models\Response\GetShipmentResponse;
    use Yandex\Marketplace\Partner\Models\Response\GetShipmentItemsResponse;
    
    class HobotixYamClient extends Client
    {
        
        private $headers = [
        'Yandex-Market-Php-Version' => 'yandex.market php library 1.1.0'
        ];
        
        public function getHiddenOffers ($campaignId, array $params = [])
        {
            $resource = 'campaigns/' . $campaignId . '/hidden-offers.json';
            
            $response = $this->sendRequest(
            'GET',
            $this->getServiceUrl($resource),
            ['json' => $params]
            );               
            
            $decodedResponseBody = $this->getDecodedBody($response->getBody());
            
            $postResponse = new PostResponse($decodedResponseBody);
            
            return $postResponse->getResult();
        }
        
        public function getRecommendedPrices ($campaignId, array $params = [])
        {
            $resource = 'campaigns/' . $campaignId . '/offer-prices/suggestions.json';
            
            $response = $this->sendRequest(
            'POST',
            $this->getServiceUrl($resource),
            ['json' => $params]
            );
            
            $decodedResponseBody = $this->getDecodedBody($response->getBody());
            
            $postResponse = new PostResponse($decodedResponseBody);
            
            return $postResponse->getResult();
        }
        
        public function getReceptionTransferAct($campaignId)
        {
            $resource = 'campaigns/' . $campaignId . '/shipments/reception-transfer-act';	
            
            $response = $this->sendRequest('GET', $this->getServiceUrl($resource));
            $header = $response->getHeader("Content-Type");
            if ($header[0] == "application/pdf") {
                return $response->getBody()->getContents();
            }
            
            $decodedResponseBody = $this->getDecodedBody($response->getBody());
            
            return new PostResponse($decodedResponseBody);
        }
        
        public function setShipmentAsFullFilled($campaignId, $shipment_id, array $params = []){
            
            $resource = 'campaigns/' . $campaignId . '/first-mile/shipments/' . $shipment_id . '/confirm.json';	        
            $response = $this->sendRequest('POST', $this->getServiceUrl($resource), ['json' => $params]);
            
            $decodedResponseBody = $this->getDecodedBody($response->getBody());
            
        }
        
        
        /**
            * Sends a request
            *
            * @param string $method  HTTP method
            * @param string $uri     URI object or string.
            * @param array  $options Request options to apply.
            *
            * @return \Psr\Http\Message\ResponseInterface
            *
            * @throws ForbiddenException
            * @throws UnauthorizedException
            * @throws PartnerRequestException
            * @throws \GuzzleHttp\Exception\GuzzleException
        */
        protected function sendUnexceptionedRequest($method, $uri, array $options = [])
        {
            try {
                $response = $this->getClient($this->headers)->request($method, $uri, $options);
                } catch (ClientException $ex) {
                $result = $ex->getResponse();
                
                $code = $result->getStatusCode();
                $message = $result->getReasonPhrase();
                $body = $result->getBody();
                
                if ($body) {
                    $jsonBody = json_decode($body);
                    
                    if (isset($jsonBody->error->message) && $jsonBody) {
                        $message = $jsonBody->error->message;
                    }
                }
                
                if ($code === 403) {
                    throw new ForbiddenException($message);
                }
                
                if ($code === 401) {
                    throw new UnauthorizedException($message);
                }
                
                throw new PartnerRequestException(
                'Service responded with error code: "' . $code . '" and message: "' . $message . '"',
                $code
                );
            }
            
            return $response;
        }
        
        
    }                    