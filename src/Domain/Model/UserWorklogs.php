<?php

namespace JiraTempoApi\Domain\Model;

class UserWorklogs
{
    /** @var array */
    private $results;

    /** @return array */
    public function getResults()
    {
        return $this->results;
    }

    public function getIssues()
    {
        $added_issues = [];
        $filteredResults = array_filter(
            $this->results,
            function ($result) use (&$added_issues) {
                $result = (object) $result;
                if (isset($result->issue) && !isset($added_issues[$result->issue->key])) {
                    $added_issues[$result->issue->key] = true;
                    return true;
                };

                return false;
            }
        );

        return array_map(
            function ($result) {
                $result = (object) $result;
                return Issue::create($result->issue);
            },
            $filteredResults
        );
    }
}