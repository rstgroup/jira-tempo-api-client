<?php

namespace JiraTempoApi\Domain\Model;

class UserWorklogs
{
    /** @var array */
    private $results;

    private $issues;

    /** @return array */
    public function getResults()
    {
        return $this->results;
    }

    public function getIssues()
    {
        if($this->issues !== null || $this->issues === []){
            return $this->issues;
        }

        $addedIssues = [];
        $filteredResults = array_filter(
            $this->results,
            function ($result) use (&$addedIssues) {
                $result = (object) $result;
                if (isset($result->issue) && !isset($addedIssues[$result->issue->key])) {
                    $addedIssues[$result->issue->key] = true;
                    return true;
                };

                return false;
            }
        );

        $this->issues = array_map(
            function ($result) {
                $result = (object) $result;
                return Issue::create($result->issue);
            },
            $filteredResults
        );

        return $this->issues;
    }
}