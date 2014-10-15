<?php
class AjaxGateway
{
    /**
     * @var SimpleRestClient
     */
    protected $client;
    protected $url;
    protected $username;
    protected $pass;

    public function __construct($url)
    {
		global $OPTION;
        $this->client   =  new SimpleRestClient();
        $this->username = $OPTION['wps_emarsys_username'];
        $this->pass     = $OPTION['wps_emarsys_password'];
        $this->url      = $OPTION['wps_emarsys_url'].$url;


        $nonce = md5(rand());
        $nonce_ts = date('c');
        $password_digest = base64_encode(sha1($nonce . $nonce_ts .$this->pass));

        $this->client->setOption(CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "X-WSSE: UsernameToken Username=\"{$this->username}\""
            . ", PasswordDigest=\"{$password_digest}\""
            . ", Nonce=\"{$nonce}\""
            . ", Created=\"{$nonce_ts}\""
        ));
    }



    protected function postData($data)
    {
        $this->client->postWebRequest($this->url, $data);
    }

    protected function putData($data)
    {
        $this->client->postWebRequest($this->url, $data, 'put');
    }

    protected function deleteData($data)
    {
        $this->client->postWebRequest($this->url, $data, 'delete');
    }

    protected function getRequest()
    {
        $this->client->getWebRequest($this->url);
    }

    public function getResponse($aData, $method)
    {
        switch ($method)
        {
            case "POST":
                $this->postData($aData);
                break;

            case "PUT":
                $this->putData($aData);
                break;

            case "DELETE":
                $this->deleteData($aData);
                break;

            default:
                $this->getRequest($aData);
                break;
        }
        $response = $this->client->getWebResponse();

        $parts = explode("\r\n\r\n", $response);
        $body = array_pop($parts);
        $headers = array_pop($parts);
        $header = explode("\r\n", $headers, 2);
        $header = array_shift($header);
        header($header, true, $this->client->getStatusCode());

        return $body;
    }

}