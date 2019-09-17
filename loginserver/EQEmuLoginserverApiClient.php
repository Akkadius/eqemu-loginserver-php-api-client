<?php

/**
 * Class LoginserverClient
 */
class EQEmuLoginserverApiClient
{
    const ACCOUNT_CREATE_ENDPOINT                        = '/account/create';
    const ACCOUNT_CREATE_EXTERNAL_ENDPOINT               = '/account/create/external';
    const ACCOUNT_CREDENTIALS_VALIDATE_LOCAL_ENDPOINT    = '/account/credentials/validate/local';
    const ACCOUNT_CREDENTIALS_VALIDATE_EXTERNAL_ENDPOINT = '/account/credentials/validate/external';
    const ACCOUNT_CREDENTIALS_UPDATE_LOCAL_ENDPOINT      = '/account/credentials/update/local';
    const ACCOUNT_CREDENTIALS_UPDATE_EXTERNAL_ENDPOINT   = '/account/credentials/update/external';
    const SERVERS_LIST_ENDPOINT                          = '/servers/list';

    /**
     * @var string
     */
    public $api_token;

    /**
     * @var string
     */
    public $api_base_url;

    /**
     * @param        $username
     * @param        $password
     * @param string $email
     *
     * @return array
     */
    public function createLoginserverAccount($username, $password, $email = "")
    {
        return $this->sendRequest(
            self::ACCOUNT_CREATE_ENDPOINT,
            'POST',
            [
                'username' => $username,
                'password' => $password,
                'email'    => $email,
            ]
        );
    }

    /**
     * @param $username
     * @param $password
     * @param $login_account_id
     *
     * @return array
     */
    public function createExternalLoginserverAccount($username, $password, $login_account_id)
    {
        return $this->sendRequest(
            self::ACCOUNT_CREATE_EXTERNAL_ENDPOINT,
            'POST',
            [
                'username'         => $username,
                'password'         => $password,
                'login_account_id' => $login_account_id,
            ]
        );
    }

    /**
     * @param $username
     * @param $password
     *
     * @return array
     */
    public function checkLocalAccountCredentialsValid($username, $password)
    {
        return $this->sendRequest(
            self::ACCOUNT_CREDENTIALS_VALIDATE_LOCAL_ENDPOINT,
            'POST',
            [
                'username' => $username,
                'password' => $password,
            ]
        );
    }

    /**
     * @param $username
     * @param $password
     *
     * @return array
     */
    public function checkExternalAccountCredentialsValid($username, $password)
    {
        return $this->sendRequest(
            self::ACCOUNT_CREDENTIALS_VALIDATE_EXTERNAL_ENDPOINT,
            'POST',
            [
                'username' => $username,
                'password' => $password,
            ]
        );
    }

    /**
     * @param $username
     * @param $password
     *
     * @return array
     */
    public function updateLocalAccountCredentials($username, $password)
    {
        return $this->sendRequest(
            self::ACCOUNT_CREDENTIALS_UPDATE_LOCAL_ENDPOINT,
            'POST',
            [
                'username' => $username,
                'password' => $password,
            ]
        );
    }

    /**
     * @param $username
     * @param $password
     *
     * @return array
     */
    public function updateExternalAccountCredentials($username, $password)
    {
        return $this->sendRequest(
            self::ACCOUNT_CREDENTIALS_UPDATE_EXTERNAL_ENDPOINT,
            'POST',
            [
                'username' => $username,
                'password' => $password,
            ]
        );
    }

    /**
     * Gets servers connected to Loginserver
     *
     * @return array
     */
    public function getServersList()
    {
        return $this->sendRequest(self::SERVERS_LIST_ENDPOINT);
    }

    /**
     * @param $endpoint
     * @param $request_type
     * @param $payload
     *
     * @return array
     */
    private function sendRequest($endpoint, $request_type = 'GET', $payload = [])
    {
        $curl         = curl_init($this->getApiBaseUrl() . '/v1' . $endpoint);
        $json_payload = json_encode($payload);

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $request_type);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_payload);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, "eqemu-loginserver-php-api-client");
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($json_payload),
                'Authorization: Bearer ' . $this->getApiToken(),
            ]
        );
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);

        $result = curl_exec($curl);
        curl_close($curl);

        return (array)json_decode($result, true);
    }

    /**
     * @return mixed
     */
    public function getApiBaseUrl()
    {
        return $this->api_base_url;
    }

    /**
     * @param mixed $api_base_url
     *
     * @return EQEmuLoginserverApiClient
     */
    public function setApiBaseUrl($api_base_url)
    {
        $this->api_base_url = $api_base_url;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getApiToken()
    {
        return $this->api_token;
    }

    /**
     * @param mixed $api_token
     *
     * @return EQEmuLoginserverApiClient
     */
    public function setApiToken($api_token)
    {
        $this->api_token = $api_token;

        return $this;
    }
}
