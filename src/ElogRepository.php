<?php

namespace Jlab\ElogRepository;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class ElogRepository
{
    /**
     * @var string
     */
    protected $url = '';

    /**
     * @var Collection
     */
    protected $entries;

    /**
     * @var array
     */
    protected $whereConditions = [];

    /**
     * @var string
     */
    protected $queryString = '';


    /**
     * LogEntryRepository constructor.
     * @param string $url
     */
    public function __construct(string $url = ''){
        $this->url = $url ?: config('elog-repository.api');
        $this->entries = new Collection();
    }


    /**
     * Does the lognumber exist on the server?
     * Unfortunately the logbooks API doesn't return a 404 for
     * Not Found, so we're forced to try and receive the entire entry.
     * @param $lognumber
     */
    public function exists($lognumber){
        return $this->find($lognumber) != null;
    }

    /**
     * Retrieve the specified logentry
     *
     * @param $lognumber
     */
    public function find($lognumber){
        $this->entries = new Collection();
        $response = $this->http()->get($this->logentryUrl($lognumber));
        if ($response->ok()
            && array_key_exists('stat',$response->json())
            && $response->json()['stat'] == 'ok'
            && array_key_exists('data',$response->json())
            && array_key_exists('entry', $response->json()['data']))
        {
            $this->entries->push($response->json()['data']['entry']);
            return $this->entries->first();
        }
        return null;
    }

    /**
     * Chainable constraints that will be used to query the API
     * The valid key values are documented at
     * @see https://logbooks.jlab.org/content/available-fields
     * @param $key
     * @param $value
     */
    public function where($key, $value){
        $this->whereConditions[$key] = $value;
        return $this;
    }

    /**
     * Get logentry data collection.
     */
    public function get(){
        $this->entries = new Collection();
        $response = $this->http()->get($this->queryUrl());
        if ($response->ok()){
            $this->entries = $this->collectEntries($response);
        }
        return $this->entries;
    }

    protected function collectEntries(Response $response){
        $entries = new Collection();
        if ($response->json() && array_key_exists('data', $response->json())){
            $data = $response->json()['data'];
            if (array_key_exists('entries', $data)){
                $entries = collect($data['entries']);
            }
        }
        return $entries;
    }

    protected function entriesUrl(){
        return $this->url.'/entries';
    }

    protected function queryUrl(){
        $url = $this->entriesUrl().'/';
        $this->buildQuery();
        if ( $this->queryString ){
            $url .= '?'.$this->queryString;
        }
        return $url;
    }

    protected function buildQuery(){
        $this->queryString = http_build_query($this->whereConditions);
    }

    protected function logentryUrl($lognumber){
        return $this->entriesUrl().'/'.$lognumber;
    }

    /**
     * Obtains the HTTP client handle with custom timeout limit configured.
     *
     * @return \Illuminate\Http\Client\PendingRequest
     */
    protected function http(){
        return Http::timeout(config('elog-repository.timeout'));
    }
}
