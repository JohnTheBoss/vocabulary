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
            $filter = array_merge($filter, $this->config['filter']);
        }

        $find = $entityRepository->findOneBy($filter);
        if (!empty($find)) {
            $field = $this->config['filterSkipField'];
            if ($find->{$field}() == $this->config['filterSkipValue']) {
                return;
            }

            $this->addError('Az értéknek egyedinek kell lennie!');
        }
    }
}
