<?php

namespace JiraTempoApi\Repositories\RestApi\V3\JiraApi;

use JiraTempoApi\HttpClient\Formatter\QueryParametersFormatter;
use JiraTempoApi\Repositories\Base\JiraApiRepository;

class MyPermissions extends JiraApiRepository
{
    protected $basePath = '/mypermissions';


    public function getMyPermissions($permissions = [])
    {
        $response = $this->jiraApiClient->exec(
            sprintf(
                '%s%s',
                $this->basePath,
                QueryParametersFormatter::toHttpQueryParameter(['permissions' => $permissions])
            )
        );

        $this->jiraApiClient->getLog()->info(
            sprintf("[REST PATH: %s] Result=\n%s",$this->basePath, $response)
        );

        return $this->json->decode($response);
    }
}