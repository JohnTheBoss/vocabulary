<?php

namespace App\Validator\Rules;

use App\Adapter\RepositoryAdapter\EntityRepositoryInterface;

class UniqueValidatorRule extends AbstractValidatorRule
{

    function validate($data)
    {
        /** @var $entityRepository EntityRepositoryInterface */
        $entityRepository = $this->config['entityRepository'];
        $field = $this->config['entityField'];

        $filter = [$field => $data];

        if (isset($this->config['filter'])) {
            $filter[$this->config['filter']] = $this->config['filterValue'];
        }

        $find = $entityRepository->findOneBy($filter);
        if (!empty($find)) {
            $this->addError('Az értéknek egyedinek kell lennie!');
        }
    }
}
