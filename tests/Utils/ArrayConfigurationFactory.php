<?php

namespace Tests\Utils;

use JiraRestApi\Configuration\ArrayConfiguration;

class ArrayConfigurationFactory
{
    public static function create(
        $host = null,
        $user = null,
        $password = null
    ) {
        return new ArrayConfiguration([
            'jiraHost' => $host ?: getenv('JIRA_HOST'),
            'jiraLogFile' => './../../var/logs/test.log',
            'jiraUser' => $user ?: getenv('JIRA_USER'),
            'jiraPassword' => $password ?: getenv('JIRA_PASS'),
            'useV3RestApi' => true
        ]);
    }
}