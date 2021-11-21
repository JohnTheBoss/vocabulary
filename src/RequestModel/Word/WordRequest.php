<?php

namespace App\RequestModel\Word;

use App\Adapter\RepositoryAdapter\WordRepositoryAdapter;
use App\Adapter\RequestAdapterInterface;
use App\RequestModel\AbstractRequest;
use App\Validator\InputValidatorBuilder;
use App\Validator\Validator;

class WordRequest extends AbstractRequest
{
    private $validatorBuilder;

    public ?string $knownLanguage;
    public ?string $foreignLanguage;

    private WordRepositoryAdapter $wordRepositoryAdapter;
    private int $dictionaryId;

    public function __construct(RequestAdapterInterface $request, InputValidatorBuilder $validatorBuilder, WordRepositoryAdapter $wordRepositoryAdapter)
    {
        $this->validatorBuilder = $validatorBuilder;
        $this->wordRepositoryAdapter = $wordRepositoryAdapter;
        $data = $request->getJsonRequestData();

        $this->dictionaryId = $request->getParameters()['dictionaryId'];

        $this->knownLanguage = $this->getData($data, 'knownLanguage');
        $this->foreignLanguage = $this->getData($data, 'foreignLanguage');
    }

    public function getValidator(): Validator
    {
        $config['filter'] = 'dictionary';
        $config['filterValue'] = $this->dictionaryId;

        $this->validatorBuilder->addUniqueInput('knownLanguage', 'dictionaryWordUnique', $this->knownLanguage, $this->wordRepositoryAdapter, 'knownLanguage', $config);
        $this->validatorBuilder->addUniqueInput('foreignLanguage', 'dictionaryWordUnique', $this->foreignLanguage, $this->wordRepositoryAdapter, 'foreignLanguage', $config);

        return $this->validatorBuilder->getValidator();
    }
}
