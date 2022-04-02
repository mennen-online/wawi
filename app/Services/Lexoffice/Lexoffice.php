<?php

namespace App\Services\Lexoffice;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

abstract class Lexoffice
{
    protected const API_URL = 'https://api.lexoffice.io/v1';

    public const ENDPOINT = '/';

    public function __construct(
        protected ?int $maxPageSize = null,
        protected ?int $size = 500,
        protected ?int $page = null
    ) {
    }

    /**
     * @return int|null
     */
    public function getMaxPageSize(): ?int {
        return $this->maxPageSize;
    }

    /**
     * @param  int|null  $maxPageSize
     * @return Lexoffice
     */
    public function setMaxPageSize(?int $maxPageSize): self {
        $this->maxPageSize = $maxPageSize;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getSize(): ?int {
        return $this->size;
    }

    /**
     * @param  int|null  $size
     * @return Lexoffice
     */
    public function setSize(?int $size): self {
        if($this->maxPageSize && $size > $this->maxPageSize) {
            $size = $this->maxPageSize;
        }
        $this->size = $size;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPage(): ?int {
        return $this->page;
    }

    /**
     * @param  int|null  $page
     * @return Lexoffice
     */
    public function setPage(?int $page): self {
        $this->page = $page;

        return $this;
    }

    public function index() {
        $request = $this->prepareRequest();

        $query = [];

        foreach(get_object_vars($this) as $var => $value) {
            if($value !== null) {
                $query[$var] = $value;
            }
        }

        $results = collect();

        $page = 0;

        do{
            $query['page'] = $page;
            $response = json_decode($request->get($this->getEndpoint(), $query)->body());
            $results->add($response->content);
            $page ++;
        }while(!$response->last);

        return $results->flatten();
    }

    public function show(string $id) {
        return json_decode($this->prepareRequest()->get($this->getEndpoint($id))->body());
    }

    public function store(array $data) {
        return $this->prepareRequest()->post($this->getEndpoint(), $data);
    }

    public function update(string $id, array $data) {
        return $this->prepareRequest()->put($this->getEndpoint($id), $data);
    }

    private function getEndpoint(?string $id = null) {
        $endpoint = str($this::ENDPOINT)->lower();

        if(!Str::startsWith($endpoint, '/')) {
            $endpoint = $endpoint->prepend('/');
        }

        if($id) {
            if(!Str::endsWith($endpoint, '/')) {
                $endpoint = $endpoint->append('/');
            }
            $endpoint = $endpoint->append($id);
        }

        return self::API_URL.$endpoint->toString();
    }

    private function prepareRequest() {
        return Http::withToken(config('lexoffice.token'));
    }
}