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

        $find = $entityRepository->findOneBy([$field => $data]);
        if (!empty($find)) {
            $this->addError('Az értéknek egyedinek kell lennie!');
        }
    }
}
