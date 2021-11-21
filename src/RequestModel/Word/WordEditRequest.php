<?php

namespace App\RequestModel\Word;

use App\Adapter\RepositoryAdapter\WordRepositoryAdapter;
use App\Adapter\RequestAdapterInterface;
use App\RequestModel\AbstractRequest;
use App\Validator\InputValidatorBuilder;
use App\Validator\Validator;

class WordEditRequest extends AbstractRequest
{
    private $validatorBuilder;

    public ?string $knownLanguage;
    public ?string $foreignLanguage;

    private WordRepositoryAdapter $wordRepositoryAdapter;
    private int $dictionaryId;
    private int $wordId;

    public function __construct(RequestAdapterInterface $request, InputValidatorBuilder $validatorBuilder, WordRepositoryAdapter $wordRepositoryAdapter)
    {
        $this->validatorBuilder = $validatorBuilder;
        $this->wordRepositoryAdapter = $wordRepositoryAdapter;
        $data = $request->getJsonRequestData();

        $this->dictionaryId = $request->getParameters()['dictionaryId'];
        $this->wordId = $request->getParameters()['wordId'];

        $this->knownLanguage = $this->getData($data, 'knownLanguage');
        $this->foreignLanguage = $this->getData($data, 'foreignLanguage');
    }

    public function getValidator(): Validator
    {
        $config['filterSkipField'] = 'getId';
        $config['filterSkipValue'] = $this->wordId;

        $config['filter'] = ['dictionary' => $this->dictionaryId];

        if (!empty($this->knownLanguage)) {
            $this->validatorBuilder->addUniqueInput('knownLanguage', 'dictionaryWordUnique', $this->knownLanguage, $this->wordRepositoryAdapter, 'knownLanguage', $config);
        }

        if (!empty($this->foreignLanguage)) {
            $this->validatorBuilder->addUniqueInput('foreignLanguage', 'dictionaryWordUnique', $this->foreignLanguage, $this->wordRepositoryAdapter, 'foreignLanguage', $config);
        }
        return $this->validatorBuilder->getValidator();
    }
}
