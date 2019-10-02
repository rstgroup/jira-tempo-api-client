<?php

namespace JiraTempoApi\Domain\Model;

class UserWorklogs
{
    /** @var array */
    private $results;

    private $issues;

    private $worklogs;

    /** @return array */
    public function getResults()
    {
        return $this->results;
    }

    /** @return UserWorklog[] */
    public function getWorklogs()
    {
        if($this->worklogs !== null || $this->issues === []){
            return $this->worklogs;
        }

        $this->worklogs = array_map(
            function ($result) use (&$addedIssues) {
                $result = (object) $result;
                $key = $result->issue->key;
                $description = $result->description;
                $time = $result->billableSeconds ?: $result->timeSpentSeconds;
                return new UserWorklog($key, $description, $time);
            },
            $this->results
        );

        return $this->worklogs;
    }

    public function getGroupedWorklogsByIssues()
    {
        $groupedWorklogs = [];
        $worklogs = $this->getWorklogs();
        $issues = $this->getIssues();

        foreach ($issues as $issue) {
            if (isset($groupedWorklogs[$issue->getKey()])) {
                continue;
            }

            $issueWorklogs = [];
            foreach ($worklogs as $worklog) {
                if ($worklog->getIssue() === $issue->getKey()) {
                    $issueWorklogs[] = $worklog;
                }
            }

            $groupedWorklogs[$issue->getKey()] = $issueWorklogs;
        }

        return $groupedWorklogs;
    }

    /** @return Issue[] */
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
                $key = $result->issue->key;
                if (isset($result->issue) && !isset($addedIssues[$key])) {
                    $addedIssues[$key] = true;
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