<?php
namespace App\Helpers;

use Symfony\Component\HttpFoundation\Response;

class CurlRequest{
    public function __construct(string $baseURI, string $methodName = 'GET',array $parameters = [], array $headers = [])
    {
        $this->data = [];
        $this->curl = curl_init();
        $this->baseURI = $baseURI;
        $parameters[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_1;
        $parameters[CURLOPT_CUSTOMREQUEST] = $methodName;
        $parameters[CURLOPT_URL] = $baseURI;
        $parameters[CURLOPT_HEADER] = true;
        $parameters[CURLOPT_RETURNTRANSFER] = true;
        $parameters[CURLOPT_SSL_VERIFYPEER] = false;
        $parameters[CURLOPT_FOLLOWLOCATION] = true;
        $parameters[CURLOPT_AUTOREFERER] = true;
        $parameters[CURLOPT_HTTPHEADER] = $headers;
        $this->parameters = $parameters;
        curl_setopt_array($this->curl,
            $parameters
        );
    }

    public function setHeaders(array $headers): self
    {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
        $this->parameters[CURLOPT_HTTPHEADER] = $headers;
        return $this;
    }

    public function getHeaders(): array
    {
        return $this->parameters[CURLOPT_HTTPHEADER];
    }

    public function getMethodName(): array
    {
        return $this->parameters[CURLOPT_CUSTOMREQUEST];
    }

    public function setMethodName(string $methodName): self
    {
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $methodName);
        $this->parameters[CURLOPT_CUSTOMREQUEST] = $methodName;
        return $this;
    }

    public function getBaseURI(): string
    {
        return $this->baseURI;
    }

    public function setBaseURI(string $baseURI): self
    {
        $this->baseURI = $baseURI;
        curl_setopt($this->curl, CURLOPT_URL, $baseURI);
        $this->parameters[CURLOPT_URL] = $baseURI;
        return $this;
    }

    private function getResponseArray(): array 
    {
        $responseArray = [
            'url' => curl_getinfo($this->curl, CURLINFO_EFFECTIVE_URL),
            'http_code' => curl_getinfo($this->curl, CURLINFO_RESPONSE_CODE),
            'http_version' => curl_getinfo($this->curl, CURLINFO_HTTP_VERSION  ),
            'content_type' => curl_getinfo($this->curl, CURLINFO_CONTENT_TYPE ),
        ];
        return $responseArray;
    }

    private function getResponseContent(string $response): string
    {
        $start = strpos($response, "\r\n\r\n") + 4;
        $html = substr($response, $start, strlen($response) - $start);
        return $html;
    }

    public function execRequest(): string
    {
        $response = curl_exec($this->curl);
        $err = curl_error($this->curl);
        
        $this->data = $this->getResponseArray();
        $this->data['content'] = $this->getResponseContent($response);
        if ($err) {
            $jsonData = json_encode($err, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            return $jsonData;
        } else {
            $jsonData = json_encode($this->data, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            return $jsonData;
        }
    }
}
?>