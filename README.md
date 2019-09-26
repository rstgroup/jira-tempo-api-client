# JiraTempoApiClient
#### Work in progress...


## Requirements:

- php:5.6+

## Dependencies:
- vlucas/phpdotenv
- lesstif/php-jira-rest-client

## Installation
```bash
composer require rstgroup/jira-tempo-api-client 
```

## Configuration:

1. Create file `.env`
2. Set variables:

```.dotenv
# Required 
JIRA_HOST="https://your-jira.host.com"
JIRA_USER="jira-username"
JIRA_PASS="jira-password-OR-api-token"
JIRA_REST_API_V3=true
TEMPO_TOKEN="your-tempo-access-token"
```
